<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailTravauxModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'detailtravaux';
	protected $primaryKey           = 'iddetailtravaux';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		"quantite",
        "prix",
        "idunite",
        "idtravaux",
        "idmaison"
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

	public function getAllMaisons(){
        return $this->findAll();
    }

	public function getById($idmaison){
		return $this->where('id', $idmaison);
	}

    public function getDetailTravaux($iddevis){
		$sql = "Select historique_detailtravaux_devis.*, quantite*prix as total from historique_detailtravaux_devis where historique_detailtravaux_devis.iddevis = ?";
		$result = $this->query($sql, [$iddevis])->getResult();
		return $sql;
	}
}