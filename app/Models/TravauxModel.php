<?php

namespace App\Models;

use CodeIgniter\Model;

class TravauxModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'travaux';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		"nom",
        "idtravaux_mere"
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

    public function insertData($id, $nom){
        $sql= "insert into travaux (id, nom) values (?, ?)";
        $this->db->query($sql, [$id,$nom]);

    }
	public function getAllMaisons(){
        return $this->findAll();
    }

	public function getById($idmaison){
		return $this->where('id', $idmaison);
	}

//     public function getHierarchie($idmaison)
// {
//     // $sql = "
//     //       WITH RECURSIVE TravauxHierarchy AS (
//     //             SELECT t.id as id, t.nom, t.idTravaux_mere, 0 as level, dt.quantite, dt.prix, dt.idmaison, m.nom AS nom_maison, u.nom as nom_unite
//     //             FROM Travaux t
//     //             LEFT JOIN detailtravaux dt ON t.id = dt.idtravaux
//     //             LEFT JOIN maison m ON m.id = dt.idmaison
//     //             LEFT JOIN unite u on u.id = dt.idunite
//     //             WHERE m.id = ?
//     //             UNION ALL
//     //             SELECT t.id, t.nom, t.idTravaux_mere, th.level + 1, dt.quantite, dt.prix, dt.idmaison, m.nom AS nom_maison, u.nom as nom_unite
//     //             FROM Travaux t
//     //             JOIN TravauxHierarchy th ON t.id = th.idTravaux_mere
//     //             LEFT JOIN detailtravaux dt ON t.id = dt.idtravaux
//     //             LEFT JOIN maison m ON m.id = dt.idmaison
//     //             LEFT JOIN unite u on u.id = dt.idunite

//     //         )
//     //         SELECT DISTINCT 
//     //         th.*, 
//     //         t2.nom AS nom_travail_mere
//     //                 FROM TravauxHierarchy th
//     //                 LEFT JOIN Travaux t2 ON th.idTravaux_mere = t2.id
//     //                 order by th.id asc;
                
//     // ";
//     $sql="
//     WITH RECURSIVE TravauxHierarchy AS (
//         SELECT 
//             t.id as id, 
//             t.nom, 
//             t.idTravaux_mere, 
//             0 as level, 
//             SUM(dt.quantite * dt.prix) as prix_total, 
//             CASE 
//                 WHEN t.idTravaux_mere IS NULL THEN SUM(dt.quantite * dt.prix)
//                 ELSE NULL
//             END as prix, 
//             dt.quantite, 
//             dt.prix as prix_unitaire, 
//             dt.idmaison, 
//             m.nom AS nom_maison, 
//             u.nom as nom_unite
//         FROM 
//             Travaux t
//         LEFT JOIN 
//             detailtravaux dt ON t.id = dt.idtravaux
//         LEFT JOIN 
//             maison m ON m.id = dt.idmaison
//         LEFT JOIN 
//             unite u on u.id = dt.idunite
//         WHERE 
//             m.id = ?
//         GROUP BY 
//             t.id, t.nom, t.idTravaux_mere, dt.quantite, dt.prix, dt.idmaison, m.nom, u.nom
            
//         UNION ALL
        
//         SELECT 
//             t.id, 
//             t.nom, 
//             t.idTravaux_mere, 
//             th.level + 1, 
//             th.prix_total,
//             NULL,
//             dt.quantite, 
//             dt.prix as prix_unitaire, 
//             dt.idmaison, 
//             m.nom AS nom_maison, 
//             u.nom as nom_unite
//         FROM 
//             Travaux t
//         JOIN 
//             TravauxHierarchy th ON t.id = th.idTravaux_mere
//         LEFT JOIN 
//             detailtravaux dt ON t.id = dt.idtravaux
//         LEFT JOIN 
//             maison m ON m.id = dt.idmaison
//         LEFT JOIN 
//             unite u on u.id = dt.idunite
//         )
        
//         SELECT 
//             th.id, 
//             th.nom, 
//             th.idTravaux_mere, 
//             th.level, 
//             SUM(th.prix_total) as prix_total,
//             th.prix,
//             th.quantite, 
//             th.prix_unitaire, 
//             th.idmaison, 
//             th.nom_maison, 
//             th.nom_unite,
//             t2.nom AS nom_travail_mere
//         FROM 
//             TravauxHierarchy th
//         LEFT JOIN 
//             Travaux t2 ON th.idTravaux_mere = t2.id
//         GROUP BY 
//             th.id, th.nom, th.idTravaux_mere, th.level, th.prix, th.quantite, th.prix_unitaire, th.idmaison, th.nom_maison, th.nom_unite, t2.nom
//         ORDER BY 
//             th.id ASC;
        
//     ";
//     $result = $this->query($sql, [$idmaison])->getResult();
    
//     // Retourner les résultats
//     return $result;
// }

    public function getHierarchie($iddevise){
        $sql = "select * from v_detailtravaux where iddevis = ?";
        $resultat = $this->query($sql,[$iddevise])->getResult();
        return $resultat;
    }
    public function getAll(){
        $sql = "select * from v_travaux_lib";
        // $sql = "select * from travaux";

        $resultat = $this->query($sql)->getResult();
        return $resultat;
    }

}