<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModels extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tb_transaksi';
    protected $primaryKey           = 't_id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDelete        = false;
    protected $protectFields        = true;
    protected $allowedFields        = [
        "u_id",
        "p_id",
        "t_qty",
        "t_totalharga",
        "waktu",
        "t_approval_by",
        "t_status",
        "u_id"
    ];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'waktu';
    protected $updatedField         = 'waktu';
    protected $deletedField         = 'waktu';

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
    public function datauser()
    {
        $query = $this->db->query('SELECT * FROM `tb_user` WHERE u_role = "coordinator" OR u_role = "owner"OR u_role = "anggota"');
        return $query->getResultArray();
    }
    public function HargaPaket($p_id)
    {
        return $this->db->table('tb_paket')->where('p_id', $p_id)->Get()->getResultArray();
    }
    public function get_transaksi_paket_by_idi($field, $id)
    {
        return $this->where($field, $id)->findAll();
    }
}
