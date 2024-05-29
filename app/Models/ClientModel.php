<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'client';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		"phone"
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

	public function Insertion($phonenumber)
	{
        $client = $this->where('phone', $phonenumber)->first();
        if(!$client){
            $this->insert([
                'phone' => $phonenumber
            ]); 
			$client = $this->orderBy('id', 'DESC')->first();
			$idclient  = $client['id'];
        }else{
			$idclient = $client['id'];
		}
		return $idclient;
	}	
}