<div class="card card-outline card-info" id="tampilan-form-create">
    <div class="card-header">
        <i class="fas fa-list-alt">&nbsp;Form Create Produk</i>
        <div class="card-tools">
            <button type="button" class="btn btn-xs btn-warning btn-padding" id="btn-kembali">
                <i class="fas fa-backward"></i> Kembali
            </button>
        </div>
    </div>
    <div class="card-body">
        <?= form_open('produk/create', ['class' => 'formCreateData']) ?>
        <div class="form-group row">
            <label class="col-2 form-label">Kode Produk</label>
            <div class="col-4">
                <input type="text" name="kodeProduk" id="kodeProduk" class="form-control font-weight-bold text-primary" readonly>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 form-label">Produk</label>
            <div class="col-8">
                <input type="text" name="namaProduk" id="namaProduk" class="form-control" placeholder="Ex: Rinso">
                <div class="invalid-feedback" id="e_namaProduk"></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 form-label">Kategori</label>
            <div class="col-8">
                <select name="kategoriId" id="kategoriId" class="custom-select">
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($data as $row) : ?>
                        <option value="<?= $row['idKategori'] ?>"><?= $row['namaKategori'] ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback" id="e_kategoriId"></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 form-label">Satuan</label>
            <div class="col-8">
                <select name="satuan" id="satuan" class="custom-select">
                    <option value="">-- Pilih Satuan --</option>
                    <option value="PCS">PCS</option>
                    <option value="BTG">BTG</option>
                    <option value="DUS">DUS</option>
                    <option value="KG">KG</option>
                    <option value="LT">LT</option>
                    <option value="BKS">BKS</option>
                    <option value="PCK">PCK</option>
                </select>
                <div class="invalid-feedback" id="e_satuan"></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 form-label">Stok</label>
            <div class="col-2">
                <input type="number" name="stokProduk" id="stokProduk" class="form-control" placeholder="0">
                <div class="invalid-feedback" id="e_stokProduk"></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 form-label">Harga</label>
            <div class="col-4">
                <input type="text" name="hargaProduk" id="hargaProduk" class="form-control font-weight-bold text-danger" placeholder="Rp ">
                <div class="invalid-feedback" id="e_hargaProduk"></div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-2 form-label"></label>
            <div class="col-4">
                <button type="submit" class="btn btn-sm btn-info">
                    <i class="fab fa-telegram-plane"></i> Simpan Data
                </button>
            </div>
        </div>
        <?= form_close() ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Sweet Alert
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500
        });

        // Kode Produk
        kodeForProduk()

        // Focus
        $('#namaProduk').focus()

        // Button For To Back
        $('#btn-kembali').click(function(e) {
            e.preventDefault();
            $('#tampilan-form-create').hide();
            $('#tampilan-produk').show();
            $('#tabel-produk').DataTable().ajax.reload();
        });
        // Auto Numeric Harga
        $('#hargaProduk').autoNumeric('init', {
            aSep: '.',
            aDec: ',',
            aSign: 'Rp ',
            mDec: '0'
        })

        // Simpan Data

        $('.formCreateData').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        if (response.error.namaProduk) {
                            $('#namaProduk').addClass('is-invalid');
                            $('#e_namaProduk').html(response.error.namaProduk);
                        } else {
                            $('#namaProduk').removeClass('is-invalid');
                            $('#e_namaProduk').html('');
                        }
                        if (response.error.stokProduk) {
                            $('#stokProduk').addClass('is-invalid');
                            $('#e_stokProduk').html(response.error.stokProduk);
                        } else {
                            $('#stokProduk').removeClass('is-invalid');
                            $('#e_stokProduk').html('');
                        }
                        if (response.error.hargaProduk) {
                            $('#hargaProduk').addClass('is-invalid');
                            $('#e_hargaProduk').html(response.error.hargaProduk);
                        } else {
                            $('#hargaProduk').removeClass('is-invalid');
                            $('#e_hargaProduk').html('');
                        }
                        if (response.error.kategoriId) {
                            $('#kategoriId').addClass('is-invalid');
                            $('#e_kategoriId').html(response.error.kategoriId);
                        } else {
                            $('#kategoriId').removeClass('is-invalid');
                            $('#e_kategoriId').html('');
                        }
                    } else {
                        Toast.fire({
                            title: response.sukses,
                            icon: 'success'
                        }).then((result) => {
                            kosong()
                            kodeForProduk()
                            $('#namaProduk').focus();
                        })
                    }
                },
                error: function(xhr) {
                    alert(xhr.status + '\n' + xhr.responseText)
                }
            });
        });
    });

    function kodeForProduk() {
        $.ajax({
            url: "<?= base_url('produk/kodeForProduk') ?>",
            dataType: "json",
            success: function(response) {
                if (response) {
                    $('#kodeProduk').val(response);
                }
            },
            error: function(xhr) {
                alert(xhr.status + '\n' + xhr.responseText)
            }
        });
    }

    function kosong() {
        $('#namaProduk').val('');
        $('#hargaProduk').val('');
        $('#stokProduk').val('');
        $('#kategoriId').val('');
        $('#satuan').val('');
        $('#kategoriId').removeClass('is-invalid');
        $('#e_kategoriId').html('');
        $('#hargaProduk').removeClass('is-invalid');
        $('#e_hargaProduk').html('');
        $('#stokProduk').removeClass('is-invalid');
        $('#e_stokProduk').html('');
        $('#namaProduk').removeClass('is-invalid');
        $('#e_namaProduk').html('');
    }
</script>