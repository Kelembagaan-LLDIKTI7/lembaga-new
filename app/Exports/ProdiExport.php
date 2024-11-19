<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProdiExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $prodi = DB::table('program_studis')
            ->leftJoin('organisasis', 'program_studis.id_organization', '=', 'organisasis.id')
            ->leftJoin('akreditasis', function ($join) {
                $join->on('program_studis.id', '=', 'akreditasis.id_prodi')
                    ->whereRaw('akreditasis.akreditasi_tgl_akhir = (
                                SELECT MAX(sub.akreditasi_tgl_akhir)
                                FROM akreditasis as sub
                                WHERE sub.id_prodi = program_studis.id
            )');
            })
            ->leftJoin('lembaga_akreditasis', 'akreditasis.id_lembaga_akreditasi', '=', 'lembaga_akreditasis.id')
            ->leftJoin('peringkat_akreditasis', 'akreditasis.id_peringkat_akreditasi', '=', 'peringkat_akreditasis.id')
            ->orderBy('organisasis.organisasi_kode')
            ->orderBy('program_studis.prodi_kode')
            ->select(
                'organisasis.organisasi_kode as kode_pt',
                'organisasis.organisasi_nama as nama_pt',
                'program_studis.prodi_kode as prodi_kode',
                'program_studis.prodi_nama as prodi_nama',
                'program_studis.prodi_jenjang as prodi_jenjang',
                'akreditasis.akreditasi_sk as no_sk_akreditasi',
                'akreditasis.akreditasi_tgl_awal as tgl_terbit_sk_akreditasi',
                'peringkat_akreditasis.peringkat_nama as akreditasi',
                'akreditasis.akreditasi_tgl_akhir as tgl_akhir_sk_akreditasi',
            )
            ->get();

        $data = [];
        $no_pt = 1;
        $no_prodi = 1;
        $change = false;
        $first = true;

        foreach ($prodi as $key => $value) {
            $tahun = $value->tgl_terbit_sk_akreditasi ? date('Y', strtotime($value->tgl_terbit_sk_akreditasi)) : '';
            if ($key > 0 && $value->nama_pt !== $data[$key - 1]['Nama PT']) {
                $no_pt += 1;
                $change = true;
                $first = true;
            }

            if ($change) {
                $no_prodi = 1;
                $change = false;
                $data[] = [
                    'No' => $no_pt,
                    'Kode PT' => $value->kode_pt,
                    'Nama PT' => $value->nama_pt,
                    'No Prodi' => $no_prodi,
                    'Kode Prodi' => $value->prodi_kode,
                    'Prodi' => $value->prodi_nama,
                    'Program' => $value->prodi_jenjang,
                    'Nomor SK' => $value->no_sk_akreditasi,
                    'Tahun' => $tahun,
                    'Akreditasi BAN-PT' => $value->akreditasi,
                    'Tanggal Kedaluarsa' => $value->tgl_akhir_sk_akreditasi,
                ];
            } else {
                $data[] = [
                    'No' => $first ? $no_pt : '',
                    'Kode PT' => $value->kode_pt,
                    'Nama PT' => $value->nama_pt,
                    'No Prodi' => $no_prodi,
                    'Kode Prodi' => $value->prodi_kode,
                    'Prodi' => $value->prodi_nama,
                    'Program' => $value->prodi_jenjang,
                    'Nomor SK' => $value->no_sk_akreditasi,
                    'Tahun' => $tahun,
                    'Akreditasi BAN-PT' => $value->akreditasi,
                    'Tanggal Kedaluarsa' => $value->tgl_akhir_sk_akreditasi,
                ];
            }

            $no_prodi += 1;

            $first = false;
        }

        return collect($data);
    }


    public function headings(): array
    {
        return [
            'No',
            'Kode PT',
            'Nama PT',
            'No Prodi',
            'Kode Prodi',
            'Prodi',
            'Program',
            'Nomor SK',
            'Tahun',
            'Akreditasi BAN-PT',
            'Tanggal Kedaluarsa',
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
