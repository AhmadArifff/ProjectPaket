<?= $this->extend('admin/layout/default') ?>
<?= $this->section('title') ?>
<title>Forms Edit Data Kategori Barang &mdash; ARISYA</title>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1>Data Transaksi</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">Data Transaksi</div>
            <div class="breadcrumb-item"><a href="<?= base_url(); ?>/admin/datatransaksi/datatransaksi">Data Transaksi Pembayaran</a></div>
            <div class="breadcrumb-item">Transaksi Pembayaran</div>
        </div>
    </div>
</section>
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Edit Data Transaksi Pembayaran</h4>
                    </div>

                    <div class="card-body">
                        <?php if (!empty(session()->getFlashdata('error'))) : ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <h4>Periksa Entrian Form</h4>
                                </hr />
                                <?php echo session()->getFlashdata('error'); ?>
                            </div>
                        <?php endif; ?>
                        <form method="post" action="" id="text-editor">
                            <?= csrf_field(); ?>
                            <div class="row">
                                <input type="hidden" name="no" value="<?= $tb_transaksi['t_id'] ?>">
                                <div class="form-group col-6">
                                    <label>Nama Pengambil Paket</label>
                                    <select class="js-example-basic-single" name="u_id" id="">
                                        <option value="">--Pilih Nama Pengambil Paket--</option>
                                        <?php foreach ($DataUser as $tb_user) { ?>
                                            <option value="<?php echo $tb_user['u_id']; ?>" <?= $tb_user['u_id'] == $tb_transaksi['u_id'] ? 'selected' : '' ?>><?php echo $tb_user['u_nama']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label>Nama Paket</label>
                                    <select class="js-example-basic-single" name="p_id" id="">
                                        <option value="">--Pilih Nama Paket--</option>
                                        <?php foreach ($DataPaket as $tb_paket) { ?>
                                            <option value="<?php echo $tb_paket['p_id']; ?>" <?= $tb_paket['p_id'] == $tb_transaksi['p_id'] ? 'selected' : '' ?>><?php echo $tb_paket['p_nama']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label>Jumlah Paket</label>
                                    <input type="text" name="t_qty" class="form-control" placeholder="Masukan Jumlah Paket" required value="<?= $tb_transaksi['t_qty'] ?>">
                                </div>
                                <div class="form-group col-6">
                                    <label>Status Transaksi Paket</label>
                                    <select class="js-example-basic-single" name="t_status" required>
                                        <option value="">--Pilih Role Akses--</option>
                                        <option value="BELUM LUNAS" <?= $tb_transaksi['t_status'] == 'Belum Lunas' || $tb_transaksi['t_status'] == 'BELUM LUNAS' ? 'selected' : '' ?>>BELUM LUNAS</option>
                                        <option value="SUDAH LUNAS" <?= $tb_transaksi['t_status'] == 'Sudah Lunas' || $tb_transaksi['t_status'] == 'SUDAH LUNAS' ? 'selected' : '' ?>>SUDAH LUNAS</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block" name="submit">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="simple-footer">
                    Copyright &copy; Arisya 2023
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>