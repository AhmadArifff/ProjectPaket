<?php

namespace App\Models;

use CodeIgniter\Model;

class CicilanModels extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tb_cicilan';
    protected $primaryKey           = 'c_id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDelete        = false;
    protected $protectFields        = true;
    protected $allowedFields        = [
        "u_id",
        "p_id",
        "t_id",
        "pe_id",
        "c_total_cicilan",
        "c_cicilan_masuk",
        "c_cicilan_outstanding",
        "c_total_biaya",
        "c_biaya_masuk",
        "c_biaya_outstanding"
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
    public function datapaket()
    {
        return $this->db->table('tb_paket')->Get()->getResultArray();
    }
    public function dataperiode()
    {
        return $this->db->table('tb_pay_periode')->Get()->getResultArray();
    }
    public function datatransaksi()
    {
        return $this->db->table('tb_transaksi')->Get()->getResultArray();
    }
    public function datauser()
    {
        $query = $this->db->query('SELECT * FROM `tb_user` WHERE u_role = "coordinator" OR u_role = "owner"OR u_role = "anggota"');
        return $query->getResultArray();
    }
    public function paketcicilan($u_id)
    {
        return $this->db->table('tb_transaksi')->where('u_id', $u_id)->Get()->getResultArray();
    }
    public function get_cicilan($u_id)
    {
        return $this->db->table('tb_cicilan')->where('u_id', $u_id)->Get()->getResultArray();
    }
}
