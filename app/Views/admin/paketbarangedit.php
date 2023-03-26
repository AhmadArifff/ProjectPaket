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
            <div class="breadcrumb-item"><a href="<?= base_url(); ?>/admin/databarang/datapaketbarang">Data Barang Supplier</a></div>
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
                        <h4>Edit Data Barang Supplier</h4>
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
                                <input type="hidden" name="no" id="p_id" value="<?= $tb_paket['p_id'] ?>" />
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label>Nama Paket Barang</label>
                                        <!-- <input type="text" name="p_nama" class="form-control" placeholder="Masukan Paket Barang" autofocus required> -->
                                        <select class="js-example-basic-single" name="p_nama" required>
                                            <option value="">--Pilih Nama Paket Barang--</option>
                                            <option value="MINI" <?= $tb_paket['p_nama'] == 'MINI' ? 'selected' : '' ?>>MINI</option>
                                            <option value="MIDI" <?= $tb_paket['p_nama'] == 'MIDI' ? 'selected' : '' ?>>MIDI</option>
                                            <option value="JUMBO" <?= $tb_paket['p_nama'] == 'JUMBO' ? 'selected' : '' ?>>JUMBO</option>
                                            <option value="SUPER" <?= $tb_paket['p_nama'] == 'SUPER' ? 'selected' : '' ?>>SUPER</option>
                                            <option value="TEENS" <?= $tb_paket['p_nama'] == 'TEENS' ? 'selected' : '' ?>>TEENS</option>
                                            <option value="HYPER" <?= $tb_paket['p_nama'] == 'HYPER' ? 'selected' : '' ?>>HYPER</option>
                                            <option value="HAMPERS 1" <?= $tb_paket['p_nama'] == 'HAMPERS 1' ? 'selected' : '' ?>>HAMPERS 1</option>
                                            <option value="HAMPERS 2" <?= $tb_paket['p_nama'] == 'HAMPERS 2' ? 'selected' : '' ?>>HAMPERS 2</option>
                                            <option value="HAMPERS 3" <?= $tb_paket['p_nama'] == 'HAMPERS 3' ? 'selected' : '' ?>>HAMPERS 3</option>
                                            <option value="HAMPERS 4" <?= $tb_paket['p_nama'] == 'HAMPERS 4' ? 'selected' : '' ?>>HAMPERS 4</option>
                                            <option value="HAMPERS 5" <?= $tb_paket['p_nama'] == 'HAMPERS 5' ? 'selected' : '' ?>>HAMPERS 5 </option>
                                            <option value="GROCERY 1" <?= $tb_paket['p_nama'] == 'GROCERY 1' ? 'selected' : '' ?>>GROCERY 1</option>
                                            <option value="GROCERY 2" <?= $tb_paket['p_nama'] == 'GROCERY 2' ? 'selected' : '' ?>>GROCERY 2</option>
                                            <option value="GROCERY 3" <?= $tb_paket['p_nama'] == 'GROCERY 3' ? 'selected' : '' ?>>GROCERY 3</option>
                                            <option value="GROCERY 4" <?= $tb_paket['p_nama'] == 'GROCERY 4' ? 'selected' : '' ?>>GROCERY 4</option>
                                            <option value="GROCERY 5" <?= $tb_paket['p_nama'] == 'GROCERY 5' ? 'selected' : '' ?>>GROCERY 5</option>
                                            <option value="ORCHID" <?= $tb_paket['p_nama'] == 'ORCHID' ? 'selected' : '' ?>>ORCHID</option>
                                            <option value="JASMINE" <?= $tb_paket['p_nama'] == 'JASMINE' ? 'selected' : '' ?>>JASMINE</option>
                                            <option value="ROSE" <?= $tb_paket['p_nama'] == 'ROSE' ? 'selected' : '' ?>>ROSE</option>
                                            <option value="PREMIUM" <?= $tb_paket['p_nama'] == 'PREMIUM' ? 'selected' : '' ?>>PREMIUM</option>
                                            <option value="GLAM" <?= $tb_paket['p_nama'] == 'GLAM' ? 'selected' : '' ?>>GLAM</option>
                                            <option value="PRIOR" <?= $tb_paket['p_nama'] == 'PRIOR' ? 'selected' : '' ?>>PRIOR</option>
                                            <option value="SILVER" <?= $tb_paket['p_nama'] == 'SILVER' ? 'selected' : '' ?>>SILVER</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="">Nama Periode Pembayaran</label>
                                        <select class="js-example-basic-single" name="pe_id" id="" required>
                                            <option value="">--Pilih Nama Periode Pembayaran--</option>
                                            <?php foreach ($payperiode as $tb_pay_periode) { ?>
                                                <option value="<?php echo $tb_pay_periode['pe_id']; ?>" <?= $tb_pay_periode['pe_id'] == $tb_paket['pe_id'] ? 'selected' : '' ?>><?php echo $tb_pay_periode['pe_nama']; ?></option>
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
                                            <input type="text" name="p_hargaBarang" class="form-control currency" placeholder="Masukan Harga Asli Paket Barang" required value="<?= $tb_paket['p_hargaBarang'] ?>">
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
                                            <input type="text" name="p_hargaJual" class="form-control hargajual" placeholder="Masukan Harga Jual Paket Barang" required value="<?= $tb_paket['p_hargaJual'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Nama Packaging Barang</label>
                                        <select class="js-example-basic-single" name="pa_id" id="pa_id" required>
                                            <option value="">--Pilih Nama Packaging Barang--</option>
                                            <?php foreach ($packagingbarang as $tb_packaging) { ?>
                                                <option value="<?php echo $tb_packaging['pa_id']; ?>" <?= $tb_packaging['pa_id'] == $tb_paket['pa_id'] ? 'selected' : '' ?>><?php echo $tb_packaging['pa_nama']; ?></option>
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
                                            <input type="text" name="p_cashback" class="form-control cashback" placeholder="Masukan Cashback Paket Barang" required value="<?= $tb_paket['p_cashback'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Barang Supplier</label>
                                        <a id="clearButton" class="btn btn-warning btn-sm" style="margin-left: 0%;"><ion-icon name="refresh-outline"></ion-icon> Refresh</a>
                                        <select class="js-example-basic-multiple" name="p_barang[]" id="sb_id" multiple="multiple" required>
                                            <!-- <?php
                                                    foreach ($pengambilanpaket as $tb_pengambilan_paket) {
                                                        foreach ($barangsupplier as $tb_sup_barang) {
                                                            if ($tb_sup_barang['sb_id'] == $tb_pengambilan_paket['p_sb_id']) {
                                                                foreach ($supplier as $tb_supplier) {
                                                                    foreach ($barang as $tb_barang) {
                                                                        if ($tb_sup_barang['s_id'] == $tb_supplier['s_id'] && $tb_sup_barang['b_id'] == $tb_barang['b_id']) {
                                                                            $tampil = $tb_supplier['s_nama'] . " - " . $tb_barang['b_nama'];
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    ?>
                                            <option value="<?= $tb_sup_barang['sb_id'] ?>" <?= in_array($tb_sup_barang['sb_id'], $selected_barang) ? 'selected' : '' ?>>
                                                <?= $tampil ?>
                                            </option>
                                        <?php
                                                    }
                                        ?> -->
                                            <?php if ($selectitembarang == 'true') {
                                                foreach ($barangsupplier as $tb_sup_barang) {
                                                    foreach ($supplier as $tb_supplier) {
                                                        foreach ($barang as $tb_barang) {
                                                            if ($tb_sup_barang['s_id'] == $tb_supplier['s_id'] && $tb_sup_barang['b_id'] == $tb_barang['b_id']) {
                                                                $tampil = $tb_supplier['s_nama'] . " - " . $tb_barang['b_nama'];
                                                            }
                                                        }
                                                    }
                                            ?>
                                                    <option value="<?= $tb_sup_barang['sb_id'] ?>">
                                                        <?= $tampil ?>
                                                    </option>
                                                <?php } ?>
                                                <?php } else if ($selectitembarang == 'false') {
                                                foreach ($pengambilanpaket as $tb_pengambilan_paket) {
                                                    foreach ($barangsupplier as $tb_sup_barang) {
                                                        if ($tb_sup_barang['sb_id'] == $tb_pengambilan_paket['p_sb_id']) {
                                                            foreach ($supplier as $tb_supplier) {
                                                                foreach ($barang as $tb_barang) {
                                                                    if ($tb_sup_barang['s_id'] == $tb_supplier['s_id'] && $tb_sup_barang['b_id'] == $tb_barang['b_id']) {
                                                                        $tampil = $tb_supplier['s_nama'] . " - " . $tb_barang['b_nama'];
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                    if ($tb_paket['p_id'] == $tb_pengambilan_paket['pp_p_id']) {
                                                        foreach ($barangsupplier as $tb_su_barang) {
                                                            if ($tb_pengambilan_paket['p_sb_id'] == $tb_su_barang['sb_id']) {
                                                ?>
                                                                <option value="<?= $tb_su_barang['sb_id'] ?>" <?= $tb_su_barang['sb_id'] == $tb_pengambilan_paket['p_sb_id'] ? 'selected' : '' ?>>
                                                                    <?= $tampil ?>
                                                                </option>
                                            <?php }
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
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