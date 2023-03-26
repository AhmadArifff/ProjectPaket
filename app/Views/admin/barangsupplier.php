<?= $this->extend('admin/layout/default') ?>
<?= $this->section('title') ?>
<title>Forms Register &mdash; ARISYA</title>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1>Data Barang</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">Data Barang</div>
            <div class="breadcrumb-item"><a href="<?= base_url(); ?>/admin/databarang/datasatuanbarangsupplier">Data Barang Supplier</a></div>
            <div class="breadcrumb-item">Barang Supplier</div>
        </div>
    </div>
</section>
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Barang Supplier</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty(session()->getFlashdata('error'))) : ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <h4>Periksa Entrian Form</h4>
                                </hr />
                                <?php echo session()->getFlashdata('error'); ?>
                            </div>
                        <?php endif; ?>
                        <?php if (session()->getFlashdata('success')) : ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo session()->getFlashdata('success'); ?>
                            </div>
                        <?php endif; ?>
                        <?= $this->include('admin/importexcelbarangsupplier') ?>
                        <form method="post" action="<?= base_url(); ?>/admin/databarang/barangsupplier/process" enctype="multipart/form-data">
                            <?= csrf_field(); ?>
                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="">Nama Supplier Barang</label>
                                    <select class="js-example-basic-single" name="s_id" id="" autofocus>
                                        <option value="">--Pilih Nama Supplier Barang--</option>
                                        <?php foreach ($supplier as $tb_supplier) { ?>
                                            <option value="<?php echo $tb_supplier['s_id']; ?>"><?php echo $tb_supplier['s_nama']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label>Nama Barang</label>
                                    <select class="js-example-basic-single" name="b_id" id="">
                                        <option value="">--Pilih Nama Barang--</option>
                                        <?php foreach ($barang as $tb_barang) { ?>
                                            <option value="<?php echo $tb_barang['b_id']; ?>"><?php echo $tb_barang['b_nama']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label>Harga Asli</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                Rp.
                                            </div>
                                        </div>
                                        <input type="text" name="sb_hargaasli" class="form-control currency" placeholder="Masukan Harga Asli" required>
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <label>Harga Jual</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                Rp.
                                            </div>
                                        </div>
                                        <input type="text" name="sb_hargajual" class="form-control hargaasli" placeholder="Masukan Harga Asli" required>
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <label for="">Jumlah Barang (qty)</label>
                                    <input id="" type="text" class="form-control" name="sb_qty" placeholder="Masukan Jumlah Barang (qty)" required>
                                </div>
                                <div class="form-group col-6">
                                    <label>Berat/Ukuran Barang</label>
                                    <div class="input-group">
                                        <input type="text" name="sb_berat/ukuran" class="form-control currency" placeholder="Masukan Berat/Ukuran Barang" required>
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <select class="form-control selectric" name="berat/ukuran" id="">
                                                    <option value="gram">gram</option>
                                                    <option value="kg">kg</option>
                                                    <option value="liter">liter</option>
                                                    <option value="ml">ml</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Foto Barang</label>
                                    <input type="file" name="sb_foto" class="form-control" id="file" required accept=".jpg, .jpeg, .png" /></p>
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