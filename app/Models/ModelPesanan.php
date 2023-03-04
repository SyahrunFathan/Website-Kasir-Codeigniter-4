<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelPesanan extends Model
{
    protected $table            = 'pesanan';
    protected $primaryKey       = 'idPesanan';
    protected $allowedFields    = [
        'kodePesanan', 'jumlahPesanan', 'totalPesanan', 'tglPesanan', 'pelanggan', 'produkId'
    ];

    public function selectByKodePesanan($kode)
    {
        return $this->db->table('pesanan')
            ->join('produk', 'produk.idProduk=pesanan.produkId')
            ->where('kodePesanan', $kode)
            ->get()
            ->getResultArray();
    }

    public function deleteById($kode)
    {
        return $this->db->table('pesanan')->where('kodePesanan', $kode)->delete();
    }
}
