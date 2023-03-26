<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModels;
use App\Models\SupplierModels;
use App\Models\KategoriBarangModels;
use App\Models\KategoriPaketModels;
use App\Models\BarangModels;
use App\Models\BarangSupplierModels;
use App\Models\PackingBarangModels;
use App\Models\PeriodePembayaranModels;
use App\Models\PaketBarangModels;
use App\Models\TransaksiModels;
use App\Models\CicilanModels;
use App\Models\LogCicilanModels;
use App\Models\PengambilanPaketBarangModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\HTTP\IncomingRequest;

/**
 * @property IncomingRequest $request ,$post, $load
 */




class AdminControllers extends BaseController
{
    public function __construct()
    {
        if (session()->get('u_role') != "admin") {
            echo 'Access denied';
            exit;
        }
        // $this->load->library('select2');
    }
    public function Kabupaten()
    {
        $UsersModels = new UsersModels();
        $id_provinsi = $this->request->getPost('u_provinsi');
        $kab = $UsersModels->datakabupaten1($id_provinsi);
        echo '<option value="">----Pilih Kabupaten---- </option>';
        foreach ($kab as $value => $k) {
            echo "<option value=" . $k['id_kabupaten'] . ">" . $k['nama_kabupaten'] . "</option>";
        }
    }
    public function Kecamatan()
    {
        $UsersModels = new UsersModels();
        $id_kabupaten = $this->request->getPost('u_kota');
        $kab = $UsersModels->datakecamatan1($id_kabupaten);
        echo '<option value="">----Pilih Kecamatan---- </option>';
        foreach ($kab as $value => $k) {
            echo "<option value=" . $k['id_kecamatan'] . ">" . $k['nama_kecamatan'] . "</option>";
        }
    }
    public function PaketCicilan()
    {
        $cicilanModels = new CicilanModels();
        $paketbarang = new PaketBarangModels();
        $pengambilanpaketbarang = new PengambilanPaketBarangModels();
        $u_id = $this->request->getPost('u_id');
        $p_id = $this->request->getPost('p_id');
        $kab = $cicilanModels->paketcicilan($u_id);
        $cicilan = $cicilanModels->findAll();
        $pengambilanpaket = $pengambilanpaketbarang->get_pengambilan_paket_by_idi('pp_p_id', $p_id);
        $barangsupplier = $pengambilanpaketbarang->get_all_barang_supplier();
        $supplier = $pengambilanpaketbarang->get_all_supplier();
        $barang = $pengambilanpaketbarang->get_all_barang();
        echo '<option value="">--Pilih Paket Cicilan--</option>';
        foreach ($kab as $value => $k) {
            $tampil = '';
            foreach ($pengambilanpaket as $tb_pengambilan_paket) {
                if ($k['p_id'] == $tb_pengambilan_paket['pp_p_id']) {
                    foreach ($barangsupplier as $tb_sup_barang) {
                        foreach ($supplier as $tb_supplier) {
                            foreach ($barang as $tb_barang) {
                                $sb = $tb_pengambilan_paket['p_sb_id'];
                                if ($sb == $tb_sup_barang['sb_id']) {
                                    $si = $tb_sup_barang['s_id'];
                                    $bi = $tb_sup_barang['b_id'];
                                    if ($si == $tb_supplier['s_id'] && $bi == $tb_barang['b_id']) {
                                        $tampil .= $tb_supplier['s_nama'] . " - " . $tb_barang['b_nama'] . ", ";
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (!empty($tampil)) {
                echo "<option value=" . $k['t_id'] . ">" . rtrim($tampil, ", ") . " - Jumlah Paket :" . $k['t_qty'] . "</option>";
            }
        }
    }
    public function PaketLogCicilan()
    {
        $cicilanModels = new CicilanModels();
        $paketbarang = new PaketBarangModels();
        $transaksi = new TransaksiModels();
        $pengambilanpaketbarang = new PengambilanPaketBarangModels();
        $u_id = $this->request->getPost('u_id');
        $kab = $cicilanModels->get_cicilan($u_id);
        $cicilan = $cicilanModels->findAll();
        $pengambilanpaket = $pengambilanpaketbarang->findAll();
        $transaksipaket = $transaksi->get_transaksi_paket_by_idi('u_id', $u_id);
        $barangsupplier = $pengambilanpaketbarang->get_all_barang_supplier();
        $supplier = $pengambilanpaketbarang->get_all_supplier();
        $barang = $pengambilanpaketbarang->get_all_barang();
        echo '<option value="">--Pilih Paket Cicilan--</option>';
        foreach ($kab as $value => $k) {
            $tampil = '';
            foreach ($transaksipaket as $tb_transaksi) {
                foreach ($pengambilanpaket as $tb_pengambilan_paket) {
                    if ($k['p_id'] == $tb_pengambilan_paket['pp_p_id'] && $k['p_id'] == $tb_transaksi['p_id']) {
                        $qty = $tb_transaksi['t_qty'];
                        foreach ($barangsupplier as $tb_sup_barang) {
                            foreach ($supplier as $tb_supplier) {
                                foreach ($barang as $tb_barang) {
                                    $sb = $tb_pengambilan_paket['p_sb_id'];
                                    if ($sb == $tb_sup_barang['sb_id']) {
                                        $si = $tb_sup_barang['s_id'];
                                        $bi = $tb_sup_barang['b_id'];
                                        if ($si == $tb_supplier['s_id'] && $bi == $tb_barang['b_id']) {
                                            $tampil .= $tb_supplier['s_nama'] . " - " . $tb_barang['b_nama'] . ", ";
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (!empty($tampil)) {
                echo "<option value=" . $k['c_id'] . ">" . rtrim($tampil, ", ") . " - Jumlah Paket :" . $qty . "</option>";
            }
        }
    }
    public function TotalHarga()
    {
        $transaksi = new TransaksiModels();
        $p_id = $this->request->getPost('p_id');
        $Harga = $transaksi->HargaPaket($p_id);
        foreach ($Harga as $value => $H) {
            // echo "<option value=" . $H['id_kabupaten'] . ">" . $H['nama_kabupaten'] . "</option>";
            echo "<input type=" . "text" . " name=" . "p_hargapaket" . " class=" . "form-control " . " placeholder=" . "Masukan Cashback Paket Barang" . " required  hidden value=" . $H['p_hargaJual'] . ">";
            // echo "value=" . $H['pa_harga'] . ">";
        }
    }
    public function index()
    {
        $UsersModels = new UsersModels();
        $menu = [
            'AdminDashboard' => 'dashboard',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataKategoriPaket' => '',
            'DataBarang' => '',
            'MenuDataBarang' => '',
            'DataBarangSupplier' => '',
            'DataPackingBarang' => '',
            'DataPeriodeTransaksi' => '',
            'MenuDataTransaksi' => '',
            'DataPaketBarang' => '',
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => '',
            // 'countdatauser' => $UsersModels->countAllResults(),
        ];
        return view('admin/dashboard', $menu);
    }
    //user
    public function registeruser()
    {
        $UsersModels = new UsersModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => 'registeruser',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'MenuDataBarang' => '',
            'DataBarangSupplier' => '',
            'DataPackingBarang' => '',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataKategoriPaket' => '',
            'DataPaketBarang' => '',
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'provinsi' => $UsersModels->dataprovinsi(),
            'kabupaten' => $UsersModels->datakabupaten(),
            'kecamatan' => $UsersModels->datakecamatan(),
            'DataTransaksiLogCicilan' => '',

        ];
        helper(['form', 'url']);
        $data['nameuser'] = $UsersModels->getuserreferensiadmin();
        return view('admin/registeruser', $data);
        // return view('register');
    }
    public function registeruserprocess()
    {
        if (!$this->validate([
            'u_username' => [
                'rules' => 'required|min_length[4]|max_length[20]|is_unique[tb_user.u_username]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 20 Karakter',
                    'is_unique' => 'Username sudah digunakan sebelumnya'
                ]
            ],
            'u_password' => [
                'rules' => 'required|min_length[4]|max_length[50]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 50 Karakter',
                ]
            ],
            'password-confirm' => [
                'rules' => 'matches[u_password]',
                'errors' => [
                    'matches' => 'Konfirmasi Password tidak sesuai dengan password',
                ]
            ],
            'u_fullname' => [
                'rules' => 'required|min_length[4]|max_length[100]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 100 Karakter',
                ]
            ],
            'u_role' => [
                'rules' => 'required|min_length[1]|max_length[100]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                ]
            ],
            'u_referensi' => [
                'rules' => 'required|min_length[1]|max_length[100]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                ]
            ],
            'u_email' => [
                'rules' => 'required|min_length[4]|max_length[100]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 100 Karakter',
                ]
            ],
            'u_create_at' => [
                'rules' => 'required|min_length[4]|max_length[100]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 100 Karakter',
                ]
            ],
            'u_nik' => [
                'rules' => 'required|min_length[4]|max_length[100]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 100 Karakter',
                ]
            ],
            'u_nama' => [
                'rules' => 'required|min_length[4]|max_length[100]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 100 Karakter',
                ]
            ],
            'u_tempat_lahir' => [
                'rules' => 'required|min_length[4]|max_length[100]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 100 Karakter',
                ]
            ],
            'u_tanggal_lahir' => [
                'rules' => 'required|min_length[4]|max_length[100]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 100 Karakter',
                ]
            ],
            'u_jenis_kelamin' => [
                'rules' => 'required|min_length[4]|max_length[100]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 100 Karakter',
                ]
            ],
            'u_provinsi' => [
                'rules' => 'required|min_length[1]|max_length[100]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 100 Karakter',
                ]
            ],
            'u_kelurahan' => [
                'rules' => 'required|min_length[4]|max_length[100]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 100 Karakter',
                ]
            ],
            'u_kecamatan' => [
                'rules' => 'required|min_length[4]|max_length[100]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 100 Karakter',
                ]
            ],
            'u_kodepos' => [
                'rules' => 'required|min_length[4]|max_length[100]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 100 Karakter',
                ]
            ],
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }
        $users = new UsersModels();
        $users->insert([
            'u_username' => $this->request->getVar('u_username'),
            'u_password' => password_hash($this->request->getVar('u_password'), PASSWORD_BCRYPT),
            'u_fullname' => $this->request->getVar('u_fullname'),
            'u_role' => $this->request->getVar('u_role'),
            'u_referensi' => $this->request->getVar('u_referensi'),
            'u_email' => $this->request->getVar('u_email'),
            'u_create_at' => $this->request->getVar('u_create_at'),
            'u_nik' => $this->request->getVar('u_nik'),
            'u_nama' => $this->request->getVar('u_nama'),
            'u_tempat_lahir' => $this->request->getVar('u_tempat_lahir'),
            'u_tanggal_lahir' => $this->request->getVar('u_tanggal_lahir'),
            'u_jenis_kelamin' => $this->request->getVar('u_jenis_kelamin'),
            'u_provinsi' => $this->request->getVar('u_provinsi'),
            'u_kota' => $this->request->getVar('u_kota'),
            'u_kelurahan' => $this->request->getVar('u_kelurahan'),
            'u_kecamatan' => $this->request->getVar('u_kecamatan'),
            'u_kodepos' => $this->request->getVar('u_kodepos')
        ]);
        session()->setFlashdata('success', 'Data Berhasil Disimpan!');
        return redirect()->to('/admin/formsregister/registeruser');
    }
    public function listdatauser()
    {
        $user = new UsersModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => 'registeruser',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => '',
            'DataPackingBarang' => '',
            'MenuDataTransaksi' => '',
            'DataKategoriPaket' => '',
            'DataPeriodeTransaksi' => '',
            'DataPaketBarang' => '',
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'datauser' => $user->datauser(),
            'provinsi' => $user->dataprovinsi(),
            'kabupaten' => $user->datakabupaten(),
            'kecamatan' => $user->datakecamatan(),
            'DataTransaksiLogCicilan' => '',

        ];
        $data['tb_user'] = $user->findAll();
        echo view('admin/datauser', $data);
        // helper(['form', 'url']);
        // $UsersModels = new UsersModels();
        // $dataa['nameuser'] = $UsersModels->getuserreferensiadmin();
        // return view('admin/datauser', $dataa);
        //berdasarkan login
        // $user = new UsersModels();
        // $data['tb_user'] = $user->where('u_referensi', session('u_id'))->findAll();
        // echo view('admin/datauser', $data);
    }
    public function edituser($u_id)
    {
        $UsersModels = new UsersModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => 'registeruser',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => '',
            'DataPackingBarang' => '',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataKategoriPaket' => '',
            'DataPaketBarang' => '',
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'provinsi' => $UsersModels->dataprovinsi(),
            'kabupaten' => $UsersModels->datakabupaten(),
            'kecamatan' => $UsersModels->datakecamatan(),
            'datauser' => $UsersModels->datauser(),
            'DataTransaksiLogCicilan' => '',

        ];
        // ambil artikel yang akan diedit

        $data['tb_user'] = $UsersModels->where('u_id', $u_id)->first();

        // lakukan validasi data artikel
        $validation = \Config\Services::validation();
        $validation->setRules([
            'u_username' => 'required',
            'u_password' => 'required',
            'password-confirm' => 'required',
            'u_fullname' => 'required',
            'u_role' => 'required',
            'u_referensi' => 'required',
            'u_email' => 'required',
            'u_create_at' => 'required',
            'u_nik' => 'required',
            'u_nama' => 'required',
            'u_tempat_lahir' => 'required',
            'u_tanggal_lahir' => 'required',
            'u_jenis_kelamin' => 'required',
            'u_provinsi' => 'required',
            'u_kota' => 'required',
            'u_kelurahan' => 'required',
            'u_kecamatan' => 'required',
            'u_kodepos' => 'required',
        ]);
        $isDataValid = $validation->withRequest($this->request)->run();
        // jika data vlid, maka simpan ke database
        if ($isDataValid) {
            $UsersModels->update($u_id, [
                'u_username' => $this->request->getVar('u_username'),
                'u_password' => password_hash($this->request->getVar('u_password'), PASSWORD_BCRYPT),
                'u_fullname' => $this->request->getVar('u_fullname'),
                'u_role' => $this->request->getVar('u_role'),
                'u_referensi' => $this->request->getVar('u_referensi'),
                'u_email' => $this->request->getVar('u_email'),
                'u_create_at' => $this->request->getVar('u_create_at'),
                'u_nik' => $this->request->getVar('u_nik'),
                'u_nama' => $this->request->getVar('u_nama'),
                'u_tempat_lahir' => $this->request->getVar('u_tempat_lahir'),
                'u_tanggal_lahir' => $this->request->getVar('u_tanggal_lahir'),
                'u_jenis_kelamin' => $this->request->getVar('u_jenis_kelamin'),
                'u_provinsi' => $this->request->getVar('u_provinsi'),
                'u_kota' => $this->request->getVar('u_kota'),
                'u_kelurahan' => $this->request->getVar('u_kelurahan'),
                'u_kecamatan' => $this->request->getVar('u_kecamatan'),
                'u_kodepos' => $this->request->getVar('u_kodepos')
            ]);
            session()->setFlashdata('success', 'Data Berhasil Di Edit!');
            return redirect('admin/formsregister/datauser');
        }

        // tampilkan form edit
        // helper(['form', 'url']);
        // $UsersModels = new UsersModels();
        // $data['tb_user'] = $UsersModels->getuserreferensiadmin();
        echo view('admin/registeredituser', $data);
    }
    public function deleteuser($u_id)
    {
        $user = new UsersModels();
        $user->delete($u_id);
        session()->setFlashdata('success', 'Data Berhasil Di Hapus!');
        return redirect('admin/formsregister/datauser');
    }
    public function ImportFileExcelUser()
    {
        $user = new UsersModels();
        $file = $this->request->getFile('file');
        $extension = $file->getClientExtension();
        if ($extension == 'xlsx' || $extension == 'xls') {
            if ($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($file);
            $users = $spreadsheet->getActiveSheet()->toArray();
            foreach ($users as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                $data = [
                    'u_id' => $value[0],
                    'u_username' => $value[1],
                    'u_password' => password_hash($value[2], PASSWORD_BCRYPT),
                    'u_fullname' => $value[3],
                    'u_role' => $value[4],
                    'u_referensi' => $value[5],
                    'u_email' => $value[6],
                    'u_create_at' => $value[7],
                    'u_nik' => $value[8],
                    'u_nama' => $value[9],
                    'u_tempat_lahir' => $value[10],
                    'u_tanggal_lahir' => $value[11],
                    'u_jenis_kelamin' => $value[12],
                    'u_provinsi' => $value[13],
                    'u_kota' => $value[14],
                    'u_kelurahan' => $value[15],
                    'u_kecamatan' => $value[16],
                    'u_kodepos' => $value[17],

                ];
                $user->insert($data);
            }
            session()->setFlashdata('success', 'Data Berhasil Diimport!');
            return redirect('admin/formsregister/datauser');
        } else {
            return redirect()->back()->with('message', 'Format File Tidak Sesuai! | Extension file harus .xls atau .xlsx');
        }
    }
    public function ExportDataExcelUser()
    {
        $user = new UsersModels();
        $datauser = $user->findAll();
        $spreadsheet = new Spreadsheet();
        $colomheader = $spreadsheet->getActiveSheet();
        $colomheader->setCellValue('A1', 'No');
        $colomheader->setCellValue('B1', 'Username');
        $colomheader->setCellValue('C1', 'Password');
        $colomheader->setCellValue('D1', 'Full Name');
        $colomheader->setCellValue('E1', 'Role Akses');
        $colomheader->setCellValue('F1', 'Upline');
        $colomheader->setCellValue('G1', 'Email');
        $colomheader->setCellValue('H1', 'Data Dibuat');
        $colomheader->setCellValue('I1', 'NIK KTP');
        $colomheader->setCellValue('J1', 'Nama Lengkap KTP');
        $colomheader->setCellValue('K1', 'Tempat  Lahir');
        $colomheader->setCellValue('L1', 'Tanggal Lahir');
        $colomheader->setCellValue('M1', 'Jenis Kelamin');
        $colomheader->setCellValue('N1', 'Provinsi');
        $colomheader->setCellValue('O1', 'Kota');
        $colomheader->setCellValue('P1', 'Kelurahan');
        $colomheader->setCellValue('Q1', 'Kecamatan');
        $colomheader->setCellValue('R1', 'Kode Pos');

        $colomdata = 2;
        foreach ($datauser as $setuser) {
            $colomheader->setCellValue('A' . $colomdata, ($colomdata - 1));
            $colomheader->setCellValue('B' . $colomdata, $setuser['u_username']);
            $colomheader->setCellValue('C' . $colomdata, $setuser['u_password']);
            $colomheader->setCellValue('D' . $colomdata, $setuser['u_fullname']);
            $colomheader->setCellValue('E' . $colomdata, $setuser['u_role']);
            $colomheader->setCellValue('F' . $colomdata, $setuser['u_referensi']);
            $colomheader->setCellValue('G' . $colomdata, $setuser['u_email']);
            $colomheader->setCellValue('H' . $colomdata, $setuser['u_create_at']);
            $colomheader->setCellValue('I' . $colomdata, $setuser['u_nik']);
            $colomheader->setCellValue('J' . $colomdata, $setuser['u_nama']);
            $colomheader->setCellValue('K' . $colomdata, $setuser['u_tempat_lahir']);
            $colomheader->setCellValue('L' . $colomdata, $setuser['u_tanggal_lahir']);
            $colomheader->setCellValue('M' . $colomdata, $setuser['u_jenis_kelamin']);
            $colomheader->setCellValue('N' . $colomdata, $setuser['u_provinsi']);
            $colomheader->setCellValue('O' . $colomdata, $setuser['u_kota']);
            $colomheader->setCellValue('P' . $colomdata, $setuser['u_kelurahan']);
            $colomheader->setCellValue('Q' . $colomdata, $setuser['u_kecamatan']);
            $colomheader->setCellValue('R' . $colomdata, $setuser['u_kodepos']);
            $colomdata++;
        }
        $colomheader->getStyle('A1:R1')->getFont()->setBold(true);
        $colomheader->getStyle('A1:R1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $colomheader->getStyle('A1:R' . ($colomdata - 1))->applyFromArray($styleArray);

        $colomheader->getColumnDimension('A')->setAutoSize(true);
        $colomheader->getColumnDimension('B')->setAutoSize(true);
        $colomheader->getColumnDimension('C')->setAutoSize(true);
        $colomheader->getColumnDimension('D')->setAutoSize(true);
        $colomheader->getColumnDimension('E')->setAutoSize(true);
        $colomheader->getColumnDimension('F')->setAutoSize(true);
        $colomheader->getColumnDimension('G')->setAutoSize(true);
        $colomheader->getColumnDimension('H')->setAutoSize(true);
        $colomheader->getColumnDimension('I')->setAutoSize(true);
        $colomheader->getColumnDimension('J')->setAutoSize(true);
        $colomheader->getColumnDimension('K')->setAutoSize(true);
        $colomheader->getColumnDimension('L')->setAutoSize(true);
        $colomheader->getColumnDimension('M')->setAutoSize(true);
        $colomheader->getColumnDimension('N')->setAutoSize(true);
        $colomheader->getColumnDimension('O')->setAutoSize(true);
        $colomheader->getColumnDimension('P')->setAutoSize(true);
        $colomheader->getColumnDimension('Q')->setAutoSize(true);
        $colomheader->getColumnDimension('R')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheet1.sheet');
        header('Content-Disposition: attachment;filename=Export-Data-User.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    //supplier
    public function registersupplier()
    {
        $SupplierModels = new SupplierModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => 'registersupplier',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => '',
            'DataPackingBarang' => '',
            'DataKategoriPaket' => '',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataPaketBarang' => '',
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => '',

        ];
        return view('admin/registersupplier', $data);
        // return view('register');
    }
    public function registersupplierprocess()
    {
        if (!$this->validate([
            's_nama' => [
                'rules' => 'required|min_length[4]|max_length[50]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 50 Karakter',
                ]
            ],
            's_contact' => [
                'rules' => 'required|min_length[4]|max_length[15]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 15 Karakter',
                ]
            ],
            's_alamat' => 'required',
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }
        $supplier = new SupplierModels();
        $supplier->insert([
            's_nama' => $this->request->getVar('s_nama'),
            's_alamat' => $this->request->getVar('s_alamat'),
            's_contact' => $this->request->getVar('s_contact'),
            'u_id' => session()->get('u_id')
        ]);
        session()->setFlashdata('success', 'Data Berhasil Disimpan!');
        return redirect()->to('/admin/formsregister/registersupplier');
    }
    public function listdatasupplier()
    {
        $supplier = new SupplierModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => 'registersupplier',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => '',
            'DataPackingBarang' => '',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataPaketBarang' => '',
            'DataKategoriPaket' => '',
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => '',
        ];
        $data['tb_supplier'] = $supplier->findAll();
        echo view('admin/datasupplier', $data);
        //berdasarkan login
        // $user = new UsersModels();
        // $data['tb_user'] = $user->where('u_referensi', session('u_id'))->findAll();
        // echo view('admin/datauser', $data);
    }
    public function editsupplier($s_id)
    {
        $supplier = new SupplierModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => 'registersupplier',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => '',
            'DataPackingBarang' => '',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataKategoriPaket' => '',
            'DataPaketBarang' => '',
            'DataTransaksi' => '',
            'DataTransaksiLogCicilan' => '',
        ];
        // ambil artikel yang akan diedit

        $data['tb_supplier'] = $supplier->where('s_id', $s_id)->first();

        // lakukan validasi data artikel
        $validation = \Config\Services::validation();
        $validation->setRules([
            's_nama' => [
                'rules' => 'required|min_length[4]|max_length[50]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 50 Karakter',
                ]
            ],
            's_contact' => [
                'rules' => 'required|min_length[4]|max_length[15]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 15 Karakter',
                ]
            ],
            's_alamat' => 'required',
        ]);
        $isDataValid = $validation->withRequest($this->request)->run();
        // jika data vlid, maka simpan ke database
        if ($isDataValid) {
            $supplier->update($s_id, [
                's_nama' => $this->request->getVar('s_nama'),
                's_alamat' => $this->request->getVar('s_alamat'),
                's_contact' => $this->request->getVar('s_contact'),
                'u_id' => session()->get('u_id')
            ]);
            session()->setFlashdata('success', 'Data Berhasil Di Edit!');
            return redirect('admin/formsregister/datasupplier');
        }
        echo view('admin/registereditsupplier', $data);
    }
    public function deletesupplier($s_id)
    {
        $supplier = new SupplierModels();
        $supplier->delete($s_id);
        session()->setFlashdata('success', 'Data Berhasil Di Hapus!');
        return redirect('admin/formsregister/datasupplier');
    }
    public function ImportFileExcelsupplier()
    {
        $supplier = new SupplierModels();
        $file = $this->request->getFile('file');
        $extension = $file->getClientExtension();
        if ($extension == 'xlsx' || $extension == 'xls') {
            if ($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($file);
            $suppliers = $spreadsheet->getActiveSheet()->toArray();
            foreach ($suppliers as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                $data = [
                    's_id' => $value[0],
                    's_nama' => $value[1],
                    's_alamat' => $value[2],
                    's_contact' => $value[3],
                    'u_id' => session()->get('u_id'),

                ];
                $supplier->insert($data);
            }
            session()->setFlashdata('success', 'Data Berhasil Diimport!');
            return redirect('admin/formsregister/datasupplier');
        } else {
            return redirect()->back()->with('message', 'Format File Tidak Sesuai! | Extension file harus .xls atau .xlsx');
        }
    }
    public function ExportDataExcelsupplier()
    {
        $supplier = new SupplierModels();
        $datasupplier = $supplier->findAll();
        $spreadsheet = new Spreadsheet();
        $colomheader = $spreadsheet->getActiveSheet();
        $colomheader->setCellValue('A1', 'No');
        $colomheader->setCellValue('B1', 'Nama Supplier');
        $colomheader->setCellValue('C1', 'Alamat Supplier');
        $colomheader->setCellValue('D1', 'Contact Supplier');

        $colomdata = 2;
        foreach ($datasupplier as $setsupplier) {
            $colomheader->setCellValue('A' . $colomdata, ($colomdata - 1));
            $colomheader->setCellValue('B' . $colomdata, $setsupplier['s_nama']);
            $colomheader->setCellValue('C' . $colomdata, $setsupplier['s_alamat']);
            $colomheader->setCellValue('D' . $colomdata, $setsupplier['s_contact']);
            $colomdata++;
        }
        $colomheader->getStyle('A1:D1')->getFont()->setBold(true);
        $colomheader->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $colomheader->getStyle('A1:D' . ($colomdata - 1))->applyFromArray($styleArray);

        $colomheader->getColumnDimension('A')->setAutoSize(true);
        $colomheader->getColumnDimension('B')->setAutoSize(true);
        $colomheader->getColumnDimension('C')->setAutoSize(true);
        $colomheader->getColumnDimension('D')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheet1.sheet');
        header('Content-Disposition: attachment;filename=Export-Data-Supplier.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    //Data Kategori barang
    public function kategoribarang()
    {
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => 'datakategoribarang',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => 'menudatabarang',
            'DataPackingBarang' => '',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataKategoriPaket' => '',
            'DataPaketBarang' => '',
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => '',
        ];
        return view('admin/kategoribarang', $data);
        // return view('register');
    }
    public function kategoribarangprocess()
    {
        if (!$this->validate([
            'k_nama' => [
                'rules' => 'required|min_length[4]|max_length[50]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 50 Karakter',
                ]
            ],
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }
        $kategoribarang = new KategoriBarangModels();
        $kategoribarang->insert([
            'k_nama' => $this->request->getVar('k_nama'),
            'u_id' => session()->get('u_id')
        ]);
        session()->setFlashdata('success', 'Data Berhasil Disimpan!');
        return redirect()->to('/admin/databarang/kategoribarang');
    }
    public function listdatakategoribarang()
    {
        $kategoribarang = new KategoriBarangModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => 'datakategoribarang',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => 'menudatabarang',
            'DataPackingBarang' => '',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataKategoriPaket' => '',
            'DataTransaksi' => '',
            'DataPaketBarang' => '',
            'DataTransaksiLogCicilan' => '',
        ];
        $data['tb_kategori'] = $kategoribarang->findAll();
        echo view('admin/datakategoribarang', $data);
        //berdasarkan login
        // $user = new UsersModels();
        // $data['tb_user'] = $user->where('u_referensi', session('u_id'))->findAll();
        // echo view('admin/datauser', $data);
    }
    public function editkategoribarang($k_id)
    {
        $kategoribarang = new KategoriBarangModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => 'datakategoribarang',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => 'menudatabarang',
            'DataPackingBarang' => '',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataPaketBarang' => '',
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataKategoriPaket' => '',
            'DataTransaksiLogCicilan' => '',
        ];
        // ambil artikel yang akan diedit

        $data['tb_kategori'] = $kategoribarang->where('k_id', $k_id)->first();

        // lakukan validasi data artikel
        $validation = \Config\Services::validation();
        $validation->setRules([
            'k_nama' => [
                'rules' => 'required|min_length[4]|max_length[50]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 50 Karakter',
                ]
            ],
        ]);
        $isDataValid = $validation->withRequest($this->request)->run();
        // jika data vlid, maka simpan ke database
        if ($isDataValid) {
            $kategoribarang->update($k_id, [
                'k_nama' => $this->request->getVar('k_nama'),
                'u_id' => session()->get('u_id')
            ]);
            session()->setFlashdata('success', 'Data Berhasil Di Edit!');
            return redirect('admin/databarang/datakategoribarang');
        }
        echo view('admin/kategoribarangedit', $data);
    }
    public function deletekategoribarang($k_id)
    {
        $kategoribarang = new KategoriBarangModels();
        $kategoribarang->delete($k_id);
        session()->setFlashdata('success', 'Data Berhasil Di Hapus!');
        return redirect('admin/databarang/datakategoribarang');
    }
    public function ImportFileExcelkategoribarang()
    {
        $kategoribarang = new KategoriBarangModels();
        $file = $this->request->getFile('file');
        $extension = $file->getClientExtension();
        if ($extension == 'xlsx' || $extension == 'xls') {
            if ($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($file);
            $kategori = $spreadsheet->getActiveSheet()->toArray();
            foreach ($kategori as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                $data = [
                    'k_id' => $value[0],
                    'k_nama' => $value[1],
                    'u_id' => session()->get('u_id'),

                ];
                $kategoribarang->insert($data);
            }
            session()->setFlashdata('success', 'Data Berhasil Diimport!');
            return redirect('admin/databarang/datakategoribarang');
        } else {
            return redirect()->back()->with('message', 'Format File Tidak Sesuai! | Extension file harus .xls atau .xlsx');
        }
    }
    public function ExportDataExcelkategoribarang()
    {
        $kategoribarang = new KategoriBarangModels();
        $datakategori = $kategoribarang->findAll();
        $spreadsheet = new Spreadsheet();
        $colomheader = $spreadsheet->getActiveSheet();
        $colomheader->setCellValue('A1', 'No');
        $colomheader->setCellValue('B1', 'Nama Kategori Barang');

        $colomdata = 2;
        foreach ($datakategori as $setkategori) {
            $colomheader->setCellValue('A' . $colomdata, ($colomdata - 1));
            $colomheader->setCellValue('B' . $colomdata, $setkategori['k_nama']);
            $colomdata++;
        }
        $colomheader->getStyle('A1:B1')->getFont()->setBold(true);
        $colomheader->getStyle('A1:B1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $colomheader->getStyle('A1:B' . ($colomdata - 1))->applyFromArray($styleArray);

        $colomheader->getColumnDimension('A')->setAutoSize(true);
        $colomheader->getColumnDimension('B')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheet1.sheet');
        header('Content-Disposition: attachment;filename=Export-Data-KategoriBarang.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    //Data Kategori barang
    public function kategoripaket()
    {
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataKategoriPaket' => 'datakategoripaket',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => 'menudatabarang',
            'DataPackingBarang' => '',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataPaketBarang' => '',
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => '',
        ];
        return view('admin/kategoripaket', $data);
        // return view('register');
    }
    public function kategoripaketprocess()
    {
        if (!$this->validate([
            'kp_nama' => [
                'rules' => 'required|min_length[4]|max_length[50]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 50 Karakter',
                ]
            ],
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }
        $kategoripaket = new KategoriPaketModels();
        $kategoripaket->insert([
            'kp_nama' => $this->request->getVar('kp_nama')
        ]);
        session()->setFlashdata('success', 'Data Berhasil Disimpan!');
        return redirect()->to('/admin/databarang/kategoripaket');
    }
    public function listdatakategoripaket()
    {
        $kategoripaket = new KategoriPaketModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'DataKategoriPaket' => 'datakategoripaket',
            'MenuDataBarang' => 'menudatabarang',
            'DataPackingBarang' => '',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataTransaksi' => '',
            'DataPaketBarang' => '',
            'DataTransaksiLogCicilan' => '',
        ];
        $data['tb_kategori_paket'] = $kategoripaket->findAll();
        echo view('admin/datakategoripaket', $data);
        //berdasarkan login
        // $user = new UsersModels();
        // $data['tb_user'] = $user->where('u_referensi', session('u_id'))->findAll();
        // echo view('admin/datauser', $data);
    }
    public function editkategoripaket($kp_id)
    {
        $kategoripaket = new KategoriPaketModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataKategoriPaket' => 'datakategoripaket',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => 'menudatabarang',
            'DataPackingBarang' => '',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataPaketBarang' => '',
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => '',
        ];
        // ambil artikel yang akan diedit

        $data['tb_kategori_paket'] = $kategoripaket->where('kp_id', $kp_id)->first();

        // lakukan validasi data artikel
        $validation = \Config\Services::validation();
        $validation->setRules([
            'kp_nama' => [
                'rules' => 'required|min_length[4]|max_length[50]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 50 Karakter',
                ]
            ],
        ]);
        $isDataValid = $validation->withRequest($this->request)->run();
        // jika data vlid, maka simpan ke database
        if ($isDataValid) {
            $kategoripaket->update($kp_id, [
                'kp_nama' => $this->request->getVar('kp_nama')
            ]);
            session()->setFlashdata('success', 'Data Berhasil Di Edit!');
            return redirect('admin/databarang/datakategoripaket');
        }
        echo view('admin/kategoripaketedit', $data);
    }
    public function deletekategoripaket($kp_id)
    {
        $kategoripaket = new KategoriPaketModels();
        $kategoripaket->delete($kp_id);
        session()->setFlashdata('success', 'Data Berhasil Di Hapus!');
        return redirect('admin/databarang/datakategoripaket');
    }
    public function ImportFileExcelkategoripaket()
    {
        $kategoripaket = new KategoriBarangModels();
        $file = $this->request->getFile('file');
        $extension = $file->getClientExtension();
        if ($extension == 'xlsx' || $extension == 'xls') {
            if ($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($file);
            $kategori = $spreadsheet->getActiveSheet()->toArray();
            foreach ($kategori as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                $data = [
                    'k_id' => $value[0],
                    'k_nama' => $value[1],
                    'u_id' => session()->get('u_id'),

                ];
                $kategoripaket->insert($data);
            }
            session()->setFlashdata('success', 'Data Berhasil Diimport!');
            return redirect('admin/databarang/datakategoripaket');
        } else {
            return redirect()->back()->with('message', 'Format File Tidak Sesuai! | Extension file harus .xls atau .xlsx');
        }
    }
    public function ExportDataExcelkategoripaket()
    {
        $kategoripaket = new KategoriBarangModels();
        $datakategori = $kategoripaket->findAll();
        $spreadsheet = new Spreadsheet();
        $colomheader = $spreadsheet->getActiveSheet();
        $colomheader->setCellValue('A1', 'No');
        $colomheader->setCellValue('B1', 'Nama Kategori Barang');

        $colomdata = 2;
        foreach ($datakategori as $setkategori) {
            $colomheader->setCellValue('A' . $colomdata, ($colomdata - 1));
            $colomheader->setCellValue('B' . $colomdata, $setkategori['k_nama']);
            $colomdata++;
        }
        $colomheader->getStyle('A1:B1')->getFont()->setBold(true);
        $colomheader->getStyle('A1:B1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $colomheader->getStyle('A1:B' . ($colomdata - 1))->applyFromArray($styleArray);

        $colomheader->getColumnDimension('A')->setAutoSize(true);
        $colomheader->getColumnDimension('B')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheet1.sheet');
        header('Content-Disposition: attachment;filename=Export-Data-KategoriPaket.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    //Data Barang
    public function barang()
    {
        $barang = new BarangModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => 'databarang',
            'DataBarangSupplier' => '',
            'DataPackingBarang' => '',
            'DataPeriodeTransaksi' => '',
            'kategoribarang' => $barang->datakategoribarang(),
            'MenuDataBarang' => 'menudatabarang',
            'MenuDataTransaksi' => '',
            'DataPaketBarang' => '',
            'DataKategoriPaket' => '',
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => '',

        ];
        return view('admin/barang', $data);
        // return view('register');
    }
    public function barangprocess()
    {
        if (!$this->validate([
            'b_nama' => [
                'rules' => 'required|min_length[4]|max_length[50]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 50 Karakter',
                ]
            ],
            'k_id' => 'required',
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }
        $barang = new BarangModels();
        $barang->insert([
            'b_nama' => $this->request->getVar('b_nama'),
            'k_id' => $this->request->getVar('k_id'),
            'u_id' => session()->get('u_id')
        ]);
        session()->setFlashdata('success', 'Data Berhasil Disimpan!');
        return redirect()->to('/admin/databarang/barang');
    }
    public function listdatabarang()
    {
        $barang = new BarangModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => 'databarang',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => 'menudatabarang',
            'DataPackingBarang' => '',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataPaketBarang' => '',
            'DataTransaksiCicilan' => '',
            'DataKategoriPaket' => '',
            'DataTransaksi' => '',
            'kategoribarang' => $barang->datakategoribarang(),
            'DataTransaksiLogCicilan' => '',

        ];
        $data['tb_barang'] = $barang->findAll();
        echo view('admin/databarang', $data);
        //berdasarkan login
        // $user = new UsersModels();
        // $data['tb_user'] = $user->where('u_referensi', session('u_id'))->findAll();
        // echo view('admin/datauser', $data);
    }
    public function editbarang($b_id)
    {
        $barang = new BarangModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => 'databarang',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => 'menudatabarang',
            'MenuDataTransaksi' => '',
            'DataPackingBarang' => '',
            'DataPeriodeTransaksi' => '',
            'DataKategoriPaket' => '',
            'DataPaketBarang' => '',
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'kategoribarang' => $barang->datakategoribarang(),
            'DataTransaksiLogCicilan' => '',
        ];
        // ambil artikel yang akan diedit

        $data['tb_barang'] = $barang->where('b_id', $b_id)->first();

        // lakukan validasi data artikel
        $validation = \Config\Services::validation();
        $validation->setRules([
            'b_nama' => [
                'rules' => 'required|min_length[4]|max_length[50]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 50 Karakter',
                ]
            ],
            'k_id' => 'required',
        ]);
        $isDataValid = $validation->withRequest($this->request)->run();
        // jika data vlid, maka simpan ke database
        if ($isDataValid) {
            $barang->update($b_id, [
                'b_nama' => $this->request->getVar('b_nama'),
                'k_id' => $this->request->getVar('k_id'),
                'u_id' => session()->get('u_id')
            ]);
            session()->setFlashdata('success', 'Data Berhasil Di Edit!');
            return redirect('admin/databarang/datasatuanbarang');
        }
        echo view('admin/barangedit', $data);
    }
    public function deletebarang($b_id)
    {
        $barang = new BarangModels();
        $barang->delete($b_id);
        session()->setFlashdata('success', 'Data Berhasil Di Hapus!');
        return redirect('admin/databarang/datasatuanbarang');
    }
    public function ImportFileExcelbarang()
    {
        $barang = new BarangModels();
        $file = $this->request->getFile('file');
        $extension = $file->getClientExtension();
        if ($extension == 'xlsx' || $extension == 'xls') {
            if ($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($file);
            $kategori = $spreadsheet->getActiveSheet()->toArray();
            foreach ($kategori as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                $kategoribarang = new KategoriBarangModels();
                $datakategori = $kategoribarang->findAll();
                foreach ($datakategori as $setkategori) {
                    if ($value[2] == $setkategori['k_nama']) {
                        $set = $setkategori['k_id'];
                    }
                }

                $data = [
                    'b_id' => $value[0],
                    'b_nama' => $value[1],
                    'k_id' => $set,
                    'u_id' => session()->get('u_id'),

                ];
                $barang->insert($data);
            }
            session()->setFlashdata('success', 'Data Berhasil Diimport!');
            return redirect('admin/databarang/datasatuanbarang');
        } else {
            return redirect()->back()->with('message', 'Format File Tidak Sesuai! | Extension file harus .xls atau .xlsx');
        }
    }
    public function ExportDataExcelbarang()
    {
        $barang = new BarangModels();
        $databarang = $barang->findAll();
        $spreadsheet = new Spreadsheet();
        $colomheader = $spreadsheet->getActiveSheet();
        $colomheader->setCellValue('A1', 'No');
        $colomheader->setCellValue('B1', 'Nama Barang');
        $colomheader->setCellValue('C1', 'Nama Kategori Barang');


        $kategoribarang = new KategoriBarangModels();
        $datakategori = $kategoribarang->findAll();
        $colomdata = 2;
        foreach ($databarang as $setbarang) {
            $colomheader->setCellValue('A' . $colomdata, ($colomdata - 1));
            $colomheader->setCellValue('B' . $colomdata, $setbarang['b_nama']);
            foreach ($datakategori as $setkategori) {
                if ($setbarang['k_id'] == $setkategori['k_id']) {
                    $colomheader->setCellValue('C' . $colomdata, $setkategori['k_nama']);
                }
            }
            $colomdata++;
        }
        $colomheader->getStyle('A1:C1')->getFont()->setBold(true);
        $colomheader->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $colomheader->getStyle('A1:C' . ($colomdata - 1))->applyFromArray($styleArray);

        $colomheader->getColumnDimension('A')->setAutoSize(true);
        $colomheader->getColumnDimension('B')->setAutoSize(true);
        $colomheader->getColumnDimension('C')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheet1.sheet');
        header('Content-Disposition: attachment;filename=Export-Data-Barang.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    //Data Barang Supplier
    public function barangsupplier()
    {
        $barangsupplier = new BarangSupplierModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => 'databarangsupplier',
            'MenuDataBarang' => 'menudatabarang',
            'DataPackingBarang' => '',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataKategoriPaket' => '',
            'DataPaketBarang' => '',
            'kategoribarang' => $barangsupplier->datakategoribarang(),
            'supplier' => $barangsupplier->datasupplier(),
            'barang' => $barangsupplier->databarang(),
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => '',


        ];
        return view('admin/barangsupplier', $data);
        // return view('register');
    }
    public function barangsupplierprocess()
    {
        if (!$this->validate([
            's_id' => 'required',
            'b_id' => 'required',
            'sb_hargaasli' => 'required',
            'sb_hargajual' => 'required',
            'sb_qty' => 'required',
            'sb_berat/ukuran' => 'required',
            'sb_foto' => [
                'rules' => 'uploaded[sb_foto]|max_size[sb_foto,1024]|mime_in[sb_foto,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                'errors' => [
                    'uploaded' => '{field} Wajib diisi!',
                    'max_size' => 'Ukuran {field} Maksimal 1024 KB ',
                    'mime_in' => 'Format {field} harus JPG/JPEG/PNG!',
                ]
            ],
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }
        $sb_hargaasli = floatval(str_replace(",", "", $this->request->getVar('sb_hargaasli')));
        $sb_hargajual = floatval(str_replace(",", "", $this->request->getVar('sb_hargajual')));
        // Validasi nilai variabel
        if (!is_numeric($sb_hargaasli) || !is_numeric($sb_hargajual)) {
            echo "Input tidak valid!";
            exit;
        }
        $foto = $this->request->getFile('sb_foto');
        $nama_file = $foto->getRandomName();
        $barangsupplier = new BarangSupplierModels();
        $barangsupplier->insert([
            's_id' => $this->request->getVar('s_id'),
            'b_id' => $this->request->getVar('b_id'),
            'sb_hargaasli' => $sb_hargaasli,
            'sb_hargajual' => $sb_hargajual,
            'sb_qty' => $this->request->getVar('sb_qty'),
            'sb_berat/ukuran' => $this->request->getVar('sb_berat/ukuran'),
            'sb_ktrg_berat/ukuran' => $this->request->getVar('berat/ukuran'),
            'sb_foto' => $nama_file,
            'u_id' => session()->get('u_id')
        ]);
        $foto->move('foto-barang', $nama_file);
        session()->setFlashdata('success', 'Data Berhasil Disimpan!');
        return redirect()->to('/admin/databarang/barangsupplier');
    }
    public function listdatabarangsupplier()
    {
        $barangsupplier = new BarangSupplierModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => 'databarangsupplier',
            'MenuDataBarang' => 'menudatabarang',
            'DataPackingBarang' => '',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataKategoriPaket' => '',
            'DataPaketBarang' => '',
            'DataTransaksi' => '',
            'kategoribarang' => $barangsupplier->datakategoribarang(),
            'supplier' => $barangsupplier->datasupplier(),
            'barang' => $barangsupplier->databarang(),
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => '',

        ];
        $data['tb_sup_barang'] = $barangsupplier->findAll();
        echo view('admin/databarangsupplier', $data);
        //berdasarkan login
        // $user = new UsersModels();
        // $data['tb_user'] = $user->where('u_referensi', session('u_id'))->findAll();
        // echo view('admin/datauser', $data);
    }
    public function editbarangsupplier($sb_id)
    {
        $barangsupplier = new BarangSupplierModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => 'databarangsupplier',
            'MenuDataBarang' => 'menudatabarang',
            'DataPackingBarang' => '',
            'DataPaketBarang' => '',
            'DataKategoriPaket' => '',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataTransaksi' => '',
            'kategoribarang' => $barangsupplier->datakategoribarang(),
            'supplier' => $barangsupplier->datasupplier(),
            'barang' => $barangsupplier->databarang(),
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => '',

        ];
        // ambil artikel yang akan diedit

        $data['tb_sup_barang'] = $barangsupplier->where('sb_id', $sb_id)->first();

        if ($this->validate([
            's_id' => 'required',
            'b_id' => 'required',
            'sb_hargaasli' => 'required',
            'sb_hargajual' => 'required',
            'sb_qty' => 'required',
            'sb_berat/ukuran' => 'required',
            'sb_foto' => [
                'rules' => 'max_size[sb_foto,1024]|mime_in[sb_foto,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                'errors' => [
                    'uploaded' => '{field} Wajib diisi!',
                    'max_size' => 'Ukuran {field} Maksimal 1024 KB ',
                    'mime_in' => 'Format {field} harus JPG/JPEG/PNG!',
                ]
            ],
        ])) {
            $notnull1 = $this->request->getVar('sb_hargaasli');
            $notnull2 = $this->request->getVar('sb_hargajual');
            if ($notnull1 != null && $notnull2 != null) {
                $sb_hargaasli = floatval(str_replace(",", "", $this->request->getVar('sb_hargaasli')));
                $sb_hargajual = floatval(str_replace(",", "", $this->request->getVar('sb_hargajual')));
                // Validasi nilai variabel
                if (!is_numeric($sb_hargaasli) || !is_numeric($sb_hargajual)) {
                    echo "Input tidak valid!";
                    exit;
                }
            }
            $foto = $this->request->getFile('sb_foto');
            $prefoto = $this->request->getVar('preview');
            if ($foto->getError() == 4) {
                $nama_file = $prefoto;
            } else {
                $nama_file = $foto->getRandomName();
                if ($prefoto != '') {
                    unlink('foto-barang/' . $prefoto);
                }
                $foto->move('foto-barang', $nama_file);
            }
            $barangsupplier->update($sb_id, [
                's_id' => $this->request->getVar('s_id'),
                'b_id' => $this->request->getVar('b_id'),
                'sb_hargaasli' => $sb_hargaasli,
                'sb_hargajual' => $sb_hargajual,
                'sb_qty' => $this->request->getVar('sb_qty'),
                'sb_berat/ukuran' => $this->request->getVar('sb_berat/ukuran'),
                'sb_ktrg_berat/ukuran' => $this->request->getVar('berat/ukuran'),
                'sb_foto' => $nama_file,
                'u_id' => session()->get('u_id')
            ]);
            session()->setFlashdata('success', 'Data Berhasil Di Edit!');
            return redirect('admin/databarang/datasatuanbarangsupplier');
        } else {
            // session()->setFlashdata('error', $this->validator->listErrors());
        }
        echo view('admin/barangsupplieredit', $data);
        // lakukan validasi data artikel
        //     $validation = \Config\Services::validation();
        // $validation->setRules([
        //     's_id' => 'required',
        //     'b_id' => 'required',
        //     'sb_hargaasli' => 'required',
        //     'sb_hargajual' => 'required',
        //     'sb_qty' => 'required',
        //     'sb_berat/ukuran' => 'required',
        //     'sb_foto' => [
        //         'rules' => 'max_size[sb_foto,1024]|mime_in[sb_foto,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
        //         'errors' => [
        //             'uploaded' => '{field} Wajib diisi!',
        //             'max_size' => 'Ukuran {field} Maksimal 1024 KB ',
        //             'mime_in' => 'Format {field} harus JPG/JPEG/PNG!',
        //         ]
        //     ],
        // ]);
        // $isDataValid = $validation->withRequest($this->request)->run();
        // // jika data vlid, maka simpan ke database
        // if ($isDataValid) {

        // }
        // else {
        //     // session()->setFlashdata('error', 'Gunakan Username yang belum digunakan');
        //     session()->setFlashdata('error', $validation->listErrors());
        // }

    }
    public function deletebarangsupplier($sb_id)
    {
        $barangsupplier = new BarangSupplierModels();
        $barangsupplierfoto = $barangsupplier->databarangsupplier($sb_id);
        if ($barangsupplierfoto['sb_foto'] == '') {
        } else {
            unlink('foto-barang/' . $barangsupplierfoto['sb_foto']);
        }
        $barangsupplier->delete($sb_id);
        session()->setFlashdata('success', 'Data Berhasil Di Hapus!');
        return redirect('admin/databarang/datasatuanbarangsupplier');
    }
    public function ImportFileExcelbarangsupplier()
    {
        $barangsupplier = new BarangSupplierModels();
        $file = $this->request->getFile('file');
        $extension = $file->getClientExtension();
        if ($extension == 'xlsx' || $extension == 'xls') {
            if ($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($file);
            $kategori = $spreadsheet->getActiveSheet()->toArray();
            foreach ($kategori as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                $supplier = new SupplierModels();
                $datasupplier = $supplier->findAll();
                foreach ($datasupplier as $setsupplier) {
                    if ($value[1] == $setsupplier['s_nama']) {
                        $sp = $setsupplier['s_id'];
                    }
                }
                $barang = new BarangModels();
                $databarang = $barang->findAll();
                foreach ($databarang as $setbarang) {
                    if ($value[2] == $setbarang['b_nama']) {
                        $brg = $setbarang['b_id'];
                    }
                }

                $data = [
                    'sb_id' => $value[0],
                    's_id' => $sp,
                    'b_id' => $brg,
                    'sb_hargaasli' => $value[3],
                    'sb_hargajual' => $value[4],
                    'sb_qty' => $value[5],
                    'sb_berat/ukuran' => $value[6],
                    'sb_ktrg_berat/ukuran' => $value[7],
                    'u_id' => session()->get('u_id'),

                ];
                $barangsupplier->insert($data);
            }
            session()->setFlashdata('success', 'Data Berhasil Diimport!');
            return redirect('admin/databarang/datasatuanbarangsupplier');
        } else {
            return redirect()->back()->with('message', 'Format File Tidak Sesuai! | Extension file harus .xls atau .xlsx');
        }
    }
    public function ExportDataExcelbarangsupplier()
    {
        $barangsupplier = new BarangSupplierModels();
        $databarangsupplier = $barangsupplier->findAll();
        $spreadsheet = new Spreadsheet();
        $colomheader = $spreadsheet->getActiveSheet();
        $colomheader->setCellValue('A1', 'No');
        $colomheader->setCellValue('B1', 'Nama Supplier');
        $colomheader->setCellValue('C1', 'Nama Barang');
        $colomheader->setCellValue('D1', 'Harga Asli');
        $colomheader->setCellValue('E1', 'Harga Jual');
        $colomheader->setCellValue('F1', 'Jumlah Barang (qty)');
        $colomheader->setCellValue('G1', 'Berat/Ukuran Barang');
        $colomheader->setCellValue('H1', 'Keterangan Berat/Ukuran Barang');

        $barang = new BarangModels();
        $databarang = $barang->findAll();
        $supplier = new SupplierModels();
        $datasupplier = $supplier->findAll();
        $colomdata = 2;
        foreach ($databarangsupplier as $setbarangsupplier) {
            $colomheader->setCellValue('A' . $colomdata, ($colomdata - 1));
            foreach ($datasupplier as $setsupplier) {
                if ($setbarangsupplier['s_id'] == $setsupplier['s_id']) {
                    $colomheader->setCellValue('B' . $colomdata, $setsupplier['s_nama']);
                }
            }
            foreach ($databarang as $setbarang) {
                if ($setbarangsupplier['b_id'] == $setbarang['b_id']) {
                    $colomheader->setCellValue('C' . $colomdata, $setbarang['b_nama']);
                }
            }
            $colomheader->setCellValue('D' . $colomdata, $setbarangsupplier['sb_hargaasli']);
            $colomheader->setCellValue('E' . $colomdata, $setbarangsupplier['sb_hargajual']);
            $colomheader->setCellValue('F' . $colomdata, $setbarangsupplier['sb_qty']);
            $colomheader->setCellValue('G' . $colomdata, $setbarangsupplier['sb_berat/ukuran']);
            $colomheader->setCellValue('H' . $colomdata, $setbarangsupplier['sb_ktrg_berat/ukuran']);
            $colomdata++;
        }
        $colomheader->getStyle('A1:H1')->getFont()->setBold(true);
        $colomheader->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $colomheader->getStyle('A1:H' . ($colomdata - 1))->applyFromArray($styleArray);

        $colomheader->getColumnDimension('A')->setAutoSize(true);
        $colomheader->getColumnDimension('B')->setAutoSize(true);
        $colomheader->getColumnDimension('C')->setAutoSize(true);
        $colomheader->getColumnDimension('D')->setAutoSize(true);
        $colomheader->getColumnDimension('E')->setAutoSize(true);
        $colomheader->getColumnDimension('F')->setAutoSize(true);
        $colomheader->getColumnDimension('G')->setAutoSize(true);
        $colomheader->getColumnDimension('H')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheet1.sheet');
        header('Content-Disposition: attachment;filename=Export-Data-BarangSupplier.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    //Data Packing Barang
    public function packingbarang()
    {
        $packingbarang = new PackingBarangModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => 'menudatabarang',
            'DataPackingBarang' => 'datapackingbarang',
            'MenuDataTransaksi' => '',
            'DataKategoriPaket' => '',
            'DataPeriodeTransaksi' => '',
            'DataPaketBarang' => '',
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => '',

        ];
        return view('admin/packingbarang', $data);
        // return view('register');
    }
    public function packingbarangprocess()
    {
        if (!$this->validate([
            'pa_nama' => 'required',
            'pa_harga' => 'required',
            'pa_foto' => [
                'rules' => 'uploaded[pa_foto]|max_size[pa_foto,1024]|mime_in[pa_foto,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                'errors' => [
                    'uploaded' => '{field} Wajib diisi!',
                    'max_size' => 'Ukuran {field} Maksimal 1024 KB ',
                    'mime_in' => 'Format {field} harus JPG/JPEG/PNG!',
                ]
            ],
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }
        $pa_harga = floatval(str_replace(",", "", $this->request->getVar('pa_harga')));
        // Validasi nilai variabel
        if (!is_numeric($pa_harga)) {
            echo "Input tidak valid!";
            exit;
        }
        $foto = $this->request->getFile('pa_foto');
        $nama_file = $foto->getRandomName();
        $packingbarang = new PackingBarangModels();
        $packingbarang->insert([
            'pa_nama' => $this->request->getVar('pa_nama'),
            'pa_harga' => $pa_harga,
            'pa_foto' => $nama_file,
            'u_id' => session()->get('u_id')
        ]);
        $foto->move('foto-packaging', $nama_file);
        session()->setFlashdata('success', 'Data Berhasil Disimpan!');
        return redirect()->to('/admin/databarang/packagingbarang');
    }
    public function listdatapackingbarang()
    {
        $packingbarang = new PackingBarangModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'DataPackingBarang' => 'datapackingbarang',
            'MenuDataBarang' => 'menudatabarang',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataKategoriPaket' => '',
            'DataPaketBarang' => '',
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => '',
        ];
        $data['tb_packaging'] = $packingbarang->findAll();
        echo view('admin/datapackingbarang', $data);
        //berdasarkan login
        // $user = new UsersModels();
        // $data['tb_user'] = $user->where('u_referensi', session('u_id'))->findAll();
        // echo view('admin/datauser', $data);
    }
    public function editpackingbarang($pa_id)
    {
        $packingbarang = new PackingBarangModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'DataTransaksi' => '',
            'MenuDataBarang' => 'menudatabarang',
            'DataPackingBarang' => 'datapackingbarang',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataKategoriPaket' => '',
            'DataTransaksiCicilan' => '',
            'DataPaketBarang' => '',
            'DataTransaksiLogCicilan' => '',

        ];
        // ambil artikel yang akan diedit

        $data['tb_packaging'] = $packingbarang->where('pa_id', $pa_id)->first();
        if ($this->validate([
            'pa_nama' => 'required',
            'pa_harga' => 'required',
            'pa_foto' => [
                'rules' => 'max_size[pa_foto,1024]|mime_in[pa_foto,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                'errors' => [
                    'uploaded' => '{field} Wajib diisi!',
                    'max_size' => 'Ukuran {field} Maksimal 1024 KB ',
                    'mime_in' => 'Format {field} harus JPG/JPEG/PNG!',
                ]
            ],
        ])) {
            $notnull = $this->request->getVar('pa_harga');
            if ($notnull != null) {
                $pa_harga = floatval(str_replace(",", "", $this->request->getVar('pa_harga')));
                // Validasi nilai variabel
                if (!is_numeric($pa_harga)) {
                    echo "Input tidak valid!";
                    exit;
                }
            }
            $foto = $this->request->getFile('pa_foto');
            $prefoto = $this->request->getVar('preview');
            if ($foto->getError() == 4) {
                $nama_file = $prefoto;
            } else {
                $nama_file = $foto->getRandomName();
                if ($prefoto != '') {
                    unlink('foto-packaging/' . $prefoto);
                }
                $foto->move('foto-packaging', $nama_file);
            }
            // jika data vlid, maka simpan ke database
            $packingbarang->update($pa_id, [
                'pa_nama' => $this->request->getVar('pa_nama'),
                'pa_harga' => $pa_harga,
                'pa_foto' => $nama_file,
                'u_id' => session()->get('u_id')
            ]);
            session()->setFlashdata('success', 'Data Berhasil Di Edit!');
            return redirect('admin/databarang/datapackagingbarang');
        } else {
            // session()->setFlashdata('error', $this->validator->listErrors());
        }



        echo view('admin/packingbarangedit', $data);
    }
    public function deletepackingbarang($pa_id)
    {
        $packingbarang = new PackingBarangModels();
        $packingbarangfoto = $packingbarang->datapackaging($pa_id);
        if ($packingbarangfoto['pa_foto'] == '') {
        } else {
            unlink('foto-packaging/' . $packingbarangfoto['pa_foto']);
        }
        $packingbarang->delete($pa_id);
        session()->setFlashdata('success', 'Data Berhasil Di Hapus!');
        return redirect('admin/databarang/datapackagingbarang');
    }
    public function ImportFileExcelpackingbarang()
    {
        $packingbarang = new PackingBarangModels();
        $file = $this->request->getFile('file');
        $extension = $file->getClientExtension();
        if ($extension == 'xlsx' || $extension == 'xls') {
            if ($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($file);
            $kategori = $spreadsheet->getActiveSheet()->toArray();
            foreach ($kategori as $key => $value) {
                if ($key == 0) {
                    continue;
                }

                $data = [
                    'pa_id ' => $value[0],
                    'pa_nama' => $value[1],
                    'pa_harga' => $value[2],
                    'u_id' => session()->get('u_id'),

                ];
                $packingbarang->insert($data);
            }
            session()->setFlashdata('success', 'Data Berhasil Diimport!');
            return redirect('admin/databarang/datapackagingbarang');
        } else {
            return redirect()->back()->with('message', 'Format File Tidak Sesuai! | Extension file harus .xls atau .xlsx');
        }
    }
    public function ExportDataExcelpackingbarang()
    {
        $packingbarang = new PackingBarangModels();
        $datapackingbarang = $packingbarang->findAll();
        $spreadsheet = new Spreadsheet();
        $colomheader = $spreadsheet->getActiveSheet();
        $colomheader->setCellValue('A1', 'No');
        $colomheader->setCellValue('B1', 'Nama Packaging Barang');
        $colomheader->setCellValue('C1', 'Harga Packaging Barang');

        $colomdata = 2;
        foreach ($datapackingbarang as $setpackingbarang) {
            $colomheader->setCellValue('A' . $colomdata, ($colomdata - 1));
            $colomheader->setCellValue('B' . $colomdata, $setpackingbarang['pa_nama']);
            $colomheader->setCellValue('C' . $colomdata, $setpackingbarang['pa_harga']);
            $colomdata++;
        }
        $colomheader->getStyle('A1:C1')->getFont()->setBold(true);
        $colomheader->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $colomheader->getStyle('A1:C' . ($colomdata - 1))->applyFromArray($styleArray);

        $colomheader->getColumnDimension('A')->setAutoSize(true);
        $colomheader->getColumnDimension('B')->setAutoSize(true);
        $colomheader->getColumnDimension('C')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheet1.sheet');
        header('Content-Disposition: attachment;filename=Export-Data-PackingBarang.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    //Data Paket Barang
    public function HargaPackaging()
    {
        $Packing = new PackingBarangModels();
        $pa_id = $this->request->getPost('pa_id');
        $Harga = $Packing->HargaPackaging($pa_id);
        foreach ($Harga as $value => $H) {
            // echo "<option value=" . $H['id_kabupaten'] . ">" . $H['nama_kabupaten'] . "</option>";
            echo "<input type=" . "text" . " name=" . "pa_harga" . " class=" . "form-control cashback" . " placeholder=" . "Masukan Cashback Paket Barang" . " required hidden value=" . $H['pa_harga'] . ">";
            // echo "value=" . $H['pa_harga'] . ">";
        }
    }
    public function SelectEditPaketBarang()
    {

        $dataa = true;
        // return redirect()->to('admin/paketbarangedit')->with($dataa);

        // echo view('admin/paketbarangedit', ['selectitembarang' => $dataa]);
        return json_encode($dataa);
    }
    public function myFunction()
    {
        $myVariable = $this->request->getPost('myVariable');
        $p_id = $this->request->getPost('p_id');
        $paketbarang = new PaketBarangModels();
        $pengambilanpaketbarang = new PengambilanPaketBarangModels();
        $barangsupplier = $pengambilanpaketbarang->get_all_barang_supplier();
        $supplier = $pengambilanpaketbarang->get_all_supplier();
        $barang = $pengambilanpaketbarang->get_all_barang();

        $selected_barang = $paketbarang->get_pengambilan_paket_by_id($p_id);
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => 'menudatabarang',
            'DataPackingBarang' => '',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataPaketBarang' => 'datapaketbarang',
            'packagingbarang' => $paketbarang->datapackagingbarang(),
            'payperiode' => $paketbarang->datapayperiode(),
            'kategoribarang' => $paketbarang->datakategoribarang(),
            // 'barangsupplier' => $paketbarang->databarangsupplier(),
            // 'supplier' => $paketbarang->datasupplier(),
            // 'barang' => $paketbarang->databarang(),
            // 'pengambilanpaket' => $paketbarang->datapengambilanpaket(),
            'paket' => $paketbarang->datapaketbarang(),
            'DataTransaksi' => '',
            'DataTransaksiLogCicilan' => '',
            'pengambilanpaket' => $pengambilanpaketbarang->get_pengambilan_paket_by_idi('pp_p_id', $p_id),
            'barangsupplier' => $pengambilanpaketbarang->get_all_barang_supplier(),
            'supplier' => $pengambilanpaketbarang->get_all_supplier(),
            'barang' => $pengambilanpaketbarang->get_all_barang(),
            'selected_barang' => $selected_barang, // an array of selected values
            'selectitembarang' => true,

        ];
        // ambil artikel yang akan diedit

        $data['tb_paket'] = $paketbarang->where('p_id', $p_id)->first();
        // $data['pengambilan_paket'] = $paketbarang->get_pengambilan_paket_by_id($p_id);

        echo view('admin/paketbarangedit', $data);
    }
    public function paketbarang()
    {
        $paketbarang = new PaketBarangModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => 'menudatabarang',
            'DataPackingBarang' => '',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataTransaksi' => '',
            'DataKategoriPaket' => '',
            'DataTransaksiCicilan' => '',
            'DataPaketBarang' => 'datapaketbarang',
            'packagingbarang' => $paketbarang->datapackagingbarang(),
            'payperiode' => $paketbarang->datapayperiode(),
            'barangsupplier' => $paketbarang->databarangsupplier(),
            'kategoribarang' => $paketbarang->datakategoribarang(),
            'supplier' => $paketbarang->datasupplier(),
            'barang' => $paketbarang->databarang(),
            'kategoripaket' => $paketbarang->datakategoripaket(),
            'DataTransaksiLogCicilan' => '',

        ];
        return view('admin/paketbarang', $data);
        // return view('register');
    }
    public function paketbarangprocess()
    {
        $pp_barang = new PengambilanPaketBarangModels();
        if (!$this->validate([
            'kp_id' => 'required',
            'pe_id' => 'required',
            'p_hargaJual' => 'required',
            'p_hargaBarang' => 'required',
            'pa_id' => 'required',
            'p_cashback' => 'required',
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }
        // $p_hargajual = $this->request->getVar('p_hargaJual');
        // $p_hargabarang = $this->request->getVar('p_hargaBarang');
        // $pa_harga = $this->request->getVar('pa_harga');
        $p_hargajual = floatval(str_replace(",", "", $this->request->getVar('p_hargaJual')));
        $p_hargabarang = floatval(str_replace(",", "", $this->request->getVar('p_hargaBarang')));
        $pa_harga = floatval(str_replace(",", "", $this->request->getVar('pa_harga')));
        $p_cashback = floatval(str_replace(",", "", $this->request->getVar('p_cashback')));
        // Validasi nilai variabel
        if (!is_numeric($p_hargajual) || !is_numeric($p_hargabarang) || !is_numeric($pa_harga) || !is_numeric($p_cashback)) {
            echo "Input tidak valid!";
            exit;
        }
        $insentip = ($p_hargajual - $p_cashback - $pa_harga) * 0.1;
        $labakotor = $p_hargajual - ($p_hargabarang + $p_cashback + $pa_harga + $insentip);
        $presentaseLB  = ($labakotor / $p_hargajual) * 100;
        // echo "Insentive";
        // print_r($insentip);
        // echo "Laba Kotor";
        // print_r($labakotor);
        // echo "Presebtase Laba";
        // print_r($presentaseLB);
        $paketbarang = new PaketBarangModels();
        $paketbarang->insert([
            'kp_id' => $this->request->getVar('kp_id'),
            'pe_id' => $this->request->getVar('pe_id'),
            'p_hargaJual' => $p_hargajual,
            'p_hargaBarang' => $p_hargabarang,
            'pa_id' => $this->request->getVar('pa_id'),
            'p_cashback' => $p_cashback,
            'p_insentive' => $insentip,
            'p_laba' => $labakotor,
            'p_persentaseLaba' => $presentaseLB,
            'u_id' => session()->get('u_id')
        ]);
        $namabarang = count($this->request->getVar('p_barang'));
        for ($i = 0; $i < $namabarang; $i++) {
            $databarang = $this->request->getVar('p_barang[' . $i . ']');
            $pp_barang->insert([
                'pp_p_id' => $paketbarang->getInsertID(),
                'p_sb_id' => $databarang,
                'p_pa_id' => $this->request->getVar('pa_id')
            ]);
        }

        session()->setFlashdata('success', 'Data Berhasil Disimpan!');
        return redirect()->to('/admin/databarang/paketbarang');
        // echo view('admin/test');
    }
    public function listdatapaketbarang()
    {
        $paketbarang = new PaketBarangModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => 'menudatabarang',
            'DataPackingBarang' => '',
            'DataTransaksiCicilan' => '',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataKategoriPaket' => '',
            'DataTransaksi' => '',
            'DataPaketBarang' => 'datapaketbarang',
            'packagingbarang' => $paketbarang->datapackagingbarang(),
            'payperiode' => $paketbarang->datapayperiode(),
            'barangsupplier' => $paketbarang->databarangsupplier(),
            'kategoribarang' => $paketbarang->datakategoribarang(),
            'supplier' => $paketbarang->datasupplier(),
            'barang' => $paketbarang->databarang(),
            'pengambilanpaket' => $paketbarang->datapengambilanpaket(),
            'kategoripaket' => $paketbarang->datakategoripaket(),
            'DataTransaksiLogCicilan' => '',

        ];
        $data['tb_paket'] = $paketbarang->findAll();
        echo view('admin/datapaketbarang', $data);
        //berdasarkan login
        // $user = new UsersModels();
        // $data['tb_user'] = $user->where('u_referensi', session('u_id'))->findAll();
        // echo view('admin/datauser', $data);
    }
    // public function getpaket()
    // {
    //     $paketbarang = new PaketBarangModels();
    //     $p_id = $this->request->getPost('p_id');
    //     $data = $paketbarang->get_pengambilan_paket_by_id($p_id);
    //     foreach ($data as $item) {
    //         $value[] = (float)$item->pp_p_id;
    //     }
    //     echo json_encode($value);
    // }
    public function editpaketbarang($p_id)
    {
        $paketbarang = new PaketBarangModels();
        $pp_barang = new PengambilanPaketBarangModels();
        $barangsupplier = $pp_barang->get_all_barang_supplier();
        $supplier = $pp_barang->get_all_supplier();
        $barang = $pp_barang->get_all_barang();

        $selected_barang = $paketbarang->get_pengambilan_paket_by_id($p_id);
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => 'menudatabarang',
            'DataPackingBarang' => '',
            'MenuDataTransaksi' => '',
            'DataPeriodeTransaksi' => '',
            'DataKategoriPaket' => '',
            'DataTransaksiCicilan' => '',
            'DataPaketBarang' => 'datapaketbarang',
            'packagingbarang' => $paketbarang->datapackagingbarang(),
            'payperiode' => $paketbarang->datapayperiode(),
            'kategoripaket' => $paketbarang->datakategoripaket(),
            'kategoribarang' => $paketbarang->datakategoribarang(),
            // 'barangsupplier' => $paketbarang->databarangsupplier(),
            // 'supplier' => $paketbarang->datasupplier(),
            // 'barang' => $paketbarang->databarang(),
            // 'pengambilanpaket' => $paketbarang->datapengambilanpaket(),
            'paket' => $paketbarang->datapaketbarang(),
            'DataTransaksi' => '',
            'DataTransaksiLogCicilan' => '',
            'pengambilanpaket' => $pp_barang->get_pengambilan_paket_by_idi('pp_p_id', $p_id),
            'barangsupplier' => $pp_barang->get_all_barang_supplier(),
            'supplier' => $pp_barang->get_all_supplier(),
            'barang' => $pp_barang->get_all_barang(),
            'selected_barang' => $selected_barang, // an array of selected values
            'selectitembarang' => 'false',

        ];
        // ambil artikel yang akan diedit

        $data['tb_paket'] = $paketbarang->where('p_id', $p_id)->first();
        // $data['pengambilan_paket'] = $paketbarang->get_pengambilan_paket_by_id($p_id);

        // lakukan validasi data artikel
        $validation = \Config\Services::validation();
        $validation->setRules([
            'kp_id' => 'required',
            'pe_id' => 'required',
            'p_hargaJual' => 'required',
            'p_hargaBarang' => 'required',
            'pa_id' => 'required',
            'p_cashback' => 'required',
            'p_insentive' => 'required',
            'p_laba' => 'required',
            'p_persentaseLaba' => 'required',
        ]);
        $isDataValid = $validation->withRequest($this->request)->run();
        // jika data vlid, maka simpan ke database
        if ($isDataValid) {
            $notnull1 = $this->request->getVar('p_hargaJual');
            $notnull2 = $this->request->getVar('p_hargaBarang');
            $notnull3 = $this->request->getVar('pa_harga');
            $notnull4 = $this->request->getVar('p_cashback');
            if ($notnull1 != null && $notnull2 != null && $notnull3 != null && $notnull4 != null) {
                $p_hargajual = floatval(str_replace(",", "", $this->request->getVar('p_hargaJual')));
                $p_hargabarang = floatval(str_replace(",", "", $this->request->getVar('p_hargaBarang')));
                $pa_harga = floatval(str_replace(",", "", $this->request->getVar('pa_harga')));
                $p_cashback = floatval(str_replace(",", "", $this->request->getVar('p_cashback')));
                // Validasi nilai variabel
                if (!is_numeric($p_hargajual) || !is_numeric($p_hargabarang) || !is_numeric($pa_harga) || !is_numeric($p_cashback)) {
                    echo "Input tidak valid!";
                    exit;
                }
                $insentip = ($p_hargajual - $p_cashback - $pa_harga) * 0.1;
                $labakotor = $p_hargajual - ($p_hargabarang + $p_cashback + $pa_harga + $insentip);
                $presentaseLB  = ($labakotor / $p_hargajual) * 100;
                $paketbarang->update($p_id, [
                    'kp_id' => $this->request->getVar('kp_id'),
                    'pe_id' => $this->request->getVar('pe_id'),
                    'p_hargaJual' => $p_hargajual,
                    'p_hargaBarang' => $p_hargabarang,
                    'pa_id' => $this->request->getVar('pa_id'),
                    'p_cashback' => $p_cashback,
                    'p_insentive' => $insentip,
                    'p_laba' => $labakotor,
                    'p_persentaseLaba' => $presentaseLB,
                    'u_id' => session()->get('u_id')
                ]);
                $namabarang = count($this->request->getVar('p_barang'));
                for ($i = 0; $i < $namabarang; $i++) {
                    $databarang = $this->request->getVar('p_barang[' . $i . ']');
                    $pp_barang->insert([
                        'pp_p_id' => $paketbarang->getInsertID(),
                        'p_sb_id' => $databarang,
                        'p_pa_id' => $this->request->getVar('pa_id')
                    ]);
                }
            }
            //$namabarang = count($this->request->getVar('p_barang'));
            // for ($i = 0; $i < $namabarang; $i++) {
            //     $databarang = $this->request->getVar('p_barang[' . $i . ']');
            // }
            session()->setFlashdata('success', 'Data Berhasil Di Edit!');
            return redirect('admin/databarang/datapaketbarang');
        }
        echo view('admin/paketbarangedit', $data);
    }
    public function deletepaketbarang($p_id)
    {
        $paketbarang = new PaketBarangModels();
        $pengambilanpaketbarang = new PengambilanPaketBarangModels();
        $pengambilanpaketbarang->deletes($p_id);
        $paketbarang->delete($p_id);
        session()->setFlashdata('success', 'Data Berhasil Di Hapus!');
        return redirect('admin/databarang/datapaketbarang');
    }
    public function ImportFileExcelpaketbarang()
    {
        $paketbarang = new PaketBarangModels();
        $file = $this->request->getFile('file');
        $extension = $file->getClientExtension();
        if ($extension == 'xlsx' || $extension == 'xls') {
            if ($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($file);
            $kategori = $spreadsheet->getActiveSheet()->toArray();
            foreach ($kategori as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                $barang = new BarangModels();
                $databarang = $barang->findAll();
                foreach ($databarang as $setbarang) {
                    if ($value[2] == $setbarang['b_nama']) {
                        $brg = $setbarang['b_id'];
                    }
                }
                $supplier = new SupplierModels();
                $datasupplier = $supplier->findAll();
                foreach ($datasupplier as $setsupplier) {
                    if ($value[1] == $setsupplier['s_nama']) {
                        $sp = $setsupplier['s_id'];
                    }
                }

                $data = [
                    'sb_id' => $value[0],
                    's_id' => $sp,
                    'b_id' => $brg,
                    'u_id' => session()->get('u_id'),

                ];
                $paketbarang->insert($data);
            }
            session()->setFlashdata('success', 'Data Berhasil Diimport!');
            return redirect('admin/databarang/datapaketbarang');
        } else {
            return redirect()->back()->with('message', 'Format File Tidak Sesuai! | Extension file harus .xls atau .xlsx');
        }
    }
    public function ExportDataExcelpaketbarang()
    {
        $paketbarang = new PaketBarangModels();
        $datapaketbarang = $paketbarang->findAll();
        $spreadsheet = new Spreadsheet();
        $colomheader = $spreadsheet->getActiveSheet();
        $colomheader->setCellValue('A1', 'No');
        $colomheader->setCellValue('B1', 'Nama Supplier');
        $colomheader->setCellValue('C1', 'Nama Barang');

        $barang = new BarangModels();
        $databarang = $barang->findAll();
        $supplier = new SupplierModels();
        $datasupplier = $supplier->findAll();
        $colomdata = 2;
        foreach ($datapaketbarang as $setpaketbarang) {
            $colomheader->setCellValue('A' . $colomdata, ($colomdata - 1));
            foreach ($datasupplier as $setsupplier) {
                if ($setpaketbarang['s_id'] == $setsupplier['s_id']) {
                    $colomheader->setCellValue('B' . $colomdata, $setsupplier['s_nama']);
                }
            }
            foreach ($databarang as $setbarang) {
                if ($setpaketbarang['b_id'] == $setbarang['b_id']) {
                    $colomheader->setCellValue('C' . $colomdata, $setbarang['b_nama']);
                }
            }
            $colomdata++;
        }
        $colomheader->getStyle('A1:C1')->getFont()->setBold(true);
        $colomheader->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $colomheader->getStyle('A1:C' . ($colomdata - 1))->applyFromArray($styleArray);

        $colomheader->getColumnDimension('A')->setAutoSize(true);
        $colomheader->getColumnDimension('B')->setAutoSize(true);
        $colomheader->getColumnDimension('C')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheet1.sheet');
        header('Content-Disposition: attachment;filename=Export-Data-PaketBarang.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }



    //Data Periode Pembayaran
    public function periodepembayaran()
    {
        $periodepembayaran = new PeriodePembayaranModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => '',
            'DataKategoriPaket' => '',
            'DataPackingBarang' => '',
            'DataPaketBarang' => '',
            'MenuDataTransaksi' => 'menudatatransaksi',
            'DataPeriodeTransaksi' => 'dataperiodetransaksi',
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => '',

        ];
        return view('admin/periodepembayaran', $data);
        // return view('register');
    }
    public function periodepembayaranprocess()
    {
        if (!$this->validate([
            'pe_nama' => 'required',
            'pe_periode' => 'required',
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }
        $periodepembayaran = new PeriodePembayaranModels();
        $periodepembayaran->insert([
            'pe_nama' => $this->request->getVar('pe_nama'),
            'pe_periode' => $this->request->getVar('pe_periode'),
            'u_id' => session()->get('u_id')
        ]);
        session()->setFlashdata('success', 'Data Berhasil Disimpan!');
        return redirect()->to('/admin/datatransaksi/periodepembayaran');
    }
    public function listdataperiodepembayaran()
    {
        $periodepembayaran = new PeriodePembayaranModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'DataPackingBarang' => '',
            'MenuDataBarang' => '',
            'DataPaketBarang' => '',
            'DataKategoriPaket' => '',
            'DataTransaksiCicilan' => '',
            'MenuDataTransaksi' => 'menudatatransaksi',
            'DataPeriodeTransaksi' => 'dataperiodetransaksi',
            'DataTransaksi' => '',
            'DataTransaksiLogCicilan' => '',

        ];
        $data['tb_pay_periode'] = $periodepembayaran->findAll();
        echo view('admin/dataperiodepembayaran', $data);
        //berdasarkan login
        // $user = new UsersModels();
        // $data['tb_user'] = $user->where('u_referensi', session('u_id'))->findAll();
        // echo view('admin/datauser', $data);
    }
    public function editperiodepembayaran($pe_id)
    {
        $periodepembayaran = new PeriodePembayaranModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataKategoriPaket' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => '',
            'DataPackingBarang' => '',
            'DataPaketBarang' => '',
            'MenuDataTransaksi' => 'menudatatransaksi',
            'DataPeriodeTransaksi' => 'dataperiodetransaksi',
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => '',

        ];
        // ambil artikel yang akan diedit

        $data['tb_pay_periode'] = $periodepembayaran->where('pe_id', $pe_id)->first();

        // lakukan validasi data artikel
        $validation = \Config\Services::validation();
        $validation->setRules([
            'pe_nama' => 'required',
            'pe_periode' => 'required',
        ]);
        $isDataValid = $validation->withRequest($this->request)->run();
        // jika data vlid, maka simpan ke database
        if ($isDataValid) {
            $periodepembayaran->update($pe_id, [
                'pe_nama' => $this->request->getVar('pe_nama'),
                'pe_periode' => $this->request->getVar('pe_periode'),
                'u_id' => session()->get('u_id')
            ]);
            session()->setFlashdata('success', 'Data Berhasil Di Edit!');
            return redirect('admin/datatransaksi/dataperiodepembayaran');
        }
        echo view('admin/periodepembayaranedit', $data);
    }
    public function deleteperiodepembayaran($pe_id)
    {
        $periodepembayaran = new PeriodePembayaranModels();
        $periodepembayaran->delete($pe_id);
        session()->setFlashdata('success', 'Data Berhasil Di Hapus!');
        return redirect('admin/datatransaksi/dataperiodepembayaran');
    }
    public function ImportFileExcelperiodepembayaran()
    {
        $periodepembayaran = new PeriodePembayaranModels();
        $file = $this->request->getFile('file');
        $extension = $file->getClientExtension();
        if ($extension == 'xlsx' || $extension == 'xls') {
            if ($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($file);
            $kategori = $spreadsheet->getActiveSheet()->toArray();
            foreach ($kategori as $key => $value) {
                if ($key == 0) {
                    continue;
                }

                $data = [
                    'pe_id ' => $value[0],
                    'pe_nama' => $value[1],
                    'pe_periode' => $value[2],
                    'u_id' => session()->get('u_id'),

                ];
                $periodepembayaran->insert($data);
            }
            session()->setFlashdata('success', 'Data Berhasil Diimport!');
            return redirect('admin/datatransaksi/dataperiodepembayaran');
        } else {
            return redirect()->back()->with('message', 'Format File Tidak Sesuai! | Extension file harus .xls atau .xlsx');
        }
    }
    public function ExportDataExcelperiodepembayaran()
    {
        $periodepembayaran = new PeriodePembayaranModels();
        $dataperiodepembayaran = $periodepembayaran->findAll();
        $spreadsheet = new Spreadsheet();
        $colomheader = $spreadsheet->getActiveSheet();
        $colomheader->setCellValue('A1', 'No');
        $colomheader->setCellValue('B1', 'Nama Periode Pembayaran');
        $colomheader->setCellValue('C1', 'Jumlah Periode Pembayaran');

        $colomdata = 2;
        foreach ($dataperiodepembayaran as $setperiodepembayaran) {
            $colomheader->setCellValue('A' . $colomdata, ($colomdata - 1));
            $colomheader->setCellValue('B' . $colomdata, $setperiodepembayaran['pe_nama']);
            $colomheader->setCellValue('C' . $colomdata, $setperiodepembayaran['pe_periode']);
            $colomdata++;
        }
        $colomheader->getStyle('A1:C1')->getFont()->setBold(true);
        $colomheader->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $colomheader->getStyle('A1:C' . ($colomdata - 1))->applyFromArray($styleArray);

        $colomheader->getColumnDimension('A')->setAutoSize(true);
        $colomheader->getColumnDimension('B')->setAutoSize(true);
        $colomheader->getColumnDimension('C')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheet1.sheet');
        header('Content-Disposition: attachment;filename=Export-Data-PeriodePembayaran.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    //Data Transaksi Pembayaran
    public function transaksi()
    {
        $transaksi = new TransaksiModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => '',
            'DataKategoriPaket' => '',
            'DataPackingBarang' => '',
            'DataPaketBarang' => '',
            'MenuDataTransaksi' => 'menudatatransaksi',
            'DataPeriodeTransaksi' => '',
            'DataTransaksi' => 'datatransaksi',
            'DataPaket' => $transaksi->datapaket(),
            'DataUser' => $transaksi->datauser(),
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => '',


        ];
        return view('admin/transaksi', $data);
        // return view('register');
    }
    public function transaksiprocess()
    {
        if (!$this->validate([
            'u_id' => 'required',
            'p_id' => 'required',
            't_qty' => 'required',
            't_status' => 'required',
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }
        $hargapaket = $this->request->getVar('p_hargapaket');
        $qty = $this->request->getVar('t_qty');
        $total = $hargapaket * $qty;
        $transaksi = new TransaksiModels();
        $transaksi->insert([
            'u_id' => $this->request->getVar('u_id'),
            'p_id' => $this->request->getVar('p_id'),
            't_qty' => $qty,
            't_totalharga' => $total,
            't_status' => $this->request->getVar('t_status')
        ]);
        session()->setFlashdata('success', 'Data Berhasil Disimpan!');
        return redirect()->to('/admin/datatransaksi/transaksi');
    }
    public function listdatatransaksi()
    {
        $transaksi = new TransaksiModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'DataPackingBarang' => '',
            'MenuDataBarang' => '',
            'DataPaketBarang' => '',
            'DataKategoriPaket' => '',
            'DataTransaksiCicilan' => '',
            'MenuDataTransaksi' => 'menudatatransaksi',
            'DataPeriodeTransaksi' => '',
            'DataTransaksi' => 'datatransaksi',
            'DataPaket' => $transaksi->datapaket(),
            'DataUser' => $transaksi->datauser(),
            'DataTransaksiLogCicilan' => '',

        ];
        $data['tb_transaksi'] = $transaksi->findAll();
        echo view('admin/datatransaksi', $data);
        //berdasarkan login
        // $user = new UsersModels();
        // $data['tb_user'] = $user->where('u_referensi', session('u_id'))->findAll();
        // echo view('admin/datauser', $data);
    }
    public function editapprovedtransaksi($p_id)
    {
        $transaksi = new TransaksiModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => '',
            'DataPackingBarang' => '',
            'DataPaketBarang' => '',
            'DataKategoriPaket' => '',
            'MenuDataTransaksi' => 'menudatatransaksi',
            'DataPeriodeTransaksi' => '',
            'DataTransaksi' => 'datatransaksi',
            'DataPaket' => $transaksi->datapaket(),
            'DataUser' => $transaksi->datauser(),
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => '',

        ];
        $data['tb_transaksi'] = $transaksi->findAll();
        if ($p_id != null) {
            $transaksi->update($p_id, [
                't_approval_by' => session()->get('u_id')
            ]);
            session()->setFlashdata('success', 'Data Berhasil Di Setujui!');
            return redirect('admin/datatransaksi/datatransaksi');
        }
        echo view('admin/datatransaksi', $data);
    }
    public function editnoapprovedtransaksi($p_id)
    {
        $transaksi = new TransaksiModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => '',
            'DataPackingBarang' => '',
            'DataKategoriPaket' => '',
            'DataPaketBarang' => '',
            'MenuDataTransaksi' => 'menudatatransaksi',
            'DataPeriodeTransaksi' => '',
            'DataTransaksi' => 'datatransaksi',
            'DataPaket' => $transaksi->datapaket(),
            'DataUser' => $transaksi->datauser(),
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => '',

        ];
        $data['tb_transaksi'] = $transaksi->findAll();
        if ($p_id != null) {
            $transaksi->update($p_id, [
                't_approval_by' => session()->get('u_id')
            ]);
            session()->setFlashdata('success', 'Data Berhasil Di Setujui!');
            return redirect('admin/datatransaksi/datatransaksi');
        }
        echo view('admin/datatransaksi', $data);
    }
    public function edittransaksi($p_id)
    {
        $transaksi = new TransaksiModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => '',
            'DataPackingBarang' => '',
            'DataKategoriPaket' => '',
            'DataPaketBarang' => '',
            'MenuDataTransaksi' => 'menudatatransaksi',
            'DataPeriodeTransaksi' => '',
            'DataTransaksi' => 'datatransaksi',
            'DataPaket' => $transaksi->datapaket(),
            'DataUser' => $transaksi->datauser(),
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => '',

        ];
        // ambil artikel yang akan diedit

        $data['tb_transaksi'] = $transaksi->where('t_id', $p_id)->first();

        // lakukan validasi data artikel
        $validation = \Config\Services::validation();
        $validation->setRules([
            'u_id' => 'required',
            'p_id' => 'required',
            't_qty' => 'required',
            't_status' => 'required',
        ]);
        $isDataValid = $validation->withRequest($this->request)->run();
        $hargapaket = $this->request->getVar('p_hargapaket');
        $qty = $this->request->getVar('t_qty');
        $total = $hargapaket * $qty;
        // jika data vlid, maka simpan ke database
        if ($isDataValid) {
            $transaksi->update($p_id, [
                'u_id' => $this->request->getVar('u_id'),
                'p_id' => $this->request->getVar('p_id'),
                't_qty' => $qty,
                't_totalharga' => $total,
                't_status' => $this->request->getVar('t_status')
            ]);
            session()->setFlashdata('success', 'Data Berhasil Di Edit!');
            return redirect('admin/datatransaksi/datatransaksi');
        }
        echo view('admin/transaksiedit', $data);
    }
    public function deletetransaksi($p_id)
    {
        $transaksi = new TransaksiModels();
        $transaksi->delete($p_id);
        session()->setFlashdata('success', 'Data Berhasil Di Hapus!');
        return redirect('admin/datatransaksi/datatransaksi');
    }
    public function ImportFileExceltransaksi()
    {
        $transaksi = new TransaksiModels();
        $file = $this->request->getFile('file');
        $extension = $file->getClientExtension();
        if ($extension == 'xlsx' || $extension == 'xls') {
            if ($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($file);
            $kategori = $spreadsheet->getActiveSheet()->toArray();
            foreach ($kategori as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                $users = new UsersModels();
                $datausers = $users->findAll();
                foreach ($datausers as $setusers) {
                    if ($value[1] == $setusers['u_nama']) {
                        $user = $setusers['u_id'];
                    }
                }
                $paket = new PaketBarangModels();
                $datapaket = $paket->findAll();
                foreach ($datapaket as $setpaket) {
                    if ($value[2] == $setpaket['p_nama']) {
                        $pkt = $setpaket['p_id'];
                    }
                }
                $data = [
                    't_id ' => $value[0],
                    'u_id' => $user,
                    'p_id' => $pkt,
                    't_qty' => $value[3],
                    'waktu' => $value[4],
                    't_status' => $value[5],
                    't_approval_by' => session()->get('u_id'),

                ];
                $transaksi->insert($data);
            }
            session()->setFlashdata('success', 'Data Berhasil Diimport!');
            return redirect('admin/datatransaksi/datatransaksi');
        } else {
            return redirect()->back()->with('message', 'Format File Tidak Sesuai! | Extension file harus .xls atau .xlsx');
        }
    }
    public function ExportDataExceltransaksi()
    {
        $transaksi = new TransaksiModels();
        $datatransaksi = $transaksi->findAll();
        $spreadsheet = new Spreadsheet();
        $colomheader = $spreadsheet->getActiveSheet();
        $colomheader->setCellValue('A1', 'No');
        $colomheader->setCellValue('B1', 'Nama Pengambil Paket');
        $colomheader->setCellValue('C1', 'Nama Paket');
        $colomheader->setCellValue('D1', 'Jumlah Paket');
        $colomheader->setCellValue('E1', 'Waktu Transaksi Paket');
        $colomheader->setCellValue('F1', 'Status Transaksi Paket');

        $users = new UsersModels();
        $datausers = $users->findAll();
        $paket = new PaketBarangModels();
        $datapaket = $paket->findAll();
        $colomdata = 2;
        foreach ($datatransaksi as $settransaksi) {
            $colomheader->setCellValue('A' . $colomdata, ($colomdata - 1));
            foreach ($datausers as $setusers) {
                if ($settransaksi['u_id'] == $setusers['u_id']) {
                    $colomheader->setCellValue('B' . $colomdata, $setusers['u_nama']);
                }
            }
            foreach ($datapaket as $setpaket) {
                if ($settransaksi['p_id'] == $setpaket['p_id']) {
                    $colomheader->setCellValue('C' . $colomdata, $setpaket['p_nama']);
                }
            }
            $colomheader->setCellValue('D' . $colomdata, $settransaksi['t_qty']);
            $colomheader->setCellValue('E' . $colomdata, $settransaksi['waktu']);
            $colomheader->setCellValue('F' . $colomdata, $settransaksi['t_status']);
            $colomdata++;
        }
        $colomheader->getStyle('A1:F1')->getFont()->setBold(true);
        $colomheader->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $colomheader->getStyle('A1:F' . ($colomdata - 1))->applyFromArray($styleArray);

        $colomheader->getColumnDimension('A')->setAutoSize(true);
        $colomheader->getColumnDimension('B')->setAutoSize(true);
        $colomheader->getColumnDimension('C')->setAutoSize(true);
        $colomheader->getColumnDimension('D')->setAutoSize(true);
        $colomheader->getColumnDimension('E')->setAutoSize(true);
        $colomheader->getColumnDimension('F')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheet1.sheet');
        header('Content-Disposition: attachment;filename=Export-Data-Transaksi.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    //Data cicilan Pembayaran
    public function cicilan()
    {
        $cicilan = new CicilanModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => '',
            'DataPackingBarang' => '',
            'DataKategoriPaket' => '',
            'DataPaketBarang' => '',
            'MenuDataTransaksi' => 'menudatatransaksi',
            'DataPeriodeTransaksi' => '',
            'DataTransaksi' => '',
            'DataPaket' => $cicilan->datapaket(),
            'DataUser' => $cicilan->datauser(),
            'DataPayPeriode' => $cicilan->dataperiode(),
            'DataTransaksiFungsi' => $cicilan->datatransaksi(),
            'DataTransaksiCicilan' => 'datatransaksicicilan',
            'DataTransaksiLogCicilan' => '',


        ];
        return view('admin/cicilan', $data);
        // return view('register');
    }
    public function cicilanprocess()
    {
        if (!$this->validate([
            'u_id' => 'required',
            'p_id' => 'required',
            't_id' => 'required',
            'pe_id' => 'required',
            'c_total_cicilan' => 'required',
            'c_cicilan_masuk' => 'required',
            'c_cicilan_outstanding' => 'required',
            'c_total_biaya' => 'required',
            'c_biaya_masuk' => 'required',
            'c_biaya_outstanding' => 'required',
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }
        $c_total_cicilan = floatval(str_replace(",", "", $this->request->getVar('c_total_cicilan')));
        $c_cicilan_masuk = floatval(str_replace(",", "", $this->request->getVar('c_cicilan_masuk')));
        $c_cicilan_outstanding = floatval(str_replace(",", "", $this->request->getVar('c_cicilan_outstanding')));
        $c_total_biaya = floatval(str_replace(",", "", $this->request->getVar('c_total_biaya')));
        $c_biaya_masuk = floatval(str_replace(",", "", $this->request->getVar('c_biaya_masuk')));
        $c_biaya_outstanding = floatval(str_replace(",", "", $this->request->getVar('c_biaya_outstanding')));
        // Validasi nilai variabel
        if (!is_numeric($c_total_cicilan) || !is_numeric($c_cicilan_masuk) || !is_numeric($c_cicilan_outstanding) || !is_numeric($c_total_biaya) || !is_numeric($c_biaya_masuk) || !is_numeric($c_biaya_outstanding)) {
            echo "Input tidak valid!";
            exit;
        }
        $cicilan = new CicilanModels();
        $cicilan->insert([
            'u_id' => $this->request->getVar('u_id'),
            'p_id' => $this->request->getVar('p_id'),
            't_id' => $this->request->getVar('t_id'),
            'pe_id' => $this->request->getVar('pe_id'),
            'c_total_cicilan' => $c_total_cicilan,
            'c_cicilan_masuk' => $c_cicilan_masuk,
            'c_cicilan_outstanding' => $c_cicilan_outstanding,
            'c_total_biaya' => $c_total_biaya,
            'c_biaya_masuk' => $c_biaya_masuk,
            'c_biaya_outstanding' => $c_biaya_outstanding
        ]);
        session()->setFlashdata('success', 'Data Berhasil Disimpan!');
        return redirect()->to('/admin/datatransaksi/cicilan');
    }
    public function listdatacicilan()
    {
        $cicilan = new CicilanModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'DataPackingBarang' => '',
            'DataKategoriPaket' => '',
            'MenuDataBarang' => '',
            'DataPaketBarang' => '',
            'MenuDataTransaksi' => 'menudatatransaksi',
            'DataPeriodeTransaksi' => '',
            'DataTransaksi' => '',
            'DataPaket' => $cicilan->datapaket(),
            'DataUser' => $cicilan->datauser(),
            'DataPayPeriode' => $cicilan->dataperiode(),
            'DataTransaksiFungsi' => $cicilan->datatransaksi(),
            'DataTransaksiCicilan' => 'datatransaksicicilan',
            'DataTransaksiLogCicilan' => '',

        ];
        $data['tb_cicilan'] = $cicilan->findAll();
        echo view('admin/datacicilan', $data);
        //berdasarkan login
        // $user = new UsersModels();
        // $data['tb_user'] = $user->where('u_referensi', session('u_id'))->findAll();
        // echo view('admin/datauser', $data);
    }
    public function editcicilan($c_id)
    {
        $cicilan = new CicilanModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => '',
            'DataPackingBarang' => '',
            'DataKategoriPaket' => '',
            'DataPaketBarang' => '',
            'MenuDataTransaksi' => 'menudatatransaksi',
            'DataPeriodeTransaksi' => '',
            'DataTransaksi' => '',
            'DataPaket' => $cicilan->datapaket(),
            'DataUser' => $cicilan->datauser(),
            'DataPayPeriode' => $cicilan->dataperiode(),
            'DataTransaksiFungsi' => $cicilan->datatransaksi(),
            'DataTransaksiCicilan' => 'datatransaksicicilan',
            'DataTransaksiLogCicilan' => '',

        ];
        // ambil artikel yang akan diedit

        $data['tb_cicilan'] = $cicilan->where('c_id', $c_id)->first();

        // lakukan validasi data artikel
        $validation = \Config\Services::validation();
        $validation->setRules([
            'u_id' => 'required',
            'p_id' => 'required',
            't_id' => 'required',
            'pe_id' => 'required',
            'c_total_cicilan' => 'required',
            'c_cicilan_masuk' => 'required',
            'c_cicilan_outstanding' => 'required',
            'c_total_biaya' => 'required',
            'c_biaya_masuk' => 'required',
            'c_biaya_outstanding' => 'required',
        ]);
        $isDataValid = $validation->withRequest($this->request)->run();
        // jika data vlid, maka simpan ke database
        if ($isDataValid) {
            $cicilan->update($c_id, [
                'u_id' => $this->request->getVar('u_id'),
                'p_id' => $this->request->getVar('p_id'),
                't_id' => $this->request->getVar('t_id'),
                'pe_id' => $this->request->getVar('pe_id'),
                'c_total_cicilan' => $this->request->getVar('c_total_cicilan'),
                'c_cicilan_masuk' => $this->request->getVar('c_cicilan_masuk'),
                'c_cicilan_outstanding' => $this->request->getVar('c_cicilan_outstanding'),
                'c_total_biaya' => $this->request->getVar('c_total_biaya'),
                'c_biaya_masuk' => $this->request->getVar('c_biaya_masuk'),
                'c_biaya_outstanding' => $this->request->getVar('c_biaya_outstanding')
            ]);
            session()->setFlashdata('success', 'Data Berhasil Di Edit!');
            return redirect('admin/datatransaksi/cicilan');
        }
        echo view('admin/cicilanedit', $data);
    }
    public function deletecicilan($c_id)
    {
        $cicilan = new CicilanModels();
        $cicilan->delete($c_id);
        session()->setFlashdata('success', 'Data Berhasil Di Hapus!');
        return redirect('admin/datatransaksi/datacicilan');
    }
    public function ImportFileExcelcicilan()
    {
        $cicilan = new CicilanModels();
        $file = $this->request->getFile('file');
        $extension = $file->getClientExtension();
        if ($extension == 'xlsx' || $extension == 'xls') {
            if ($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($file);
            $kategori = $spreadsheet->getActiveSheet()->toArray();
            foreach ($kategori as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                $users = new UsersModels();
                $datausers = $users->findAll();
                foreach ($datausers as $setusers) {
                    if ($value[1] == $setusers['u_nama']) {
                        $user = $setusers['u_id'];
                    }
                }
                $paket = new PaketBarangModels();
                $datapaket = $paket->findAll();
                foreach ($datapaket as $setpaket) {
                    if ($value[2] == $setpaket['p_nama']) {
                        $pkt = $setpaket['p_id'];
                    }
                }
                $transaksi = new TransaksiModels();
                $datatransaksi = $transaksi->findAll();
                foreach ($datatransaksi as $settransaksi) {
                    if ($value[3] == $settransaksi['t_status']) {
                        $trk = $settransaksi['t_id'];
                    }
                }
                $payperiode = new PeriodePembayaranModels();
                $datapayperiode = $payperiode->findAll();
                foreach ($datapayperiode as $setpayperiode) {
                    if ($value[4] == $setpayperiode['pe_periode']) {
                        $periode = $setpayperiode['pe_id'];
                    }
                }
                $data = [
                    'c_id' => $value[0],
                    'u_id' => $user,
                    'p_id' => $pkt,
                    't_id' => $trk,
                    'pe_id' => $periode,
                    'c_total_cicilan' => $value[5],
                    'c_cicilan_masuk' => $value[6],
                    'c_cicilan_outstanding' => $value[7],
                    'c_total_biaya' => $value[8],
                    'c_biaya_masuk' => $value[9],
                    'c_biaya_outstanding' => $value[10],

                ];
                $cicilan->insert($data);
            }
            session()->setFlashdata('success', 'Data Berhasil Diimport!');
            return redirect('admin/datacicilan/datacicilan');
        } else {
            return redirect()->back()->with('message', 'Format File Tidak Sesuai! | Extension file harus .xls atau .xlsx');
        }
    }
    public function ExportDataExcelcicilan()
    {
        $cicilan = new CicilanModels();
        $datacicilan = $cicilan->findAll();
        $spreadsheet = new Spreadsheet();
        $colomheader = $spreadsheet->getActiveSheet();
        $colomheader->setCellValue('A1', 'No');
        $colomheader->setCellValue('B1', 'Nama Pengambil Paket');
        $colomheader->setCellValue('C1', 'Nama Paket');
        $colomheader->setCellValue('D1', 'Status Cicilan Paket');
        $colomheader->setCellValue('E1', 'Jumlah Periode Cicilan');
        $colomheader->setCellValue('F1', 'Jumlah Cicilan Masuk');
        $colomheader->setCellValue('G1', 'Jumlah Cicilan Outstanding');
        $colomheader->setCellValue('H1', 'Jumlah Total Biaya');
        $colomheader->setCellValue('I1', 'Jumlah Paket');
        $colomheader->setCellValue('J1', 'Jumlah Biaya Masuk');
        $colomheader->setCellValue('K1', 'Jumlah Biaya Outstanding');

        $users = new UsersModels();
        $datausers = $users->findAll();
        $paket = new PaketBarangModels();
        $datapaket = $paket->findAll();
        $transaksi = new TransaksiModels();
        $datatransaksi = $transaksi->findAll();
        $payperiode = new PeriodePembayaranModels();
        $datapayperiode = $payperiode->findAll();
        $colomdata = 2;
        foreach ($datacicilan as $setcicilan) {
            $colomheader->setCellValue('A' . $colomdata, ($colomdata - 1));
            foreach ($datausers as $setusers) {
                if ($setcicilan['u_id'] == $setusers['u_id']) {
                    $colomheader->setCellValue('B' . $colomdata, $setusers['u_nama']);
                }
            }
            foreach ($datapaket as $setpaket) {
                if ($setcicilan['p_id'] == $setpaket['p_id']) {
                    $colomheader->setCellValue('C' . $colomdata, $setpaket['p_nama']);
                }
            }
            foreach ($datatransaksi as $settransaksi) {
                if ($setcicilan['t_id'] == $settransaksi['t_id']) {
                    $colomheader->setCellValue('D' . $colomdata, $settransaksi['t_status']);
                }
            }
            foreach ($datapayperiode as $setpayperiode) {
                if ($setcicilan['pe_id'] == $setpayperiode['pe_id']) {
                    $colomheader->setCellValue('E' . $colomdata, $setpayperiode['pe_periode']);
                }
            }
            $colomheader->setCellValue('F' . $colomdata, $setcicilan['c_total_cicilan']);
            $colomheader->setCellValue('G' . $colomdata, $setcicilan['c_cicilan_masuk']);
            $colomheader->setCellValue('H' . $colomdata, $setcicilan['c_cicilan_outstanding']);
            $colomheader->setCellValue('I' . $colomdata, $setcicilan['c_total_biaya']);
            $colomheader->setCellValue('J' . $colomdata, $setcicilan['c_biaya_masuk']);
            $colomheader->setCellValue('K' . $colomdata, $setcicilan['c_biaya_outstanding']);
            $colomdata++;
        }
        $colomheader->getStyle('A1:K1')->getFont()->setBold(true);
        $colomheader->getStyle('A1:K1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $colomheader->getStyle('A1:K' . ($colomdata - 1))->applyFromArray($styleArray);

        $colomheader->getColumnDimension('A')->setAutoSize(true);
        $colomheader->getColumnDimension('B')->setAutoSize(true);
        $colomheader->getColumnDimension('C')->setAutoSize(true);
        $colomheader->getColumnDimension('D')->setAutoSize(true);
        $colomheader->getColumnDimension('E')->setAutoSize(true);
        $colomheader->getColumnDimension('F')->setAutoSize(true);
        $colomheader->getColumnDimension('G')->setAutoSize(true);
        $colomheader->getColumnDimension('H')->setAutoSize(true);
        $colomheader->getColumnDimension('I')->setAutoSize(true);
        $colomheader->getColumnDimension('J')->setAutoSize(true);
        $colomheader->getColumnDimension('K')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheet1.sheet');
        header('Content-Disposition: attachment;filename=Export-Data-Cicilan.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    //Data Barang Supplier
    public function logcicilan()
    {
        $logcicilan = new LogCicilanModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataKategoriPaket' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => '',
            'DataPackingBarang' => '',
            'MenuDataTransaksi' => 'menudatatransaksi',
            'DataPeriodeTransaksi' => '',
            'DataPaketBarang' => '',
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => 'datatransaksilogcicilan',
            'DataUser' => $logcicilan->datauser(),


        ];
        return view('admin/logcicilan', $data);
        // return view('register');
    }
    public function logcicilanprocess()
    {
        if (!$this->validate([
            'u_id' => 'required',
            'l_jumlah_bayar' => 'required',
            'l_foto' => [
                'rules' => 'uploaded[l_foto]|max_size[l_foto,1024]|mime_in[l_foto,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                'errors' => [
                    'uploaded' => '{field} Wajib diisi!',
                    'max_size' => 'Ukuran {field} Maksimal 1024 KB ',
                    'mime_in' => 'Format {field} harus JPG/JPEG/PNG!',
                ]
            ],
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }
        $l_jumlah_bayar = floatval(str_replace(",", "", $this->request->getVar('l_jumlah_bayar')));
        // Validasi nilai variabel
        if (!is_numeric($l_jumlah_bayar)) {
            echo "Input tidak valid!";
            exit;
        }
        $foto = $this->request->getFile('l_foto');
        $nama_file = $foto->getRandomName();
        $logcicilan = new LogCicilanModels();
        $logcicilan->insert([
            'u_id' => $this->request->getVar('u_id'),
            'c_id' => $this->request->getVar('c_id'),
            'l_jumlah_bayar' => $l_jumlah_bayar,
            'l_foto' => $nama_file
        ]);
        $foto->move('foto-bukti-pembayaran', $nama_file);
        session()->setFlashdata('success', 'Data Berhasil Disimpan!');
        return redirect()->to('/admin/datatransaksi/logcicilan');
    }
    public function listdatalogcicilan()
    {
        $logcicilan = new LogCicilanModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataKategoriPaket' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => '',
            'DataPackingBarang' => '',
            'MenuDataTransaksi' => 'menudatatransaksi',
            'DataPeriodeTransaksi' => '',
            'DataPaketBarang' => '',
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => 'datatransaksilogcicilan',
            'DataUser' => $logcicilan->datauser(),

        ];
        $data['tb_log_cicilan'] = $logcicilan->findAll();
        echo view('admin/datalogcicilan', $data);
        //berdasarkan login
        // $user = new UsersModels();
        // $data['tb_user'] = $user->where('u_referensi', session('u_id'))->findAll();
        // echo view('admin/datauser', $data);
    }
    public function editlogcicilan($l_id)
    {
        $logcicilan = new LogCicilanModels();
        $data = [
            'AdminDashboard' => '',
            'RegisterUser' => '',
            'RegisterSupplier' => '',
            'DataKategoriBarang' => '',
            'DataBarang' => '',
            'DataBarangSupplier' => '',
            'MenuDataBarang' => '',
            'DataPackingBarang' => '',
            'DataPaketBarang' => '',
            'DataKategoriPaket' => '',
            'MenuDataTransaksi' => 'menudatatransaksi',
            'DataPeriodeTransaksi' => '',
            'DataTransaksi' => '',
            'DataTransaksiCicilan' => '',
            'DataTransaksiLogCicilan' => 'datatransaksilogcicilan',
            'DataUser' => $logcicilan->datauser(),

        ];
        // ambil artikel yang akan diedit

        $data['tb_log_cicilan'] = $logcicilan->where('l_id', $l_id)->first();

        if ($this->validate([
            'u_id' => 'required',
            'l_jumlah_bayar' => 'required',
            'l_foto' => [
                'rules' => 'max_size[l_foto,1024]|mime_in[l_foto,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                'errors' => [
                    'uploaded' => '{field} Wajib diisi!',
                    'max_size' => 'Ukuran {field} Maksimal 1024 KB ',
                    'mime_in' => 'Format {field} harus JPG/JPEG/PNG!',
                ]
            ],
        ])) {
            $foto = $this->request->getFile('l_foto');
            $prefoto = $this->request->getVar('preview');
            if ($foto->getError() == 4) {
                $nama_file = $prefoto;
            } else {
                $nama_file = $foto->getRandomName();
                if ($prefoto != '') {
                    unlink('foto-bukti-pembayaran/' . $prefoto);
                }
                $foto->move('foto-bukti-pembayaran', $nama_file);
            }
            $logcicilan->update($l_id, [
                'u_id' => $this->request->getVar('u_id'),
                'l_jumlah_bayar' => $this->request->getVar('l_jumlah_bayar'),
                'l_approval_by' => session()->get('u_id'),
                'l_foto' => $nama_file
            ]);
            session()->setFlashdata('success', 'Data Berhasil Di Edit!');
            return redirect('admin/datatransaksi/datalogcicilan');
        } else {
            // session()->setFlashdata('error', $this->validator->listErrors());
        }
        echo view('admin/logcicilanedit', $data);
    }
    public function deletelogcicilan($l_id)
    {
        $logcicilan = new LogCicilanModels();
        $logcicilanfoto = $logcicilan->datalogcicilan($l_id);
        if ($logcicilanfoto['l_foto'] == '') {
        } else {
            unlink('foto-bukti-pembayaran/' . $logcicilanfoto['l_foto']);
        }
        $logcicilan->delete($l_id);
        session()->setFlashdata('success', 'Data Berhasil Di Hapus!');
        return redirect('admin/datatransaksi/datalogcicilan');
    }
    public function ImportFileExcellogcicilan()
    {
        $logcicilan = new LogCicilanModels();
        $file = $this->request->getFile('file');
        $extension = $file->getClientExtension();
        if ($extension == 'xlsx' || $extension == 'xls') {
            if ($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($file);
            $kategori = $spreadsheet->getActiveSheet()->toArray();
            foreach ($kategori as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                $users = new UsersModels();
                $datausers = $users->findAll();
                foreach ($datausers as $setusers) {
                    if ($value[1] == $setusers['u_nama']) {
                        $user = $setusers['u_id'];
                    }
                    if ($value[3] == $setusers['u_nama']) {
                        $approveuser = $setusers['u_id'];
                    }
                }

                $data = [
                    'l_id' => $value[0],
                    'u_id' => $user,
                    'l_jumlah_bayar' => $value[2],
                    'l_approval_by' => $approveuser,

                ];
                $logcicilan->insert($data);
            }
            session()->setFlashdata('success', 'Data Berhasil Diimport!');
            return redirect('admin/datatransaksi/datalogcicilan');
        } else {
            return redirect()->back()->with('message', 'Format File Tidak Sesuai! | Extension file harus .xls atau .xlsx');
        }
    }
    public function ExportDataExcellogcicilan()
    {
        $logcicilan = new logcicilanModels();
        $datalogcicilan = $logcicilan->findAll();
        $spreadsheet = new Spreadsheet();
        $colomheader = $spreadsheet->getActiveSheet();
        $colomheader->setCellValue('A1', 'No');
        $colomheader->setCellValue('B1', 'Nama Pengambil Paket');
        $colomheader->setCellValue('C1', 'Jumlah Bayar Cicilan');
        $colomheader->setCellValue('D1', 'Waktu Pembayaran Cicilan');
        $colomheader->setCellValue('E1', 'Pembayaran Cicilan Kepada');

        $users = new UsersModels();
        $datausers = $users->findAll();
        $colomdata = 2;
        foreach ($datalogcicilan as $setlogcicilan) {
            $colomheader->setCellValue('A' . $colomdata, ($colomdata - 1));
            foreach ($datausers as $setusers) {
                if ($setlogcicilan['u_id'] == $setusers['u_id']) {
                    $colomheader->setCellValue('B' . $colomdata, $setusers['u_nama']);
                }
                if ($setlogcicilan['l_approval_by'] == $setusers['u_id']) {
                    $colomheader->setCellValue('D' . $colomdata, $setusers['u_nama']);
                }
            }
            $colomheader->setCellValue('C' . $colomdata, $setlogcicilan['l_jumlah_bayar']);
            $colomheader->setCellValue('E' . $colomdata, $setlogcicilan['l_approval_date']);
            $colomdata++;
        }
        $colomheader->getStyle('A1:E1')->getFont()->setBold(true);
        $colomheader->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $colomheader->getStyle('A1:E' . ($colomdata - 1))->applyFromArray($styleArray);

        $colomheader->getColumnDimension('A')->setAutoSize(true);
        $colomheader->getColumnDimension('B')->setAutoSize(true);
        $colomheader->getColumnDimension('C')->setAutoSize(true);
        $colomheader->getColumnDimension('D')->setAutoSize(true);
        $colomheader->getColumnDimension('E')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheet1.sheet');
        header('Content-Disposition: attachment;filename=Export-Data-LogCicilan.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
}
