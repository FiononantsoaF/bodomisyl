<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class AppointmentsDayExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    protected ?Carbon $start_date;
    protected ?Carbon $end_date;
    private ?string $phone;
    private ?string $email;
    private ?string $name;

    public function __construct(?Carbon $start_date, ?Carbon $end_date, ?string $phone = null, ?string $email = null, ?string $name = null) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->phone = $phone;
        $this->email = $email;
        $this->name = $name;
    }

    public function collection()
    {
        $query = DB::table('appointments as ap')
            ->select(
                "ap.id as idrdv",
                DB::raw("
                    CASE ap.status
                        WHEN 'pending' THEN 'En attente'
                        WHEN 'confirmed' THEN 'Validé'
                        WHEN 'cancelled' THEN 'Annulé'
                        ELSE ap.status
                    END as status
                "),
                "c.name as nomclient",
                "c.email",
                "c.phone",
                "c.address",
                "ap.comment",
                "sc.name as nomservice",
                "s.title as typeprestation",
                "ep.name as nomprestataire",
                "s.duration_minutes as duree_minute",
                DB::raw("DATE_FORMAT(ap.start_times,'%d-%m-%Y %H:%i') as date_reserver"),
                DB::raw("DATE_FORMAT(DATE_ADD(ap.start_times, INTERVAL s.duration_minutes MINUTE),'%d-%m-%Y %H:%i') as fin_prestation"),
                DB::raw("DATE_FORMAT(ap.created_at,'%d-%m-%Y %H:%i') as date_creation")
            )
            ->join('clients as c', 'c.id','=', 'ap.client_id')
            ->join('employees as ep', 'ep.id', '=','ap.employee_id')
            ->join('services as s', 's.id' ,'=', 'ap.service_id')
            ->join('service_category as sc', 'sc.id' ,'=', 's.service_category_id')
            ->orderBy('ap.id','desc');

        if ($this->phone) {
            $query->where('c.phone', 'LIKE', "%{$this->phone}%");
        }
        if ($this->email) {
            $query->where('c.email', 'LIKE', "%{$this->email}%");
        }

        if ($this->name) {
            $query->where('c.name', 'LIKE', "%{$this->name}%");
        }

        if ($this->start_date) {
            $query->whereDate('ap.start_times', '>=', \Carbon\Carbon::parse($this->start_date)->format('Y-m-d'));
        }

        if ($this->end_date) {
            $query->whereDate('ap.start_times', '<=', \Carbon\Carbon::parse($this->end_date)->format('Y-m-d'));
        }
        // dd($query->toSql());
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Num',
            'Statut',
            'Client',
            'Email client',
            'Contact client',
            'Adresse',
            'Commentaire client',
            'Formule',
            'Type de prestation',
            'Prestataire',
            'Durée (minutes)',
            'Date du rendez-vous',

        ];
    }

    public function map($row): array
    {
        return [
            $row->idrdv,
            ucfirst($row->status),
            $row->nomclient,
            $row->email,
            $row->phone,
            $row->address,
            $row->comment,
            $row->nomservice,
            $row->typeprestation,
            $row->nomprestataire,
            // number_format($row->prixservice, 2, ',', ' '),
            // number_format($row->prix_final, 2, ',', ' '),
            $row->duree_minute,
            $row->date_reserver,
        ];
    }
}
