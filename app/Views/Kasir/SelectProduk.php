<div class="modal fade" id="modal-produk" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Pilih Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-bordered" id="tabel-produk-modal">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th>Kode</th>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($data as $row) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $row['kodeProduk'] ?></td>
                                    <td><?= $row['namaProduk'] ?></td>
                                    <td>Rp <?= number_format($row['hargaProduk'], 0, ',', '.') ?></td>
                                    <td><?= $row['stokProduk'] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-xs btn-info" onclick="pilihProduk(<?= $row['idProduk'] ?>)">
                                            <i class="fas fa-hand-pointer"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tabel-produk-modal').DataTable({
            "autoWidth": false
        })
    });

    function pilihProduk(kode) {
        const csrfToken = '<?= csrf_token() ?>'
        const csrfHash = '<?= csrf_hash() ?>'
        $.ajax({
            type: "post",
            url: "<?= base_url('kasir/ambilDataProduk') ?>",
            data: {
                kode: kode,
                [csrfToken]: csrfHash
            },
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    $('#produkId').val(response.sukses.produkId);
                    $('#namaProduk').val(response.sukses.produk);
                    $('#kodebarcode').val(response.sukses.kode);
                    $('#modal-produk').modal('hide');
                    $('#jumlah').focus();
                }
            },
            error: function(xhr) {
                alert(xhr.status + '\n' + xhr.responseText)
            }
        });
    }
</script>