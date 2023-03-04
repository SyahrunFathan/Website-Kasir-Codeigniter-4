<table class="table table-sm table striped table-bordered" id="tabel-temp-pesanan">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Pesanan</th>
            <th>Pesanan</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Total</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<div class="d-flex justify-content-end mt-3">
    <button class="btn btn-success btn-sm" id="selesaiTransaksi" type="button">
        <i class="fas fa-save"></i> Selesaikan Transaksi
    </button>
</div>

<script>
    $(document).ready(function() {
        const nofaktur = $('#nofaktur').val();
        $('#tabel-temp-pesanan').DataTable({
            "lengthChange": false,
            "searching": false,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": '<?= base_url('kasir/getData/') ?>' + nofaktur,
        })

        $('#selesaiTransaksi').click(function(e) {
            e.preventDefault();
            const nofaktur = $('#nofaktur').val();
            const total = $('#total').val();
            const csrfToken = '<?= csrf_token() ?>';
            const csrfHash = '<?= csrf_hash() ?>';
            $.ajax({
                type: "post",
                url: "<?= base_url('kasir/formTransaksi') ?>",
                data: {
                    nofaktur: nofaktur,
                    total: total,
                    [csrfToken]: csrfHash
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        $('#view-modal').html(response.sukses).show();
                        $('#modal-transaksi').modal('show');
                    }
                },
                error: function(xhr) {
                    alert(xhr.status + '\n' + xhr.responseText)
                }
            });
        });
    });

    function hapusData(id) {
        const csrfToken = '<?= csrf_token() ?>';
        const csrfHash = '<?= csrf_hash() ?>';
        $.ajax({
            type: "post",
            url: "<?= base_url('kasir/delete') ?>",
            data: {
                id: id,
                [csrfToken]: csrfHash,
            },
            dataType: "json",
            success: function(response) {
                if (response) {
                    Kosong();
                    totalPembayaran();
                    $('#tabel-temp-pesanan').DataTable().ajax.reload();
                }
            }
        });
    }
</script>