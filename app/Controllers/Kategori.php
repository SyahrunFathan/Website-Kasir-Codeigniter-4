<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Hermawan\DataTables\DataTable;

class Kategori extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Kategori'
        ];
        return view('Kategori/V_Kategori', $data);
    }

    public function getData()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('kategori')->select('idKategori,namaKategori');

        return DataTable::of($builder)
            ->hide('idKategori')
            ->addNumbering()
            ->add('action', function ($row) {
                return "<button class=\"btn btn-xs btn-danger\" type=\"button\" onclick=\"hapusData('" . $row->idKategori . "')\" title=\"Hapus Data\"><i class=\"fas fa-trash-alt\"></i></button>
                <button class=\"btn btn-xs btn-warning\" type=\"button\" onclick=\"editData('" . $row->idKategori . "')\" title=\"Edit Data\"><i class=\"fas fa-edit\"></i></button>";
            }, 'last')
            ->toJson();
    }

    public function create()
    {
        $idKategori = $this->request->getVar('idKategori');
        $kategori = $this->request->getVar('kategori');

        $validasi = \Config\Services::validation();

        if ($idKategori == null || $idKategori == '') {
            $valid = $this->validate([
                'kategori' => [
                    'label' => 'Kategori',
                    'rules' => 'required|is_unique[kategori.namaKategori]',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong!',
                        'is_unique' => '{field} ini sudah ada!',
                    ]
                ]
            ]);

            if (!$valid) {
                $msg = [
                    'error' => [
                        'kategori' => $validasi->getError('kategori')
                    ]
                ];
            } else {
                $data = [
                    'namaKategori' => $kategori
                ];

                $this->modelKategori->save($data);

                $msg = [
                    'sukses' => 'Anda berhasil menyimpan kategori'
                ];
            }
        } else {
            $valid = $this->validate([
                'kategori' => [
                    'label' => 'Kategori',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong!',
                    ]
                ]
            ]);

            if (!$valid) {
                $msg = [
                    'error' => [
                        'kategori' => $validasi->getError('kategori')
                    ]
                ];
            } else {
                $data = [
                    'namaKategori' => $kategori
                ];

                $this->modelKategori->update($idKategori, $data);

                $msg = [
                    'sukses' => 'Anda berhasil mengubah kategori'
                ];
            }
        }

        echo json_encode($msg);
    }

    public function update()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getVar('id');
            $dataKategori = $this->modelKategori->find($id);
            $data = [
                'id' => $id,
                'kategori' => $dataKategori['namaKategori']
            ];

            echo json_encode($data);
        } else {
            exit('Tidak dapat kami proses permintaan anda');
        }
    }

    public function delete()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getVar('id');
            $dataKategori = $this->modelKategori->find($id);
            $this->modelKategori->delete($id);

            $msg = [
                'sukses' => 'Anda berhasil menghaspus kategori ' . $dataKategori['namaKategori']
            ];

            echo json_encode($msg);
        } else {
            exit('Tidak dapat kami proses permintaan anda');
        }
    }
}
