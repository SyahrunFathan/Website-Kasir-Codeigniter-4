<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Hermawan\DataTables\DataTable;

class Kasir extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Kasir'
        ];
        return view('Kasir/V_Kasir', $data);
    }

    public function kodePesanan()
    {
        $kode = $this->modelDetailPesanan->generatorCode();
        echo json_encode($kode);
    }

    public function cariProduk()
    {
        $kode = $this->request->getVar('kode');
        $produk = $this->modelProduk->selectByKode($kode);
        if ($produk) {
            $msg = [
                'sukses' => [
                    'produkId' => $produk['idProduk'],
                    'produk' => $produk['namaProduk'],
                    'kode' => $produk['kodeProduk'],
                ]
            ];
        } else {
            $dataProduk = $this->modelProduk->findAll();
            $msg = [
                'error' => view('Kasir/SelectProduk', ['data' => $dataProduk])
            ];
        }
        echo json_encode($msg);
    }

    public function ambilDataProduk()
    {
        $kode = $this->request->getVar('kode');
        $produk = $this->modelProduk->find($kode);
        $msg = [
            'sukses' => [
                'produkId' => $produk['idProduk'],
                'produk' => $produk['namaProduk'],
                'kode' => $produk['kodeProduk'],
            ]
        ];

        echo json_encode($msg);
    }

    public function getData($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('pesanan')
            ->join('produk', 'produk.idProduk=pesanan.produkId')
            ->select('idPesanan,kodePesanan,produk.namaProduk,jumlahPesanan,produk.hargaProduk,totalPesanan')
            ->where('kodePesanan', $id);

        return DataTable::of($builder)
            ->hide('idPesanan')
            ->addNumbering()
            ->add('action', function ($row) {
                return "<button type=\"button\" class=\"btn btn-sm btn-danger\" onclick=\"hapusData('" . $row->idPesanan . "')\" title=\"Hapus Data\"><i class=\"fas fa-trash-alt\"></i></button>";
            }, 'last')
            ->format('hargaProduk', function ($valud) {
                return 'Rp ' . number_format($valud, 0, ',', '.');
            })
            ->format('totalPesanan', function ($valud) {
                return 'Rp ' . number_format($valud, 0, ',', '.');
            })
            ->toJson();
    }

    public function create_pesanan()
    {
        $idProduk = $this->request->getVar('idProduk');
        $nofaktur = $this->request->getVar('nofaktur');
        $tanggal = $this->request->getVar('tanggal');
        $jumlah = $this->request->getVar('jumlah');
        $totalbayar = $this->request->getVar('totalbayar');

        $produk = $this->modelProduk->find($idProduk);
        $str_total = substr($totalbayar, 3);
        $total_replace = str_replace(".", "", $str_total);
        $total = intval(($jumlah * $produk['hargaProduk']) + $total_replace);

        $validasi = \Config\Services::validation();

        $valid = $this->validate([
            'jumlah' => [
                'label' => 'Jumlah Pesanan',
                'rules' => 'is_natural_no_zero',
                'errors' => [
                    'is_natural_no_zero' => '{field} tidak bisa berupa 0 ataupun (-)!'
                ]
            ]
        ]);

        if (!$valid) {
            $msg = [
                'error' => [
                    'jumlah' => $validasi->getError('jumlah')
                ]
            ];
        } else {
            $data = [
                'kodePesanan' => $nofaktur,
                'jumlahPesanan' => $jumlah,
                'totalPesanan' => $total,
                'tglPesanan' => $tanggal,
                'pelanggan' => '-',
                'produkId' => $idProduk
            ];

            $simpan = $this->modelPesanan->insert($data);
            if ($simpan == true) {
                $dataStok = $produk['stokProduk'] - $jumlah;
                $this->modelProduk->update($idProduk, ['stokProduk' => $dataStok]);
                $msg = [
                    'sukses' => [
                        'pesan' => 'Pesanan berhasil di tambahkan',
                        'load' => view('Kasir/TabelPesanan')
                    ]
                ];
            } else {
                $msg = [
                    'error' => 'Pesanan tidak berhasil di tambahkan'
                ];
            }
        }
        echo json_encode($msg);
    }

    public function total_pembayaran()
    {
        $nofaktur = $this->request->getVar('nofaktur');
        $dataPesanan = $this->modelPesanan
            ->selectSum('totalPesanan', 'total')
            ->where('kodePesanan', $nofaktur)
            ->get()
            ->getRowArray();

        $total = 'Rp ' . number_format($dataPesanan['total'], 0, ',', '.');
        echo json_encode($total);
    }

    public function delete()
    {
        $id = $this->request->getVar('id');
        $pesanan = $this->modelPesanan->find($id);
        $produk = $this->modelProduk->find($pesanan['produkId']);
        $stok = $pesanan['jumlahPesanan'] + $produk['stokProduk'];
        $this->modelPesanan->delete($id);
        $this->modelProduk->update($produk['idProduk'], ['stokProduk' => $stok]);


        $msg = 200;
        echo json_encode($msg);
    }

    public function formTransaksi()
    {
        $nofaktur = $this->request->getVar('nofaktur');
        $total = $this->request->getVar('total');
        $data = [
            'nofaktur' => $nofaktur,
            'total' => $total
        ];

        $msg = [
            'sukses' => view('Kasir/ModalTransaksi', $data)
        ];
        echo json_encode($msg);
    }

    public function pembayaran()
    {
        $nofaktur   = $this->request->getVar('nofaktur');
        $total      = $this->request->getVar('total');
        $uang       = $this->request->getVar('uang');
        $str_total      = substr($total, 3);
        $replace_total  = str_replace(".", "", $str_total);
        $str_uang       = substr($uang, 3);
        $replace_uang   = str_replace(".", "", $str_uang);
        $kembalian      = $replace_uang - $replace_total;

        // Pindahkan Data Temp ke Data Pasti
        $dataPesanan = $this->modelPesanan->selectByKodePesanan($nofaktur);
        foreach ($dataPesanan as $key) {
            $data = [
                'kodePesanan'   => $key['kodePesanan'],
                'jumlahPesanan' => $key['jumlahPesanan'],
                'totalPesanan'  => $key['totalPesanan'],
                'tglPesanan'    => $key['tglPesanan'],
                'pelanggan'     => $key['pelanggan'],
                'produkId'      => $key['produkId'],
            ];

            $this->modelDetailPesanan->insert($data);
        }

        if ($dataPesanan) {
            $this->modelPesanan->deleteById($nofaktur);
        }
        $msg = [
            'sukses' => view('Kasir/ModalStruk', [
                'nofaktur'  => $nofaktur,
                'uang'      => 'Rp ' . number_format($replace_uang, 0, ',', '.'),
                'total'     => 'Rp ' . number_format($replace_total, 0, ',', '.'),
                'kembalian' => 'Rp ' . number_format($kembalian, 0, ',', '.'),
                'data'      => $dataPesanan,
            ])
        ];

        echo json_encode($msg);
    }
}
