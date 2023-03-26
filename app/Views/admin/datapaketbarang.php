<?= $this->extend('admin/layout/default') ?>
<?= $this->section('title') ?>
<title>Forms Data Barang &mdash; ARISYA</title>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<section class="section">
    <?= csrf_field(); ?>
    <div class="section-header">
        <h1>Data Barang</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">Data Barang</div>
            <div class="breadcrumb-item">Data Paket Barang</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-18">
                <div class="card">
                    <div class="card-header">
                        <h4>Data Paket Barang</h4>
                        <div class="card-header-action">
                            <a href="<?= base_url(); ?>/admin/databarang/paketbarang" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data</a>
                            <a href="<?= base_url('admin/admincontrollers/exportfileexcelpaketbarang') ?>" class="btn btn-primary"><i class="fas fa-download"></i> Export Data</a>
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
                                    <th>Nama Paket Barang</th>
                                    <th>Nama Periode Pembayaran</th>
                                    <th>Jumlah Periode Pembayaran</th>
                                    <th>Harga Asli Paket Barang</th>
                                    <th>Harga Jual Paket Barang</th>
                                    <th>Nama Packaging Barang</th>
                                    <th>Harga Packaging Barang</th>
                                    <th>Cashback Paket Barang</th>
                                    <th>Insentive Paket Barang</th>
                                    <th>Laba Paket Barang</th>
                                    <th>Persentase Laba Paket Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Action Button</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($tb_paket as $paket) : ?>
                                    <tr>
                                        <td align="center"><?= $i ?></td>
                                        <td><?php foreach ($kategoripaket as $kb) :
                                                if ($paket['kp_id'] == $kb['kp_id']) {
                                                    echo $kb['kp_nama'];
                                                }
                                            endforeach ?></td>
                                        <td><?php foreach ($payperiode as $data) :
                                                if ($paket['pe_id'] == $data['pe_id']) {
                                                    echo $data['pe_nama'];
                                                    $jumlahperiode = $data['pe_periode'];
                                                }
                                            endforeach ?></td>
                                        <td><?= $jumlahperiode ?></td>
                                        <td>Rp. <?php $angka = $paket['p_hargaBarang'];
                                                $string = number_format($angka, 2);
                                                echo $string; ?></td>
                                        <td>Rp. <?php $angka =  $paket['p_hargaJual'];
                                                $string = number_format($angka, 2);
                                                echo $string; ?></td>
                                        <td><?php foreach ($packagingbarang as $tb_packaging) :
                                                if ($paket['pa_id'] == $tb_packaging['pa_id']) {
                                                    echo $tb_packaging['pa_nama'];
                                                    $hargapackaging = $tb_packaging['pa_harga'];
                                                }
                                            endforeach ?></td>
                                        <td>Rp. <?php $angka =  $hargapackaging;
                                                $string = number_format($angka, 2);
                                                echo $string; ?></td>
                                        <td>Rp. <?php $angka =  $paket['p_cashback'];
                                                $string = number_format($angka, 2);
                                                echo $string; ?></td>
                                        <td>Rp. <?php $angka =  $paket['p_insentive'];
                                                $string = number_format($angka, 2);
                                                echo $string; ?></td>
                                        <td>Rp. <?php $angka =  $paket['p_laba'];
                                                $string = number_format($angka, 2);
                                                echo $string; ?></td>
                                        <td><?= number_format($paket['p_persentaseLaba'], 2) . "%" ?></td>
                                        <td><?php foreach ($pengambilanpaket as $tb_pengambilan_paket) :
                                                if ($paket['p_id'] == $tb_pengambilan_paket['pp_p_id']) {
                                                    foreach ($barangsupplier as $tb_sup_barang) {
                                                        foreach ($supplier as $tb_supplier) {
                                                            foreach ($barang as $tb_barang) {
                                                                $sb = $tb_pengambilan_paket['p_sb_id'];
                                                                if ($sb == $tb_sup_barang['sb_id']) {
                                                                    $si = $tb_sup_barang['s_id'];
                                                                    $bi = $tb_sup_barang['b_id'];
                                                                    if ($si == $tb_supplier['s_id'] && $bi == $tb_barang['b_id']) {
                                                                        $tampil = $tb_supplier['s_nama'] . " - " . $tb_barang['b_nama'] . ", ";
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                    echo $tampil;
                                                }
                                            endforeach ?></td>

                                        <td>
                                            <a href="<?= base_url('admin/databarang/paketbarang/' . $paket['p_id'] . '/edit') ?>" class="btn btn-warning btn-sm update-record" data-p_id="<?php echo $paket['p_id']; ?>"><i class="fas fa-pencil-alt"></i></a>
                                            <a href="#" data-href="<?= base_url('admin/databarang/paketbarang/' . $paket['p_id'] . '/delete') ?>" onclick="confirmToDelete(this)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
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