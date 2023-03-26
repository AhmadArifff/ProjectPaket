<li class="menu-header">Menu</li>
<li <?= ($AdminDashboard == 'dashboard') ? 'class="active"' : '' ?>>
    <a href="<?= base_url(); ?>/admin/dashboard" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
</li>
<li class="nav-item dropdown <?= ($RegisterUser == 'registeruser') || ($RegisterSupplier == 'registersupplier') ? 'active' : '' ?>">
    <a href="#" class="nav-link has-dropdown"><i class="far fa-user "></i> <span>Forms Register</span></a>
    <ul class="dropdown-menu">
        <li <?= ($RegisterUser == 'registeruser') ? 'class="active"' : '' ?>><a class="nav-link" href="<?= base_url(); ?>/admin/formsregister/datauser">Register User</a></li>
        <li <?= ($RegisterSupplier == 'registersupplier') ? 'class="active"' : '' ?>><a class="nav-link" href="<?= base_url(); ?>/admin/formsregister/datasupplier">Register Supplier</a>
        </li>
    </ul>
</li>
<li class="nav-item dropdown <?= ($MenuDataBarang == 'menudatabarang')  ? 'active' : '' ?>">
    <a href="#" class="nav-link has-dropdown"><i class="fas"><ion-icon name="gift-sharp"></ion-icon></i> <span>Data Barang</span></a>
    <ul class="dropdown-menu">
        <li <?= ($DataBarang == 'databarang') ? 'class="active"' : '' ?>><a class="nav-link" href="<?= base_url(); ?>/admin/databarang/datasatuanbarang">Satuan Barang</a></li>
        <li <?= ($DataBarangSupplier == 'databarangsupplier') ? 'class="active"' : '' ?>><a class="nav-link" href="<?= base_url(); ?>/admin/databarang/datasatuanbarangsupplier">Barang Supplier</a></li>
        <li <?= ($DataKategoriBarang == 'datakategoribarang') ? 'class="active"' : '' ?>><a class="nav-link" href="<?= base_url(); ?>/admin/databarang/datakategoribarang">Kategori Barang</a></li>
        <li <?= ($DataKategoriPaket == 'datakategoripaket') ? 'class="active"' : '' ?>><a class="nav-link" href="<?= base_url(); ?>/admin/databarang/datakategoripaket">Kategori Paket</a></li>
        <li <?= ($DataPaketBarang == 'datapaketbarang') ? 'class="active"' : '' ?>><a class="nav-link" href="<?= base_url(); ?>/admin/databarang/datapaketbarang">Paket Barang</a></li>
        <li <?= ($DataPackingBarang == 'datapackingbarang') ? 'class="active"' : '' ?>><a class="nav-link" href="<?= base_url(); ?>/admin/databarang/datapackagingbarang">Packaging Barang</a></li>
    </ul>
</li>
<li class="nav-item dropdown <?= ($MenuDataTransaksi == 'menudatatransaksi')  ? 'active' : '' ?>">
    <a href="#" class="nav-link has-dropdown"><i class="fas"><ion-icon name="receipt-sharp"></ion-icon></i> <span>Data Transaksi</span></a>
    <ul class="dropdown-menu">
        <li <?= ($DataPeriodeTransaksi == 'dataperiodetransaksi') ? 'class="active"' : '' ?>><a class="nav-link" href="<?= base_url(); ?>/admin/datatransaksi/dataperiodepembayaran">Periode Pembayaran</a></li>
        <li <?= ($DataTransaksi == 'datatransaksi') ? 'class="active"' : '' ?>><a class="nav-link" href="<?= base_url(); ?>/admin/datatransaksi/datatransaksi">Transaksi Pembayaran</a></li>
        <li <?= ($DataTransaksiCicilan == 'datatransaksicicilan') ? 'class="active"' : '' ?>><a class="nav-link" href="<?= base_url(); ?>/admin/datatransaksi/datacicilan">Transaksi Cicilan</a></li>
        <li <?= ($DataTransaksiLogCicilan == 'datatransaksilogcicilan') ? 'class="active"' : '' ?>><a class="nav-link" href="<?= base_url(); ?>/admin/datatransaksi/datalogcicilan">Transaksi Log Cicilan</a></li>
    </ul>
</li>