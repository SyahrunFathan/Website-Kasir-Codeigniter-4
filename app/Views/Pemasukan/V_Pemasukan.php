<?= $this->extend('Layout/main') ?>

<?= $this->section('content') ?>
<div class="row mt-2 px-2">
    <div class="col-md-12">
        <div class="card card-info card-outline">
            <div class="card-header">
                <i class="fas fa-list-alt">&nbsp;Laporan Pemasukan</i>
            </div>
            <div class="card-body">
                <?= form_open('pemasukan/export_excel', ['class' => 'formExportExcel']) ?>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group px-2">
                            <label for="">Tanggal Awal :</label>
                            <input type="date" name="tglAwal" id="tglAwal" class="form-control" value="2020-01-01" autofocus required>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group px-2">
                            <label for="">Tanggal Akhir :</label>
                            <input type="date" name="tglAkhir" id="tglAkhir" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group px-2">
                            <label for="">Download Laporan :</label><br>
                            <button class="btn btn-xs btn-success" type="submit">
                                <i class="fas fa-file-download"></i> Download Laporan
                            </button>
                        </div>
                    </div>
                </div>
                <?= form_close() ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <i class="fas fa-list-alt">&nbsp;Data Penjualan</i>
                            </div>
                            <div class="card-body">
                                <table id="tabel-detail-pesanan" class="table table-sm table-bordered table-striped mt-2">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">No</th>
                                            <th>Kode Produk</th>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
                                            <th>Total</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection('content') ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        $('#tabel-detail-pesanan').DataTable({
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "ajax": '<?= base_url('pemasukan/getData') ?>'
        })
    });
</script>
<?= $this->endSection('script') ?>