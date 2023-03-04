<?= $this->extend('Layout/main') ?>

<?= $this->section('content') ?>
<div class="row mt-2 px-2">
    <div class="col-md-8">
        <div class="card card-outline card-info">
            <div class="card-header">
                <i class="fas fa-list-alt">&nbsp;Kategori Produk</i>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-striped" id="tabel-kategori">
                        <thead>
                            <tr>
                                <th style="width: 10%;">No.</th>
                                <th>Nama Kategori</th>
                                <th style="width: 15%;">#</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-outline card-info">
            <div class="card-header">
                <i class="fas fa-cogs">&nbsp;Kelolah Kategori Produk</i>
            </div>
            <div class="card-body">
                <?= form_open('kategori/create', ['class' => 'formCreateData']) ?>
                <input type="hidden" name="idKategori" id="idKategori" class="form-control">
                <div class="form-group">
                    <label>Input Kategori :</label>
                    <input type="text" name="kategori" id="kategori" class="form-control">
                    <div class="invalid-feedback" id="e_kategori"></div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-xs btn-info">
                        <i class="fab fa-telegram-plane">&nbsp;Simpan Data</i>
                    </button>
                    <button type="button" class="btn btn-xs btn-danger" id="btn-batal" style="display: none;">
                        <i class="fas fa-times-circle">&nbsp;Batalkan</i>
                    </button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
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
        $('#tabel-kategori').DataTable({
            "searching": false,
            "lengthChange": false,
            "processing": true,
            "serverSide": true,
            "autoWidth": false,
            "ajax": '<?= base_url('kategori/getData') ?>'
        })


        $('.formCreateData').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        if (response.error.kategori) {
                            $('#kategori').addClass('is-invalid');
                            $('#e_kategori').html(response.error.kategori);
                        } else {
                            $('#kategori').removeClass('is-invalid');
                            $('#e_kategori').html('');
                        }
                    } else {
                        Toast.fire({
                            title: response.sukses,
                            icon: 'success'
                        }).then((result) => {
                            kosong()
                            $('#tabel-kategori').DataTable().ajax.reload();
                        })
                    }
                },
                error: function(xhr) {
                    alert(xhr.status + '\n' + xhr.responseText)
                }
            });
        });

        $('#btn-batal').click(function(e) {
            e.preventDefault();
            kosong()
        });
    });

    function kosong() {
        $('#idKategori').val('');
        $('#kategori').val('');
        $('#kategori').removeClass('is-invalid');
        $('#e_kategori').html('');
        $('#btn-batal').hide();
    }

    function editData(id) {
        const csrfToken = '<?= csrf_token() ?>'
        const csrfHash = '<?= csrf_hash() ?>'
        $.ajax({
            type: "post",
            url: "<?= base_url('kategori/update') ?>",
            data: {
                id: id,
                [csrfToken]: csrfHash
            },
            dataType: "json",
            success: function(response) {
                if (response) {
                    $('#btn-batal').show();
                    $('#idKategori').val(response.id);
                    $('#kategori').val(response.kategori);
                }
            },
            error: function(xhr) {
                alert(xhr.status + '\n' + xhr.responseText)
            }
        });
    }

    function hapusData(id) {
        const csrfToken = '<?= csrf_token() ?>'
        const csrfHash = '<?= csrf_hash() ?>'
        Swal.fire({
            title: 'Anda Akan Menghapus Data!',
            text: "Anda Yakin Hapus Data Ini? Jika Iya, Silahkan Klik Hapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#008080',
            cancelButtonColor: '#a52a2a',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "<?= base_url('kategori/delete') ?>",
                    data: {
                        id: id,
                        [csrfToken]: csrfHash,
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            Toast.fire({
                                title: response.sukses,
                                icon: 'success'
                            }).then((result) => {
                                kosong()
                                $('#tabel-kategori').DataTable().ajax.reload();
                            })
                        }
                    },
                    error: function(xhr) {
                        alert(xhr.status + '\n' + xhr.responseText)
                    }
                });
            }
        })
    }
</script>
<?= $this->endSection('script') ?>