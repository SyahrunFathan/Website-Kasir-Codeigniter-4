<?= $this->extend('Layout/main') ?>

<?= $this->section('content') ?>
<div class="row mt-2 px-2">
    <div class="col-md-12">
        <div class="card card-default color-palette-box">
            <div class="card-header">
                <i class="fas fa-calculator">&nbsp;KASIR</i>
            </div>
            <div class="card-body">
                <input type="hidden" class="form-control form-control-sm" style="color:red;font-weight:bold;" name="produkId" id="produkId" readonly>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nofaktur">Faktur</label>
                            <input type="text" class="form-control form-control-sm" style="color:red;font-weight:bold;" name="nofaktur" id="nofaktur" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" class="form-control form-control-sm" name="tanggal" id="tanggal" readonly value="<?= date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="napel">Pelanggan</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control form-control-sm" name="pelanggan" id="pelanggan" value="-" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tanggal">Aksi</label>
                            <div class="input-group">
                                <button class="btn btn-success" type="button" id="btnSimpanTransaksi">
                                    <i class="fa fa-save"></i>
                                </button>&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="kodebarcode">Kode Produk</label>
                            <input type="text" class="form-control form-control-sm" name="kodebarcode" id="kodebarcode" autofocus>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="napel">Produk</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control form-control-sm font-weight-bold text-uppercase" name="namaProduk" id="namaProduk" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="jml">Jumlah</label>
                            <input type="number" class="form-control form-control-sm" name="jumlah" id="jumlah" value="1">
                            <div class="invalid-feedback" id="e_jumlah"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="jml">Total Bayar</label>
                            <input type="hidden" class="form-control form-control-lg" name="totalbayar" id="totalbayar" style="text-align: right; color:blue; font-weight : bold; font-size:30pt;" placeholder="Rp " readonly value="0">
                            <input type="text" class="form-control form-control-lg" name="total" id="total" style="text-align: right; color:blue; font-weight : bold; font-size:30pt;" placeholder="Rp " readonly value="0">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" id="tabel-detail-penjualan"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="view-modal" style="display: none;"></div>
<?= $this->endSection('content') ?>

<?= $this->section('script') ?>
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 1500
    });
    $(document).ready(function() {
        kodePesanan();
        $('body').addClass('sidebar-collapse');
        $('#total').autoNumeric('init', {
            aSep: '.',
            aDec: ',',
            mDec: '',
            aSign: 'Rp '
        });
        $('#totalbayar').autoNumeric('init', {
            aSep: '.',
            aDec: ',',
            mDec: '',
            aSign: 'Rp '
        });

        $('#kodebarcode').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                const kode = $('#kodebarcode').val();
                const csrfToken = '<?= csrf_token() ?>';
                const csrfHash = '<?= csrf_hash() ?>';
                $.ajax({
                    type: "post",
                    url: "<?= base_url('kasir/cariProduk') ?>",
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
                            $('#jumlah').focus();
                        } else {
                            $('#view-modal').html(response.error).show();
                            $('#modal-produk').modal('show');
                        }
                    },
                    error: function(xhr) {
                        alert(xhr.status + '\n' + xhr.responseText)
                    }
                });
            }
        });

        $('#btnSimpanTransaksi').click(function(e) {
            e.preventDefault();
            const csrfToken = '<?= csrf_token() ?>';
            const csrfHash = '<?= csrf_hash() ?>';
            const nofaktur = $('#nofaktur').val();
            const idProduk = $('#produkId').val();
            const tanggal = $('#tanggal').val();
            const jumlah = $('#jumlah').val();
            const totalbayar = $('#totalbayar').val();
            if (idProduk == '') {
                Toast.fire({
                    title: 'Pastikan Semua Inputan Terisi!',
                    icon: 'error'
                })
            } else {
                $.ajax({
                    type: "post",
                    url: "<?= base_url('kasir/create_pesanan') ?>",
                    data: {
                        nofaktur: nofaktur,
                        idProduk: idProduk,
                        tanggal: tanggal,
                        jumlah: jumlah,
                        totalbayar: totalbayar,
                        [csrfToken]: csrfHash,
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.error) {
                            if (response.error.jumlah) {
                                $('#jumlah').addClass('is-invalid');
                                $('#e_jumlah').html(response.error.jumlah);
                            } else {
                                $('#jumlah').removeClass('is-invalid');
                                $('#e_jumlah').html('');
                            }
                        } else {
                            Toast.fire({
                                title: response.sukses.pesan,
                                icon: 'success'
                            }).then((result) => {
                                Kosong();
                                totalPembayaran();
                                $('#tabel-detail-penjualan').show();
                                $('#tabel-detail-penjualan').html(response.sukses.load);
                            })
                        }
                    },
                    error: function(xhr) {
                        alert(xhr.status + '\n' + xhr.responseText)
                    }
                });
            }
        });
    });

    function totalPembayaran() {
        const nofaktur = $('#nofaktur').val();
        const csrfToken = '<?= csrf_token() ?>';
        const csrfHash = '<?= csrf_hash() ?>';
        $.ajax({
            type: "post",
            url: "<?= base_url('kasir/total_pembayaran') ?>",
            data: {
                nofaktur: nofaktur,
                [csrfToken]: csrfHash,
            },
            dataType: "json",
            success: function(response) {
                if (response) {
                    $('#total').val(response);
                }
            },
            error: function(xhr) {
                alert(xhr.status + '\n' + xhr.responseText)
            }
        });

    }

    function kodePesanan() {
        $.ajax({
            url: "<?= base_url('kasir/kodePesanan') ?>",
            dataType: "json",
            success: function(response) {
                if (response) {
                    $('#nofaktur').val(response);
                }
            },
            error: function(xhr) {
                alert(xhr.status + '\n' + xhr.responseText)
            }
        });
    }

    function Kosong() {
        $('#produkId').val('');
        $('#namaProduk').val('');
        $('#kodebarcode').val('');
        $('#jumlah').val(1);
    }
</script>
<?= $this->endSection('script') ?>