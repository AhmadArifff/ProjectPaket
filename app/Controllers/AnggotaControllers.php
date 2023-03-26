<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModels;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class AnggotaControllers extends BaseController
{
    public function __construct()
    {
        if (session()->get('u_role') != "anggota") {
            echo 'Access denied';
            exit;
        }
    }
    public function index()
    {
        return view('anggota/dashboard');
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
}
