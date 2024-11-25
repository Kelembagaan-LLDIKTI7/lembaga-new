<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PtExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $ptQuery = DB::table('organisasis')
            ->where('organisasis.organisasi_type_id', 3)
            ->leftJoin('organisasis as parent', 'organisasis.parent_id', '=', 'parent.id')
            ->leftJoin('akreditasis', function ($join) {
                $join->on('organisasis.id', '=', 'akreditasis.id_organization')
                    ->whereRaw('akreditasis.akreditasi_tgl_akhir = (
                                SELECT MAX(sub.akreditasi_tgl_akhir)
                                FROM akreditasis as sub
                                WHERE sub.id_organization = organisasis.id
            )');
            })
            ->leftJoin('lembaga_akreditasis', 'akreditasis.id_lembaga_akreditasi', '=', 'lembaga_akreditasis.id')
            ->leftJoin('peringkat_akreditasis', 'akreditasis.id_peringkat_akreditasi', '=', 'peringkat_akreditasis.id')
            ->orderBy('organisasis.organisasi_kode')
            ->select(
                'organisasis.organisasi_kode as kode',
                'organisasis.organisasi_nama as nama',
                'organisasis.organisasi_nama_singkat as nama_singkat',
                'parent.organisasi_nama as badan_penyelenggara',
                'organisasis.organisasi_alamat as alamat',
                'organisasis.organisasi_kota as kota',
                'peringkat_akreditasis.peringkat_nama as akreditasi',
                'lembaga_akreditasis.lembaga_nama as lembaga_akreditasi',
                'akreditasis.akreditasi_sk as no_sk_akreditasi',
                'akreditasis.akreditasi_tgl_awal as tgl_terbit_sk_akreditasi',
                'akreditasis.akreditasi_tgl_akhir as tgl_akhir_sk_akreditasi',
            );

        if (Auth::user()->hasRole('Badan Penyelenggara')) {
            $ptQuery->where('organisasis.parent_id', Auth::user()->id_organization);
        }

        if (Auth::user()->hasRole('Perguruan Tinggi')) {
            $ptQuery->where('organisasis.id', Auth::user()->id_organization);
        }

        $pt = $ptQuery->get();

        return $pt;
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Nama',
            'Nama Singkatan',
            'Badan Penyelenggara',
            'Alamat',
            'Kota',
            'Akreditasi',
            'Lembaga Akreditasi',
            'No SK Akreditasi',
            'Tanggal Terbit SK Akreditasi',
            'Tanggal Berakhir SK Akreditasi',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Set header row height
                $sheet->getDelegate()->getRowDimension(1)->setRowHeight(45);

                // Apply header styles
                $sheet->getDelegate()->getStyle('A1:K1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Apply data row styles
                $sheet->getDelegate()->getStyle('A2:K' . $sheet->getHighestRow())->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $currentDate = now()->format('Y-m-d');

                // dd($currentDate);

                // Loop through the data rows
                foreach ($sheet->getDelegate()->getRowIterator(2) as $row) {
                    $rowIndex = $row->getRowIndex();
                    $cellValue = $sheet->getDelegate()->getCell('K' . $rowIndex)->getValue();

                    // dd($cellValue);

                    if ($cellValue && $cellValue < $currentDate) {
                        // Apply red background if the date is past the current date
                        $sheet->getDelegate()->getStyle('K' . $rowIndex)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['argb' => 'FFFF0000'], // Red color
                            ],
                        ]);
                    }
                }
            },
        ];
    }
}
