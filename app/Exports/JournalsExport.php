<?php

namespace App\Exports;

use App\Models\Journal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JournalsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $publisherId;

    public function __construct($publisherId = null)
    {
        $this->publisherId = $publisherId;
    }

    public function collection()
    {
        $query = Journal::with(['publisher']);
        
        if ($this->publisherId) {
            $query->where('user_id', $this->publisherId);
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Jurnal',
            'Deskripsi',
            'ISSN',
            'E-ISSN',
            'Website',
            'Email',
            'Alamat',
            'Publisher',
            'Publisher Email',
            'Status',
            'Tanggal Dibuat',
            'Tanggal Update'
        ];
    }

    public function map($journal): array
    {
        return [
            $journal->id,
            $journal->name,
            $journal->description,
            $journal->issn,
            $journal->e_issn,
            $journal->website,
            $journal->email,
            $journal->address,
            $journal->publisher ? $journal->publisher->company_name : 'N/A',
            $journal->publisher ? $journal->publisher->email : 'N/A',
            ucfirst($journal->status ?? 'active'),
            $journal->created_at ? $journal->created_at->format('Y-m-d H:i:s') : '',
            $journal->updated_at ? $journal->updated_at->format('Y-m-d H:i:s') : ''
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ]
            ],
        ];
    }
}
