<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModels;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class OwnerControllers extends BaseController
{
    public function __construct()
    {
        if (session()->get('u_role') != "owner") {
            echo 'Access denied';
            exit;
        }
    }
    public function index()
    {
        return view('owner/dashboard');
    }
    public function registeruser()
    {
        helper(['form', 'url']);
        $UsersModels = new UsersModels();
        $data['nameuser'] = $UsersModels->getuserreferensiowner();
        return view('owner/register', $data);
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
                'rules' => 'required|min_length[4]|max_length[100]',
                'errors' => [
                    'required' => '{field} Harus diisi',
                    'min_length' => '{field} Minimal 4 Karakter',
                    'max_length' => '{field} Maksimal 100 Karakter',
                ]
            ],
            'u_kota' => [
                'rules' => 'required|min_length[4]|max_length[100]',
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
        return redirect()->to('/owner/formsregister/registeruser');
    }
    public function listbrp()
    {
        $brps = new UsersModels();
        $data['brp'] = $brps->findAll();
        echo view('admin_list_brp', $data);
    }
    public function create()
    {
        // lakukan validasi
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_proyek' => 'required',
            'lokasi' => 'required',
            'pemerintah' => 'required',
            'swadaya' => 'required',
            'jumlah' => 'required',
            'pelaksanaan' => 'required',
            'manfaat' => 'required',
            'keterangan' => 'required'
        ]);
        $isDataValid = $validation->withRequest($this->request)->run();

        // jika data valid, simpan ke database
        if ($isDataValid) {
            $brps = new UsersModels();
            $brps->insert([
                "nama_proyek" => $this->request->getPost('nama_proyek'),
                "lokasi" => $this->request->getPost('lokasi'),
                "pemerintah" => $this->request->getPost('pemerintah'),
                "swadaya" => $this->request->getPost('swadaya'),
                "jumlah" => $this->request->getPost('jumlah'),
                "pelaksanaan" => $this->request->getPost('pelaksanaan'),
                "manfaat" => $this->request->getPost('manfaat'),
                "keterangan" => $this->request->getPost('keterangan')
            ]);
            return redirect('admin/BRP');
        }

        // tampilkan form create
        echo view('admin_create_brp');
    }

    //--------------------------------------------------------------------------

    public function edit($no)
    {
        // ambil artikel yang akan diedit
        $brps = new UsersModels();
        $data['brp'] = $brps->where('no', $no)->first();

        // lakukan validasi data artikel
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_proyek' => 'required',
            'lokasi' => 'required',
            'pemerintah' => 'required',
            'swadaya' => 'required',
            'jumlah' => 'required',
            'pelaksanaan' => 'required',
            'manfaat' => 'required',
            'keterangan' => 'required'
        ]);
        $isDataValid = $validation->withRequest($this->request)->run();
        // jika data vlid, maka simpan ke database
        if ($isDataValid) {
            $brps->update($no, [
                "nama_proyek" => $this->request->getPost('nama_proyek'),
                "lokasi" => $this->request->getPost('lokasi'),
                "pemerintah" => $this->request->getPost('pemerintah'),
                "swadaya" => $this->request->getPost('swadaya'),
                "jumlah" => $this->request->getPost('jumlah'),
                "pelaksanaan" => $this->request->getPost('pelaksanaan'),
                "manfaat" => $this->request->getPost('manfaat'),
                "keterangan" => $this->request->getPost('keterangan')
            ]);
            return redirect('admin/BRP');
        }

        // tampilkan form edit
        echo view('admin_edit_brp', $data);
    }

    //--------------------------------------------------------------------------

    public function delete($no)
    {
        $brps = new UsersModels();
        $brps->delete($no);
        return redirect('admin/BRP');
    }

    public function ImportFileExcel()
    {
        $brp = new UsersModels();
        $file = $this->request->getFile('file');
        $extension = $file->getClientExtension();
        if ($extension == 'xlsx' || $extension == 'xls') {
            if ($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $spreadsheet = $reader->load($file);
            $brps = $spreadsheet->getActiveSheet()->toArray();
            foreach ($brps as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                $data = [
                    'no' => $value[0],
                    'nama_proyek' => $value[1],
                    'lokasi' => $value[2],
                    'pemerintah' => $value[3],
                    'swadaya' => $value[4],
                    'jumlah' => $value[5],
                    'pelaksanaan' => $value[6],
                    'manfaat' => $value[7],
                    'keterangan' => $value[8],

                ];
                $brp->insert($data);
            }
        } else {
            return redirect()->back()->with('error', 'Format File Tidak Sesuai');
        }
        return redirect('admin/BRP');
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
