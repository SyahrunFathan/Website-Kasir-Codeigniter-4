<div class="modal fade" id="modal-transaksi" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase font-weight-bold" id="staticBackdropLabel">Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="kodePesanan" class="col-4">Faktur</label>
                    <div class="col-8">
                        <input type="text" class="form-control form-control-lg" name="kodePesanan" id="kodePesanan" style="text-align: right; color:red; font-weight : bold; font-size:24pt;" value="<?= $nofaktur ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4">Total Bayar</label>
                    <div class="col-8">
                        <input type="text" class="form-control form-control-lg" name="total-bayar" id="total-bayar" style="text-align: right; color:blue; font-weight : bold; font-size:30pt;" placeholder="Rp " readonly value="<?= $total ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4">Uang Pelanggan</label>
                    <div class="col-8">
                        <input type="text" class="form-control form-control-lg" name="uang_pelanggan" id="uang_pelanggan" style="text-align: right; color:blue; font-weight : bold; font-size:30pt;" placeholder="Rp ">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4"></label>
                    <div class="col-8">
                        <button class="btn btn-success" type="button" id="btn-bayar">
                            <i class="fas fa-calculator"></i> Bayar Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#uang_pelanggan').autoNumeric('init', {
            aSep: '.',
            aDec: ',',
            mDec: '',
            aSign: 'Rp '
        })

        $('#btn-bayar').click(function(e) {
            e.preventDefault();
            const nofaktur = $('#kodePesanan').val();
            const total = $('#total-bayar').val();
            const uang = $('#uang_pelanggan').val();
            const csrfToken = '<?= csrf_token() ?>';
            const csrfHash = '<?= csrf_hash() ?>';
            $.ajax({
                type: "post",
                url: "<?= base_url('kasir/pembayaran') ?>",
                data: {
                    nofaktur: nofaktur,
                    total: total,
                    uang: uang,
                    [csrfToken]: csrfHash,
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        $('#modal-transaksi').modal('hide');
                        $('#tabel-detail-penjualan').hide();
                        Kosong();
                        kodePesanan();
                        totalPembayaran();
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Pembayaran Telah Selesai!',
                            icon: 'success'
                        }).then((result) => {
                            $('#view-modal').html(response.sukses).show();
                            $('#modal-struk').modal('show');
                        })
                    }
                },
                error: function(xhr) {
                    alert(xhr.status + '\n' + xhr.responseText)
                }
            });
        });
    });
</script>