<?php

namespace App\Models;

use CodeIgniter\Model;

class PaketBarangModels extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tb_paket';
    protected $primaryKey           = 'p_id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDelete        = false;
    protected $protectFields        = true;
    protected $allowedFields        = [
        "kp_id",
        "pe_id",
        "p_hargaJual",
        "p_hargaBarang",
        "pa_id",
        "p_cashback",
        "p_insentive",
        "p_laba",
        "p_persentaseLaba",
        "p_barang",
        "u_id"
    ];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'created_at';
    protected $deletedField         = 'created_at';

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
    public function datapackagingbarang()
    {
        return $this->db->table('tb_packaging')->Get()->getResultArray();
    }
    public function datapayperiode()
    {
        return $this->db->table('tb_pay_periode')->Get()->getResultArray();
    }
    public function datapaketbarang()
    {
        return $this->db->table('tb_paket')->Get()->getResultArray();
    }
    public function databarangsupplier()
    {
        return $this->db->table('tb_sup_barang')->Get()->getResultArray();
    }
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
    public function datapengambilanpaket()
    {
        return $this->db->table('tb_pengambilan_paket')->Get()->getResultArray();
    }
    public function get_pengambilan_paket_by_id($p_id)
    {
        $query = $this->db->query("SELECT * FROM tb_paket INNER JOIN tb_pengambilan_paket ON tb_paket.p_id = tb_pengambilan_paket.pp_p_id INNER JOIN tb_sup_barang ON tb_pengambilan_paket.p_sb_id = tb_sup_barang.sb_id WHERE tb_paket.p_id IN ($p_id)");
        return $query->getResult();
    }
    public function get_paket_haga_by_id($p_id)
    {
        $query = $this->db->query("SELECT (tb_paket.p_hargaJual - tb_paket.p_hargaBarang - tb_paket.p_cashback) AS p_persentaseLaba FROM tb_paket WHERE tb_paket.p_id = $p_id ");
        return $query->getResult();
    }
    public function datakategoripaket()
    {
        return $this->db->table('tb_kategori_paket')->Get()->getResultArray();
    }
}
