<?= $this->extend('admin/layout/default') ?>
<?= $this->section('title') ?>
<title>Forms Data Barang &mdash; ARISYA</title>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<section class="section">
    <?= csrf_field(); ?>
    <div class="section-header">
        <h1>Data Transaksi</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">Data Transaksi</div>
            <div class="breadcrumb-item">Data Log Cicilan Pembayaran</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-18">
                <div class="card">
                    <div class="card-header">
                        <h4>Data Barang Supplier</h4>
                        <div class="card-header-action">
                            <a href="<?= base_url(); ?>/admin/datatransaksi/logcicilan" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
                            <a href="<?= base_url('admin/admincontrollers/exportfileexcellogcicilan') ?>" class="btn btn-primary"><i class="fas fa-download"></i> Export Data</a>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <?php if (session()->getFlashdata('success')) : ?>
                            <div class="alert alert-success w-auto" role="alert">
                                <?php echo session()->getFlashdata('success'); ?>
                            </div>
                        <?php endif; ?>
                        <table id="myTable" class="table table-striped table-bordered">
                            <thead>
                                <tr class="table-primary">
                                    <th class="text-center">#</th>
                                    <th>Nama Pengambil Paket</th>
                                    <th>Jumlah Bayar Cicilan</th>
                                    <th>Waktu Pembayaran Cicilan</th>
                                    <th>Bukti Pembayaran Cicilan</th>
                                    <th>Pembayaran Cicilan Kepada</th>
                                    <th>Action Button</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($tb_log_cicilan as $log_cicilan) : ?>
                                    <tr>
                                        <td align="center"><?= $log_cicilan['l_id'] ?></td>
                                        <td><?php foreach ($DataUser as $data) :
                                                if ($log_cicilan['u_id'] == $data['u_id']) {
                                                    echo $data['u_nama'];
                                                }
                                            endforeach ?></td>
                                        <td id="text-color-plus">Rp. <?php $angka =  $log_cicilan['l_jumlah_bayar'];
                                                                        $string = number_format($angka, 2);
                                                                        echo "+" . $string; ?></td>
                                        <td><?= $log_cicilan['l_approval_date'] ?></td>
                                        <td><img src="<?= base_url('foto-bukti-pembayaran/' . $log_cicilan['l_foto']) ?>" width="100px" style="border-radius: 5px;"></td>
                                        <td><?php foreach ($DataUser as $showdata) :
                                                if ($log_cicilan['l_approval_by'] == $showdata['u_id']) {
                                                    echo $showdata['u_nama'];
                                                }
                                            endforeach ?></td>
                                        <td>
                                            <a href="<?= base_url('admin/datatransaksi/logcicilan/' . $log_cicilan['l_id'] . '/edit') ?>" class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></a>
                                            <a href="#" data-href="<?= base_url('admin/datatransaksi/logcicilan/' . $log_cicilan['l_id'] . '/delete') ?>" onclick="confirmToDelete(this)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php $i++;
                                endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="confirm-dialog" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="h4">Apa kamu yakin hapus data ini?</h4>
                <p>Data akan dihapus dan hilang selamanya....</p>
            </div>
            <div class="modal-footer">
                <a href="#" role="button" id="delete-button" class="btn btn-danger">Delete</a>
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmToDelete(el) {
        $("#delete-button").attr("href", el.dataset.href);
        $("#confirm-dialog").modal('show');
    }
</script>
<?= $this->endSection() ?>