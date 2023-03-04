<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelProduk extends Model
{
    protected $table            = 'produk';
    protected $primaryKey       = 'idProduk';
    protected $allowedFields    = [
        'kodeProduk', 'namaProduk', 'stokProduk', 'hargaProduk', 'kategoriId', 'satuan', 'tgl_input'
    ];

    public function generatorCode()
    {
        $tanggal = date('Y-m-d');
        $builder = $this->db->table('produk')
            ->selectMax('kodeProduk', 'kodeMax')
            ->where('tgl_input', $tanggal)
            ->get()
            ->getRowArray();
        $hasilKode = $builder['kodeMax'];

        $urutan = substr($hasilKode, -4);
        $nextUrutan = intval($urutan) + 1;

        $kd = 'PD' . date('dm', strtotime($tanggal)) . sprintf('%04s', $nextUrutan);
        return $kd;
    }

    public function selectByKode($kode)
    {
        return $this->db->table('produk')
            ->where('kodeProduk', $kode)
            ->get()
            ->getRowArray();
    }
}
