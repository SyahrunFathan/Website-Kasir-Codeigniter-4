<?= $this->extend('Layout/main') ?>

<?= $this->section('content') ?>
<div class="row mt-2 px-2">
    <div class="col-md-12">
        <div class="card card-outline card-info" id="tampilan-produk">
            <div class="card-header">
                <i class="fas fa-list-alt">&nbsp;Data Produk</i>
                <div class="card-tools">
                    <button type="button" class="btn btn-xs btn-info btn-padding" id="btn-create">
                        <i class="fas fa-plus-circle"></i> Tambah Data Produk
                    </button>
                    <button type="button" class="btn btn-xs btn-excel btn-padding" id="btn-export-excel">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-striped" id="tabel-produk">
                        <thead>
                            <tr>
                                <th style="width: 7%;">No</th>
                                <th>Kode</th>
                                <th>Produk</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Kategori</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="form-create-or-update" style="display: none;"></div>
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
        $('#tabel-produk').DataTable({
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": '<?= base_url('produk/getData') ?>'
        })

        $('#btn-create').click(function(e) {
            e.preventDefault();
            $('#tampilan-produk').hide();
            formCreateOrUpdate()
        });
    });

    function formCreateOrUpdate() {
        $.ajax({
            url: "<?= base_url('produk/formCreate') ?>",
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    $('#form-create-or-update').html(response.sukses).show();
                }
            },
            error: function(xhr) {
                alert(xhr.status + '\n' + xhr.responseText)
            }
        });
    }

    function editData(id) {
        const csrfToken = '<?= csrf_token() ?>'
        const csrfHash = '<?= csrf_hash() ?>'
        $.ajax({
            type: "post",
            url: "<?= base_url('produk/formUpdate') ?>",
            data: {
                id: id,
                [csrfToken]: csrfHash,
            },
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    $('#tampilan-produk').hide();
                    $('#form-create-or-update').html(response.sukses).show();
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
                    url: "<?= base_url('produk/delete') ?>",
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
                                $('#tabel-produk').DataTable().ajax.reload();
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