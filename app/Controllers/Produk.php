<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Hermawan\DataTables\DataTable;

class Produk extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Produk'
        ];
        return view('Produk/V_Produk', $data);
    }

    public function getData()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('produk')
            ->select('idProduk,kodeProduk,namaProduk,stokProduk,hargaProduk,kategori.namaKategori')
            ->join('kategori', 'kategori.idKategori=produk.kategoriId');

        return DataTable::of($builder)
            ->hide('idProduk')
            ->addNumbering()
            ->add('action', function ($row) {
                return "<button class=\"btn btn-xs btn-danger\" type=\"button\" onclick=\"hapusData('" . $row->idProduk . "')\" title=\"Hapus Data\"><i class=\"fas fa-trash-alt\"></i></button>
                <button class=\"btn btn-xs btn-warning\" type=\"button\" onclick=\"editData('" . $row->idProduk . "')\" title=\"Edit Data\"><i class=\"fas fa-edit\"></i></button>";
            })
            ->format('hargaProduk', function ($value) {
                return 'Rp ' . number_format($value, 0, ',', '.');
            })
            ->toJson();
    }

    public function formCreate()
    {
        $dataKategori = $this->modelKategori->findAll();
        $msg = [
            'sukses' => view('Produk/FormCreate', ['data' => $dataKategori])
        ];
        echo json_encode($msg);
    }

    public function create()
    {
        $kodeProduk = $this->request->getVar('kodeProduk');
        $namaProduk = $this->request->getVar('namaProduk');
        $stokProduk = $this->request->getVar('stokProduk');
        $hargaProduk = $this->request->getVar('hargaProduk');
        $kategoriId = $this->request->getVar('kategoriId');
        $satuan = $this->request->getVar('satuan');

        $ubahStringHarga = substr($hargaProduk, 3);
        $harga = str_replace(".", "", $ubahStringHarga);

        $validasi = \Config\Services::validation();
        $valid = $this->validate([
            'namaProduk' => [
                'label' => 'Produk',
                'rules' => 'required|is_unique[produk.namaProduk]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong!',
                    'is_unique' => '{field} ini sudah ada!',
                ]
            ],
            'kategoriId' => [
                'label' => 'Kategori',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong!'
                ]
            ],
            'stokProduk' => [
                'label' => 'Stok',
                'rules' => 'required|integer|is_natural_no_zero',
                'errors' => [
                    'required' => '{field} tidak boleh kosong!',
                    'integer' => '{field} hanya boleh berupa angka!',
                    'is_natural_no_zero' => '{field} tidak boleh bernilai 0!'
                ]
            ],
            'hargaProduk' => [
                'label' => 'Harga',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong!'
                ]
            ],
        ]);

        if (!$valid) {
            $msg = [
                'error' => [
                    'namaProduk' => $validasi->getError('namaProduk'),
                    'stokProduk' => $validasi->getError('stokProduk'),
                    'hargaProduk' => $validasi->getError('hargaProduk'),
                    'kategoriId' => $validasi->getError('kategoriId'),
                ]
            ];
        } else {
            $data = [
                'namaProduk' => $namaProduk,
                'kodeProduk' => $kodeProduk,
                'kategoriId' => $kategoriId,
                'satuan' => $satuan,
                'hargaProduk' => $harga,
                'stokProduk' => $stokProduk,
                'tgl_input' => date('Y-m-d')
            ];

            $this->modelProduk->save($data);

            $msg = [
                'sukses' => 'Anda berhasil menyimpan produk:)'
            ];
        }
        echo json_encode($msg);
    }

    public function kodeForProduk()
    {
        $kode = $this->modelProduk->generatorCode();
        echo json_encode($kode);
    }

    public function formUpdate()
    {
        $id = $this->request->getVar('id');
        $dataProduk = $this->modelProduk->find($id);
        $data = [
            'id' => $id,
            'kode' => $dataProduk['kodeProduk'],
            'produk' => $dataProduk['namaProduk'],
            'harga' => $dataProduk['hargaProduk'],
            'stok' => $dataProduk['stokProduk'],
            'kategori' => $dataProduk['kategoriId'],
            'satuan' => $dataProduk['satuan'],
            'data' => $this->modelKategori->findAll()
        ];

        $msg = [
            'sukses' => view('Produk/FormUpdate', $data)
        ];

        echo json_encode($msg);
    }

    public function update()
    {
        $id = $this->request->getVar('idProduk');
        $kodeProduk = $this->request->getVar('kodeProduk');
        $namaProduk = $this->request->getVar('namaProduk');
        $stokProduk = $this->request->getVar('stokProduk');
        $hargaProduk = $this->request->getVar('hargaProduk');
        $kategoriId = $this->request->getVar('kategoriId');
        $satuan = $this->request->getVar('satuan');

        $ubahStringHarga = substr($hargaProduk, 3);
        $harga = str_replace(".", "", $ubahStringHarga);

        $validasi = \Config\Services::validation();
        $valid = $this->validate([
            'namaProduk' => [
                'label' => 'Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong!',
                    'is_unique' => '{field} ini sudah ada!',
                ]
            ],
            'kategoriId' => [
                'label' => 'Kategori',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong!'
                ]
            ],
            'stokProduk' => [
                'label' => 'Stok',
                'rules' => 'required|integer|is_natural_no_zero',
                'errors' => [
                    'required' => '{field} tidak boleh kosong!',
                    'integer' => '{field} hanya boleh berupa angka!',
                    'is_natural_no_zero' => '{field} tidak boleh bernilai 0!'
                ]
            ],
            'hargaProduk' => [
                'label' => 'Harga',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong!'
                ]
            ],
        ]);

        if (!$valid) {
            $msg = [
                'error' => [
                    'namaProduk' => $validasi->getError('namaProduk'),
                    'stokProduk' => $validasi->getError('stokProduk'),
                    'hargaProduk' => $validasi->getError('hargaProduk'),
                    'kategoriId' => $validasi->getError('kategoriId'),
                ]
            ];
        } else {
            $data = [
                'namaProduk' => $namaProduk,
                'kodeProduk' => $kodeProduk,
                'kategoriId' => $kategoriId,
                'satuan' => $satuan,
                'hargaProduk' => $harga,
                'stokProduk' => $stokProduk
            ];

            $this->modelProduk->update($id, $data);

            $msg = [
                'sukses' => 'Anda berhasil mengubah produk:)'
            ];
        }
        echo json_encode($msg);
    }

    public function delete()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getVar('id');
            $dataProduj = $this->modelProduk->find($id);
            $this->modelProduk->delete($id);

            $msg = [
                'sukses' => 'Anda berhasil menghaspus produk ' . $dataProduj['namaProduk']
            ];

            echo json_encode($msg);
        } else {
            exit('Tidak dapat kami proses permintaan anda');
        }
    }
}
