<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelDetailPesanan extends Model
{
    protected $table            = 'detailpesanan';
    protected $primaryKey       = 'idDetailPesanan';
    protected $allowedFields    = [
        'kodePesanan', 'jumlahPesanan', 'totalPesanan', 'tglPesanan', 'pelanggan', 'produkId'
    ];

    public function generatorCode()
    {
        $tanggal = date('Y-m-d');
        $builder = $this->db->table('detailpesanan')
            ->selectMax('kodePesanan', 'kodeMax')
            ->where('tglPesanan', $tanggal)
            ->get()
            ->getRowArray();
        $hasilKode = $builder['kodeMax'];

        $urutan = substr($hasilKode, -4);
        $nextUrutan = intval($urutan) + 1;

        $kd = 'F' . date('ymd', strtotime($tanggal)) . sprintf('%04s', $nextUrutan);
        return $kd;
    }

    public function selectByDate($tglAwal, $tglAkhir)
    {
        return $this->db->table('detailpesanan')
            ->join('produk', 'produk.idProduk=detailpesanan.produkId')
            ->where('tglPesanan >=', $tglAwal)
            ->where('tglPesanan <=', $tglAkhir)
            ->get()
            ->getResultArray();
    }
}
