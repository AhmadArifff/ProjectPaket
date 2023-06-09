<?php

namespace App\Models;

use CodeIgniter\Model;

class LogCicilanModels extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tb_log_cicilan';
    protected $primaryKey           = 'l_id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDelete        = false;
    protected $protectFields        = true;
    protected $allowedFields        = [
        "u_id",
        "c_id",
        "l_jumlah_bayar",
        "l_approval_by",
        "l_approval_date",
        "l_foto"
    ];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'l_approval_date';
    protected $updatedField         = 'l_approval_date';
    protected $deletedField         = 'l_approval_date';

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
    public function datauser()
    {
        $query = $this->db->query('SELECT * FROM `tb_user` WHERE u_role = "coordinator" OR u_role = "owner"OR u_role = "anggota"');
        return $query->getResultArray();
    }
    public function datalogcicilan($l_id)
    {
        return $this->db->table('tb_log_cicilan')->where('l_id', $l_id)->Get()->getRowArray();
    }
}
