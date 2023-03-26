<?= $this->extend('admin/layout/default') ?>
<?= $this->section('title') ?>
<title>Forms Edit Data Kategori Barang &mdash; ARISYA</title>
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
                        <h4>Edit Data Satuan Barang</h4>
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
                                <input type="hidden" name="no" value="<?= $tb_barang['b_id'] ?>" />
                                <div class="form-group col-6">
                                    <label for="">Nama Satuan Barang</label>
                                    <input id="" type="text" class="form-control" value="<?= $tb_barang['b_nama'] ?>" name="b_nama" autofocus placeholder="Masukan Satuan Barang" required>
                                </div>
                                <div class="form-group col-6">
                                    <label>Nama Kategori Barang</label>
                                    <select class="js-example-basic-single" name="k_id" id="">
                                        <option value="">--Pilih Nama Kategori Barang--</option>
                                        <?php foreach ($kategoribarang as $tb_kategori) { ?>
                                            <option value="<?php echo $tb_kategori['k_id']; ?>" <?= $tb_kategori['k_id'] == $tb_barang['k_id'] ? 'selected' : '' ?>><?php echo $tb_kategori['k_nama']; ?></option>
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