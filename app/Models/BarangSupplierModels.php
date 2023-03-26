<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangSupplierModels extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tb_sup_barang';
    protected $primaryKey           = 'sb_id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDelete        = false;
    protected $protectFields        = true;
    protected $allowedFields        = [
        "s_id",
        "b_id",
        "sb_hargaasli",
        "sb_hargajual",
        "sb_qty",
        "sb_berat/ukuran",
        "sb_ktrg_berat/ukuran",
        "sb_foto",
        "u_id"
    ];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'u_created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks       = true;
    protected $beforeInsert         = [];
    protected $afterInsert          = [];
    protected $beforeUpdate         = [];
    protected $afterUpdate          = [];
    protected $beforeFind           = [];
    protected $afterFind            = [];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];
    public function datakategoribarang()
    {
        return $this->db->table('tb_kategori')->Get()->getResultArray();
    }
    public function databarang()
    {
        return $this->db->table('tb_barang')->Get()->getResultArray();
    }
    public function datasupplier()
    {
        return $this->db->table('tb_supplier')->Get()->getResultArray();
    }
    public function databarangsupplier($sb_id)
    {
        return $this->db->table('tb_sup_barang')->where('sb_id', $sb_id)->Get()->getRowArray();
    }
}
