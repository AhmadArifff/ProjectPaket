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
            <div class="breadcrumb-item"><a href="<?= base_url(); ?>/admin/databarang/datapaketbarang">Data Paket Barang</a></div>
            <div class="breadcrumb-item">Paket Barang</div>
        </div>
    </div>
</section>
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Paket Barang</h4>
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
                        <form method="post" action="<?= base_url(); ?>/admin/databarang/paketbarang/process">
                            <?= csrf_field(); ?>
                            <div class="row">
                                <div class="form-group col-6">
                                    <label>Nama Paket Barang</label>
                                    <select class="js-example-basic-single" name="kp_id" id="" required>
                                        <option value="">--Pilih Nama Periode Pembayaran--</option>
                                        <?php foreach ($kategoripaket as $tb_kategori_paket) { ?>
                                            <option value="<?php echo $tb_kategori_paket['kp_id']; ?>"><?php echo $tb_kategori_paket['kp_nama']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label for="">Nama Periode Pembayaran</label>
                                    <select class="js-example-basic-single" name="pe_id" id="" required>
                                        <option value="">--Pilih Nama Periode Pembayaran--</option>
                                        <?php foreach ($payperiode as $tb_pay_periode) { ?>
                                            <option value="<?php echo $tb_pay_periode['pe_id']; ?>"><?php echo $tb_pay_periode['pe_nama']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label>Harga Asli Paket Barang</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                Rp.
                                            </div>
                                        </div>
                                        <input type="text" name="p_hargaBarang" class="form-control currency" placeholder="Masukan Harga Asli Paket Barang" required>
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <label>Harga Jual Paket Barang</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                Rp.
                                            </div>
                                        </div>
                                        <input type="text" name="p_hargaJual" class="form-control hargajual" placeholder="Masukan Harga Jual Paket Barang" required>
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <label>Nama Packaging Barang</label>
                                    <select class="js-example-basic-single" name="pa_id" id="pa_id" required>
                                        <option value="">--Pilih Nama Packaging Barang--</option>
                                        <?php foreach ($packagingbarang as $tb_packaging) { ?>
                                            <option value="<?php echo $tb_packaging['pa_id']; ?>"><?php echo $tb_packaging['pa_nama']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <!-- <div class="form-group col-6">
                                    <label>Harga Packaging Barang</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                Rp.
                                            </div>
                                        </div>
                                        <input type="text" name="pa_harga" id="pa_harga" class="form-control cashback" placeholder="Masukan Cashback Paket Barang" required>
                                    </div>
                                </div> -->
                                <div class="form-group col-6">
                                    <label>Cashback Paket Barang</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                Rp.
                                            </div>
                                        </div>
                                        <input type="text" name="p_cashback" class="form-control cashback" placeholder="Masukan Cashback Paket Barang" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Nama Barang Supplier</label>
                                    <select class="js-example-basic-multiple" name="p_barang[]" id="" multiple="multiple" required>
                                        <?php foreach ($barangsupplier as $tb_sup_barang) { ?>
                                            <?php foreach ($supplier as $tb_supplier) { ?>
                                                <?php foreach ($barang as $tb_barang) { ?>
                                                    <?php if ($tb_sup_barang['s_id'] == $tb_supplier['s_id'] && $tb_sup_barang['b_id'] == $tb_barang['b_id']) {
                                                        $tampil = $tb_supplier['s_nama'] . " - " . $tb_barang['b_nama'];
                                                    } ?>
                                                <?php } ?>
                                            <?php } ?>
                                            <option value="<?php echo $tb_sup_barang['sb_id']; ?>"><?= $tampil ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block" name="submit">
                                    Simpan
                                </button>
                            </div>

                            <div id="pa_harga"></div>
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