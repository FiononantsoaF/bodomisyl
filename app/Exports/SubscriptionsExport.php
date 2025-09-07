<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class SubscriptionsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    public function collection()
    {
        return DB::table('subscriptions as s')
            ->join('clients as c', 's.client_id', '=', 'c.id')
            ->join('services as sv', 's.services_id', '=', 'sv.id')
            ->join('service_category as sc', 'sv.service_category_id', '=', 'sc.id')
            ->select(
                'c.name as nomclient',
                'sv.title as nomservice',
                'sc.name as typeprestation',
                's.total_session as total',
                's.used_session as session_achevee',
                'sv.price as prixservice',
                's.final_price as prix_final',
                's.period_start',
                's.period_end'
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            'Client',
            'Formule',
            'Service',
            'Séances totales',
            'Séances achevées',
            'Séances restantes',
            'Prix',
            'Prix final',
            'Début',
            'Fin',
        ];
    }

    public function map($row): array
    {
        return [
            $row->nomclient,
            $row->nomservice,
            $row->typeprestation,
            $row->total,
            $row->session_achevee,
            $row->total - $row->session_achevee,
            number_format($row->prixservice, 2, ',', ' '),
            number_format($row->prix_final, 2, ',', ' '),
            \Carbon\Carbon::parse($row->period_start)->format('d/m/Y'),
            \Carbon\Carbon::parse($row->period_end)->format('d/m/Y'),
        ];
    }
}
