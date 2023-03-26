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
            <div class="breadcrumb-item"><a href="<?= base_url(); ?>/admin/databarang/datasatuanbarang">Data Satuan Barang</a></div>
            <div class="breadcrumb-item">Satuan Barang</div>
        </div>
    </div>
</section>
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Satuan Barang</h4>
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
                        <?= $this->include('admin/importexcelbarang') ?>
                        <form method="post" action="<?= base_url(); ?>/admin/databarang/barang/process">
                            <?= csrf_field(); ?>
                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="">Nama Satuan Barang</label>
                                    <input id="" type="text" class="form-control" name="b_nama" autofocus placeholder="Masukan Satuan Barang" required>
                                </div>
                                <div class="form-group col-6">
                                    <label>Nama Kategori Barang</label>
                                    <select class="js-example-basic-single" name="k_id" id="">
                                        <option value="">--Pilih Nama Kategori Barang--</option>
                                        <?php foreach ($kategoribarang as $tb_kategori) { ?>
                                            <option value="<?php echo $tb_kategori['k_id']; ?>"><?php echo $tb_kategori['k_nama']; ?></option>
                                        <?php } ?>
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