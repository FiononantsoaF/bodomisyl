<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Http\Requests\TestimonialRequest;
use Illuminate\Http\Request;

/**
 * Class TestimonialController
 * @package App\Http\Controllers
 */
class TestimonialdbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testimonials = Testimonial::paginate();

        return view('testimonial.index', compact('testimonials'))
            ->with('i', (request()->input('page', 1) - 1) * $testimonials->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $testimonial = new Testimonial();
        return view('testimonial.create', compact('testimonial'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->hasFile('file_path')) {
            $request->validate([
                'file_path' => 'required|mimes:jpg,jpeg,png,bmp,tiff|max:4096',
            ]);
            $fileName = time() . '.' . $request->file('file_path')->extension();
            $request->file('file_path')->move(public_path('imageTemoignage'), $fileName);
            Testimonial::create([
                'file_path' => $fileName,
                'is_active' => true,
            ]);

            return redirect()->back()->with('success', 'Image enregistrée avec succès !');
        }

        return redirect()->back()->with('error', 'Aucune image sélectionnée.');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $testimonial = Testimonial::find($id);

        return view('testimonial.show', compact('testimonial'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $testimonial = Testimonial::find($id);

        return view('testimonial.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TestimonialRequest $request, Testimonial $testimonial)
    {
        $testimonial->update($request->validated());

        return redirect()->route('testimonials.index')
            ->with('success', 'Testimonial updated successfully');
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $path = public_path('imageTemoignage/' . $testimonial->file_path);
        if (file_exists($path)) unlink($path);
        $testimonial->delete();

        return redirect()->back()->with('success', 'Image supprimée avec succès !');
    }

    public function toggle($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->is_active = !$testimonial->is_active;
        $testimonial->save();

        return redirect()->back()->with('success', 'Statut mis à jour.');
    }

}
