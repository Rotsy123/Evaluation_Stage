<?php

namespace App\Models;

use CodeIgniter\Model;

class TypePaiement extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'typepaiement';
	protected $primaryKey           = 'idtype';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		"nom"
	];

    public function getAll(){
        return $this->findAll();
    }
	

}