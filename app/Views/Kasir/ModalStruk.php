<div class="modal fade" id="modal-struk" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase font-weight-bold" id="staticBackdropLabel">Struk Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="font-weight-bold font-italic">Toko AR Shop</h4>
                        <p><strong>No Hp :</strong> 081342504930</p>
                        <p><strong>Alamat :</strong> Jl. Lekatu, Kota Palu</p>
                        <p>======================================</p>
                        <p><strong>Kasir :</strong> <?= session()->get('name') ?></p>
                        <p><strong>Faktur :</strong> <?= $nofaktur ?></p>
                    </div>
                    <div class="col-sm-12">
                        <table class="table table-sm" style="border: none; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Pesanan</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $row) : ?>
                                    <tr>
                                        <td><?= $row['namaProduk'] ?></td>
                                        <td><?= $row['jumlahPesanan'] ?></td>
                                        <td>Rp <?= number_format($row['hargaProduk'], 0, ',', '.') ?></td>
                                        <td>Rp <?= number_format($row['totalPesanan'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                            <tfoot class="text-right">
                                <tr>
                                    <th colspan="3">Total Bayar</th>
                                    <td><?= $total ?></td>
                                </tr>
                                <tr>
                                    <th colspan="3">Uang Pelanggan</th>
                                    <td><?= $uang ?></td>
                                </tr>
                                <tr>
                                    <th colspan="3">Kembalian</th>
                                    <td><?= $kembalian ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>