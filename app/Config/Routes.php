<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/login', 'LoginControllers::index');
$routes->get('/', 'LoginControllers::index');
$routes->match(['get', 'post'], 'login', 'LoginControllers::login', ["filter" => "noauth"]);
$routes->get('/select', 'DependentDropdownController::index');
$routes->post('AdminControllers/Kabupaten', 'AdminControllers::Kabupaten');
$routes->post('AdminControllers/Kecamatan', 'AdminControllers::Kecamatan');
$routes->post('AdminControllers/HargaPackaging', 'AdminControllers::HargaPackaging');
$routes->get('AdminControllers/SelectEditPaketBarang', 'AdminControllers::SelectEditPaketBarang');
$routes->post('AdminControllers/myFunction', 'AdminControllers::myFunction');
$routes->post('AdminControllers/PaketCicilan', 'AdminControllers::PaketCicilan');
$routes->post('AdminControllers/PaketLogCicilan', 'AdminControllers::PaketLogCicilan');
$routes->post('AdminControllers/TotalHarga', 'AdminControllers::TotalHarga');
//admin Routes
$routes->group("admin", ["filter" => "auth"], function ($routes) {
    //Dashboard
    $routes->get("dashboard", "AdminControllers::index");
    //Register User
    $routes->get('kabupaten', 'AdminControllers::kabupaten');
    $routes->get('formsregister/datauser', 'AdminControllers::listdatauser');
    $routes->get('formsregister/registeruser', 'AdminControllers::registeruser');
    $routes->post('formsregister/registeruser/process', 'AdminControllers::registeruserprocess');
    $routes->add('formsregister/datauser/(:segment)/edit', 'AdminControllers::edituser/$1');
    $routes->get('formsregister/datauser/(:segment)/delete', 'AdminControllers::deleteuser/$1');
    $routes->match(['get', 'post'], 'admincontrollers/importfileexceluser', 'AdminControllers::ImportFileExcelUser');
    $routes->get('admincontrollers/exportfileexceluser', 'AdminControllers::ExportDataExcelUser');
    //Register Supplier
    $routes->get('formsregister/datasupplier', 'AdminControllers::listdatasupplier');
    $routes->get('formsregister/registersupplier', 'AdminControllers::registersupplier');
    $routes->post('formsregister/registersupplier/process', 'AdminControllers::registersupplierprocess');
    $routes->add('formsregister/datasupplier/(:segment)/edit', 'AdminControllers::editsupplier/$1');
    $routes->get('formsregister/datasupplier/(:segment)/delete', 'AdminControllers::deletesupplier/$1');
    $routes->match(['get', 'post'], 'admincontrollers/importfileexcelsupplier', 'AdminControllers::ImportFileExcelSupplier');
    $routes->get('admincontrollers/exportfileexcelsupplier', 'AdminControllers::ExportDataExcelSupplier');
    //Register Data Kategori Barang
    $routes->get('databarang/datakategoribarang', 'AdminControllers::listdatakategoribarang');
    $routes->get('databarang/kategoribarang', 'AdminControllers::kategoribarang');
    $routes->post('databarang/kategoribarang/process', 'AdminControllers::kategoribarangprocess');
    $routes->add('databarang/datakategoribarang/(:segment)/edit', 'AdminControllers::editkategoribarang/$1');
    $routes->get('databarang/datakategoribarang/(:segment)/delete', 'AdminControllers::deletekategoribarang/$1');
    $routes->match(['get', 'post'], 'admincontrollers/importfileexcelkategoribarang', 'AdminControllers::ImportFileExcelKategoribarang');
    $routes->get('admincontrollers/exportfileexcelkategoribarang', 'AdminControllers::ExportDataExcelKategoribarang');
    //Register Data Kategori Barang
    $routes->get('databarang/datakategoripaket', 'AdminControllers::listdatakategoripaket');
    $routes->get('databarang/kategoripaket', 'AdminControllers::kategoripaket');
    $routes->post('databarang/kategoripaket/process', 'AdminControllers::kategoripaketprocess');
    $routes->add('databarang/datakategoripaket/(:segment)/edit', 'AdminControllers::editkategoripaket/$1');
    $routes->get('databarang/datakategoripaket/(:segment)/delete', 'AdminControllers::deletekategoripaket/$1');
    $routes->match(['get', 'post'], 'admincontrollers/importfileexcelkategoripaket', 'AdminControllers::ImportFileExcelkategoripaket');
    $routes->get('admincontrollers/exportfileexcelkategoripaket', 'AdminControllers::ExportDataExcelkategoripaket');
    //Register Data Barang
    $routes->get('databarang/datasatuanbarang', 'AdminControllers::listdatabarang');
    $routes->get('databarang/barang', 'AdminControllers::barang');
    $routes->post('databarang/barang/process', 'AdminControllers::barangprocess');
    $routes->add('databarang/barang/(:segment)/edit', 'AdminControllers::editbarang/$1');
    $routes->get('databarang/barang/(:segment)/delete', 'AdminControllers::deletebarang/$1');
    $routes->match(['get', 'post'], 'admincontrollers/importfileexcelbarang', 'AdminControllers::ImportFileExcelbarang');
    $routes->get('admincontrollers/exportfileexcelbarang', 'AdminControllers::ExportDataExcelbarang');
    //Register Data Barang Supplier
    $routes->get('databarang/datasatuanbarangsupplier', 'AdminControllers::listdatabarangsupplier');
    $routes->get('databarang/barangsupplier', 'AdminControllers::barangsupplier');
    $routes->post('databarang/barangsupplier/process', 'AdminControllers::barangsupplierprocess');
    $routes->add('databarang/barangsupplier/(:segment)/edit', 'AdminControllers::editbarangsupplier/$1');
    $routes->get('databarang/barangsupplier/(:segment)/delete', 'AdminControllers::deletebarangsupplier/$1');
    $routes->match(['get', 'post'], 'admincontrollers/importfileexcelbarangsupplier', 'AdminControllers::ImportFileExcelbarangsupplier');
    $routes->get('admincontrollers/exportfileexcelbarangsupplier', 'AdminControllers::ExportDataExcelbarangsupplier');
    //Register Data Packing Barang 
    $routes->get('databarang/datapackagingbarang', 'AdminControllers::listdatapackingbarang');
    $routes->get('databarang/packagingbarang', 'AdminControllers::packingbarang');
    $routes->post('databarang/packagingbarang/process', 'AdminControllers::packingbarangprocess');
    $routes->add('databarang/packagingbarang/(:segment)/edit', 'AdminControllers::editpackingbarang/$1');
    $routes->get('databarang/packagingbarang/(:segment)/delete', 'AdminControllers::deletepackingbarang/$1');
    $routes->match(['get', 'post'], 'admincontrollers/importfileexcelpackagingbarang', 'AdminControllers::ImportFileExcelpackingbarang');
    $routes->get('admincontrollers/exportfileexcelpackagingbarang', 'AdminControllers::ExportDataExcelpackingbarang');
    //Register Data Paket Barang 
    $routes->get('databarang/datapaketbarang', 'AdminControllers::listdatapaketbarang');
    $routes->get('databarang/paketbarang', 'AdminControllers::paketbarang');
    $routes->post('databarang/paketbarang/process', 'AdminControllers::paketbarangprocess');
    $routes->add('databarang/paketbarang/(:segment)/edit', 'AdminControllers::editpaketbarang/$1');
    $routes->get('databarang/paketbarang/(:segment)/delete', 'AdminControllers::deletepaketbarang/$1');
    $routes->match(['get', 'post'], 'admincontrollers/importfileexcelpaketbarang', 'AdminControllers::ImportFileExcelpaketbarang');
    $routes->get('admincontrollers/exportfileexcelpaketbarang', 'AdminControllers::ExportDataExcelpaketbarang');


    //Register Data Periode Pembayaran
    $routes->get('datatransaksi/dataperiodepembayaran', 'AdminControllers::listdataperiodepembayaran');
    $routes->get('datatransaksi/periodepembayaran', 'AdminControllers::periodepembayaran');
    $routes->post('datatransaksi/periodepembayaran/process', 'AdminControllers::periodepembayaranprocess');
    $routes->add('datatransaksi/periodepembayaran/(:segment)/edit', 'AdminControllers::editperiodepembayaran/$1');
    $routes->get('datatransaksi/periodepembayaran/(:segment)/delete', 'AdminControllers::deleteperiodepembayaran/$1');
    $routes->match(['get', 'post'], 'admincontrollers/importfileexcelperiodepembayaran', 'AdminControllers::ImportFileExcelperiodepembayaran');
    $routes->get('admincontrollers/exportfileexcelperiodepembayaran', 'AdminControllers::ExportDataExcelperiodepembayaran');
    //Register Data transaksi Pembayaran
    $routes->get('datatransaksi/datatransaksi', 'AdminControllers::listdatatransaksi');
    $routes->get('datatransaksi/transaksi', 'AdminControllers::transaksi');
    $routes->post('datatransaksi/transaksi/process', 'AdminControllers::transaksiprocess');
    $routes->add('datatransaksi/transaksi/(:segment)/edit', 'AdminControllers::edittransaksi/$1');
    $routes->add('datatransaksi/approvedtransaksi/(:segment)/approved', 'AdminControllers::editapprovedtransaksi/$1');
    $routes->add('datatransaksi/noapprovedtransaksi/(:segment)/noapproved', 'AdminControllers::editnoapprovedtransaksi/$1');
    $routes->get('datatransaksi/transaksi/(:segment)/delete', 'AdminControllers::deletetransaksi/$1');
    $routes->match(['get', 'post'], 'admincontrollers/importfileexceltransaksi', 'AdminControllers::ImportFileExceltransaksi');
    $routes->get('admincontrollers/exportfileexceltransaksi', 'AdminControllers::ExportDataExceltransaksi');
    //Register Data Cicilan Pembayaran
    $routes->get('datatransaksi/datacicilan', 'AdminControllers::listdatacicilan');
    $routes->get('datatransaksi/cicilan', 'AdminControllers::cicilan');
    $routes->post('datatransaksi/cicilan/process', 'AdminControllers::cicilanprocess');
    $routes->add('datatransaksi/cicilan/(:segment)/edit', 'AdminControllers::editcicilan/$1');
    $routes->get('datatransaksi/cicilan/(:segment)/delete', 'AdminControllers::deletecicilan/$1');
    $routes->match(['get', 'post'], 'admincontrollers/importfileexcelcicilan', 'AdminControllers::ImportFileExcelcicilan');
    $routes->get('admincontrollers/exportfileexcelcicilan', 'AdminControllers::ExportDataExcelcicilan');
    //Register Data Log Cicilan Pembayaran
    $routes->get('datatransaksi/datalogcicilan', 'AdminControllers::listdatalogcicilan');
    $routes->get('datatransaksi/logcicilan', 'AdminControllers::logcicilan');
    $routes->post('datatransaksi/logcicilan/process', 'AdminControllers::logcicilanprocess');
    $routes->add('datatransaksi/logcicilan/(:segment)/edit', 'AdminControllers::editlogcicilan/$1');
    $routes->get('datatransaksi/logcicilan/(:segment)/delete', 'AdminControllers::deletelogcicilan/$1');
    $routes->match(['get', 'post'], 'admincontrollers/importfileexcellogcicilan', 'AdminControllers::ImportFileExcellogcicilan');
    $routes->get('admincontrollers/exportfileexcellogcicilan', 'AdminControllers::ExportDataExcellogcicilan');
    //logout
    $routes->get('logout', 'LoginControllers::logout');
});

//owner Routes
$routes->group("owner", ["filter" => "auth"], function ($routes) {
    $routes->get("dashboard", "OwnerControllers::index");
    //Register User
    $routes->get('formsregister/registeruser', 'OwnerControllers::registeruser');
    $routes->post('formsregister/registeruser/process', 'OwnerControllers::registeruserprocess');
    //News CRUD
    $routes->get('BRP/', 'OwnerControllers::listbrp');
    $routes->add('BRP/create', 'OwnerControllers::create');
    $routes->add('BRP/(:segment)/edit', 'OwnerControllers::edit/$1');
    $routes->get('BRP/(:segment)/delete', 'OwnerControllers::delete/$1');
    $routes->match(['get', 'post'], 'OwnerControllers/ImportFileExcel', 'OwnerControllers::ImportFileExcel');
    $routes->get('logout', 'LoginControllers::logout');
});

//coordinator Routes
$routes->group("coordinator", ["filter" => "auth"], function ($routes) {
    $routes->get("dashboard", "CoordinatorControllers::index");
    //Register User
    $routes->get('formsregister/registeruser', 'CoordinatorControllers::registeruser');
    $routes->post('formsregister/registeruser/process', 'CoordinatorControllers::registeruserprocess');
    //News CRUD
    $routes->get('BRP/', 'CoordinatorControllers::listbrp');
    $routes->add('BRP/create', 'CoordinatorControllers::create');
    $routes->add('BRP/(:segment)/edit', 'CoordinatorControllers::edit/$1');
    $routes->get('BRP/(:segment)/delete', 'CoordinatorControllers::delete/$1');
    $routes->match(['get', 'post'], 'CoordinatorControllers/ImportFileExcel', 'CoordinatorControllers::ImportFileExcel');
    $routes->get('logout', 'LoginControllers::logout');
});

//anggota Routes
$routes->group("anggota", ["filter" => "auth"], function ($routes) {
    $routes->get("dashboard", "AnggotaControllers::index");
    //Register User
    $routes->get('FormsRegister/RegisterUser', 'RegisterControllers::index');
    $routes->post('FormsRegister/RegisterUser/Process', 'RegisterControllers::process');
    //News CRUD
    $routes->get('BRP/', 'AnggotaControllers::listbrp');
    $routes->add('BRP/create', 'AnggotaControllers::create');
    $routes->add('BRP/(:segment)/edit', 'AnggotaControllers::edit/$1');
    $routes->get('BRP/(:segment)/delete', 'AnggotaControllers::delete/$1');
    $routes->match(['get', 'post'], 'AnggotaControllers/ImportFileExcel', 'AnggotaControllers::ImportFileExcel');
    $routes->get('logout', 'LoginControllers::logout');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
