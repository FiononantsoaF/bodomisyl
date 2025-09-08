<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;


class AppointmentsDayExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{

    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
        return DB::table('appointments as ap')
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
            ->orderBy('ap.id','desc')
            ->whereBetween('ap.start_times', [
                $this->start . ' 00:00:00',
                $this->end   . ' 23:59:59'
            ])
            ->get();
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
            'Date de reservation',

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
