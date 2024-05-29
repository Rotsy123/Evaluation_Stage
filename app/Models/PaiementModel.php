<?php

namespace App\Models;

use CodeIgniter\Model;

class PaiementModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'paiement';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		"datepaiement",
        "a_paye",
        "iddevis"
	];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
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

	public function choixpaiement($data){
        $this->save($data);
    }

	public function getPaiementEffectue(){
		$sql = "SELECT sum(total_paiements) as total FROM V_montant_paye";
		$paiement = $this->query($sql)->getResult()[0]->total;
		return $paiement;
	}

	public function insertData($idpaiement, $datepaiement, $a_paye, $iddevis){
        $sql= "insert into paiement (id, datepaiement, a_paye, iddevis) values (?,?, ?,?)";
        $this->db->query($sql, [$idpaiement, $datepaiement, $a_paye, $iddevis]);

    }

	public function getPaiementParDevis($iddevis){
		$sql = "SELECT * from paiement where iddevis = ?";
		return $this->db->query($sql,[$iddevis])->getResult();
	}

	public function getSommePayer($iddevis){
		$sql = "SELECT sum(a_paye) as total from paiement where iddevis = ?";
		return $this->db->query($sql,[$iddevis])->getResult()[0]->total;
	}
}