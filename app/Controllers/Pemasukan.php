<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Hermawan\DataTables\DataTable;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pemasukan extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Pemasukan'
        ];
        return view('Pemasukan/V_Pemasukan', $data);
    }

    public function getData()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('detailpesanan')
            ->join('produk', 'produk.idProduk=detailpesanan.produkId')
            ->select('idDetailPesanan,produk.kodeProduk,produk.namaProduk,jumlahPesanan,produk.hargaProduk,totalPesanan,tglPesanan');

        return DataTable::of($builder)
            ->addNumbering()
            ->hide('idDetailPesanan')
            ->format('hargaProduk', function ($value) {
                return 'Rp ' . number_format($value, 0, ',', '.');
            })
            ->format('totalPesanan', function ($value) {
                return 'Rp ' . number_format($value, 0, ',', '.');
            })
            ->toJson();
    }

    public function export_excel()
    {
        $tglAwal = $this->request->getVar('tglAwal');
        $tglAkhir = $this->request->getVar('tglAkhir');

        $dataPesanan = $this->modelDetailPesanan->selectByDate($tglAwal, $tglAkhir);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // header
        $styleHeader = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ];
        $sheet->setCellValue('A1', 'LAPORAN PEMASUKAN');
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->setCellValue('A2', 'Laporan: ' . $tglAwal . ' s/d ' . $tglAkhir);
        $sheet->getStyle('A1')->applyFromArray($styleHeader);
        $sheet->getStyle('A2')->applyFromArray($styleHeader);

        $styleColomn = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],

            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->setCellValue('A4', 'No')->getColumnDimension('A')->setAutoSize(true);
        $sheet->setCellValue('B4', 'Kode Pesanan')->getColumnDimension('B')->setAutoSize(true);
        $sheet->setCellValue('C4', 'Nama Produk')->getColumnDimension('C')->setAutoSize(true);
        $sheet->setCellValue('D4', 'Tanggal Pesanan')->getColumnDimension('D')->setAutoSize(true);
        $sheet->setCellValue('E4', 'Jumlah')->getColumnDimension('E')->setAutoSize(true);
        $sheet->setCellValue('F4', 'Harga')->getColumnDimension('F')->setAutoSize(true);
        $sheet->setCellValue('G4', 'Total')->getColumnDimension('G')->setAutoSize(true);

        $sheet->getStyle('A4')->applyFromArray($styleColomn);
        $sheet->getStyle('B4')->applyFromArray($styleColomn);
        $sheet->getStyle('C4')->applyFromArray($styleColomn);
        $sheet->getStyle('D4')->applyFromArray($styleColomn);
        $sheet->getStyle('E4')->applyFromArray($styleColomn);
        $sheet->getStyle('F4')->applyFromArray($styleColomn);
        $sheet->getStyle('G4')->applyFromArray($styleColomn);

        $no = 1;
        $column = 5;
        $total = 0;
        $colomnAkhir = 1;
        foreach ($dataPesanan as $row) {
            $total += $row['totalPesanan'];

            $sheet->setCellValue('A' . $column, $no);
            $sheet->setCellValue('B' . $column, $row['kodeProduk']);
            $sheet->setCellValue('C' . $column, $row['namaProduk']);
            $sheet->setCellValue('D' . $column, $row['tglPesanan']);
            $sheet->setCellValue('E' . $column, $row['jumlahPesanan']);
            $sheet->setCellValue('F' . $column, 'Rp ' . number_format($row['hargaProduk'], 0, ',', '.'));
            $sheet->setCellValue('G' . $column, 'Rp ' . number_format($row['totalPesanan'], 0, ',', '.'));

            $sheet->getStyle('A' . $column)->applyFromArray($styleColomn);
            $sheet->getStyle('B' . $column)->applyFromArray($styleColomn);
            $sheet->getStyle('C' . $column)->applyFromArray($styleColomn);
            $sheet->getStyle('D' . $column)->applyFromArray($styleColomn);
            $sheet->getStyle('E' . $column)->applyFromArray($styleColomn);
            $sheet->getStyle('F' . $column)->applyFromArray($styleColomn);
            $sheet->getStyle('G' . $column)->applyFromArray($styleColomn);

            $no++;
            $column++;
        }

        $nextcolomn = $column + $colomnAkhir;

        $sheet->mergeCells('A' . $nextcolomn . ':' . 'F' . $nextcolomn);
        $sheet->setCellValue('A' . $nextcolomn, 'Total Pemasukan');
        $sheet->setCellValue('G' . $nextcolomn, 'Rp ' . number_format($total, 0, ',', '.'));

        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->setTitle('Laporan Pemasukan');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename = "Laporan Pemasukan.xlsx"');
        header('Cache-control:max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
