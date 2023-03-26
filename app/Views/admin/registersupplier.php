<?= $this->extend('admin/layout/default') ?>
<?= $this->section('title') ?>
<title>Forms Register &mdash; ARISYA</title>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1>Forms Register</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">Forms Register</div>
            <div class="breadcrumb-item"><a href="<?= base_url(); ?>/admin/formsregister/datasupplier">Data Supplier</a></div>
            <div class="breadcrumb-item">Register Supplier</div>
        </div>
    </div>
</section>
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Register Supplier</h4>
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
                        <?= $this->include('admin/importexcelsupplier') ?>
                        <form method="post" action="<?= base_url(); ?>/admin/formsregister/registersupplier/process">
                            <?= csrf_field(); ?>
                            <div class="form-group">
                                <label for="">Nama Supplier</label>
                                <input id="" type="text" class="form-control" name="s_nama" autofocus placeholder="Masukan Nama Supplier" required>
                            </div>
                            <div class="form-group">
                                <label for="">Alamat Supplier</label>
                                <textarea id="" type="text" class="form-control h-100" name="s_alamat" placeholder="Masukan Alamat Supplier" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Contact Supplier</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control phone-number" name="s_contact" placeholder="Masukan Contact Supplier">
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