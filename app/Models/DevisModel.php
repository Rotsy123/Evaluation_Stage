<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class DevisModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'devis';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		"idclient",
        "idmaison",
        "idfinition",
        "datedevis",
        "debut",
		"lieu"
	];
	public $maison;
	public $finition;
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

	// public function insertData($id, $idclient, $idmaison, $idfinition, $datedevis, $debut, $lieu){
    //     $sql= "insert into devis (id, idclient, idmaison, idfinition, datedevis, debut, lieu) values (?, ?, ?, ?, ?, ?, ?)";
    //     $this->db->query($sql, [$id, $idclient, $idmaison, $idfinition, $datedevis, $debut, $lieu]);

    // }
	public function VerifierPaiement($devis, $prix){
		if($prix<0){
			throw new Exception("LE MONTANT DOIT ETRE POSITIF");
		}
		$prixtotal = (float) $this->getPrixFinitionParDevis($devis);

		// Obtenir le montant total déjà payé pour ce devis
		$sql = "SELECT total_paiements FROM v_montant_paye WHERE iddevis = ?";
		$resultat = $this->query($sql, [$devis])->getRow();
	
		// Si aucun paiement n'a encore été enregistré pour ce devis, initialiser à zéro
		$paye = $resultat ? (float) $resultat->total_paiements : 0;
	
		// Convertir le nouveau prix en nombre flottant
		$nouveauPrix = (float) $prix;
		// Vérifier si le montant total payé plus le nouveau montant dépasse le prix total du devis
		if ($paye + $nouveauPrix > $prixtotal) {
			// Si oui, lancer une exception
			throw new Exception("Le montant total payé dépasse le prix total du devis.");
		}
	
		// Sinon, retourner true pour indiquer que le paiement est autorisé
		return true;
	}
	
	public function getAnnee(){
		$sql = "select annee from v_statistique group by annee;";
		$resultats = $this->query($sql)->getResult();
		return $resultats;
	}
	public function Dostat(){
		$sql = "
			SELECT 
				SUM(montant_total) AS total,
				mois,
				annee 
			FROM 
				v_statistique
			GROUP BY 
				mois_int, mois,
				annee
			order by mois_int asc;
			";
		$resultats = $this->query($sql)->getResult();
		$mois = [];
		$total = [];
		if ($resultats) {
			foreach ($resultats as $resultat) {
				$mois[] = $resultat->mois; // Utilisez la notation -> pour accéder aux propriétés de l'objet
				$total[] = $resultat->total; // Utilisez la notation -> pour accéder aux propriétés de l'objet
			}
		}
	
		// Retournez un tableau associatif contenant les noms et les prix des objets
		return [
			'mois' => $mois,
			'total' => $total
		];
	
	}
	public function Histogramme($annee){
		$sql="
				SELECT 
				SUM(montant_total) AS total,
				mois,
				annee 
			FROM 
				v_statistique 
			WHERE annee = ?
			GROUP BY 
				mois_int, mois,
				annee
			order by mois_int asc;
		";

		$resultats = $this->query($sql,[$annee])->getResult();
		$mois = [];
		$total = [];
		if ($resultats) {
			foreach ($resultats as $resultat) {
				$mois[] = $resultat->mois; // Utilisez la notation -> pour accéder aux propriétés de l'objet
				$total[] = $resultat->total; // Utilisez la notation -> pour accéder aux propriétés de l'objet
			}
		}
	
		// Retournez un tableau associatif contenant les noms et les prix des objets
		return [
			'mois' => $mois,
			'total' => $total
		];
	
	}
	
	public function getAllDevis(){
        return $this->findAll();
    }

	//SANS FINITION
	public function getPrixAllDevis(){
		$sql = "select sum (prix_total_finition) as prix_total_finition from v_devise_prix_vrai";
		return  $this->query($sql)->getResult()[0]->prix_total_finition;
	}

	//avec devis
	public function getPrixAllDevisAvecFinition(){

	}

	public function getPrixDevisEnCours(){
		$sql = "select sum (prix_total_finition) as prix_total_finition from v_Encoursdeconstruction";
		return  $this->query($sql)->getResult()[0]->prix_total_finition;
	}

	//A REVOIR FA MANINA NO MAISON?
	// public function getSomme($idmaison){
	// 	$sql = "SELECT prix_total FROM v_devise_prix WHERE idmaison = ?";
	// 	$resultats = $this->query($sql, [$idmaison])->getResult();
		
	// 	if($resultats) {
	// 		// Récupérer le premier résultat
	// 		$premierResultat = $resultats[0];
			
	// 		// Accéder à la propriété prix_total du premier résultat
	// 		return $premierResultat->prix_total;
	// 	} else {
	// 		return null; // Ou une autre valeur par défaut si aucun résultat trouvé
	// 	}
	// }
	

	public function getSommePrixDevis($iddevis){
		$sql = "SELECT prix_total FROM v_devise_prix WHERE id_devis = ?";
		$result = $this->query($sql, [$iddevis])->getResult();
	
		if (!empty($result)) {
			// echo ($result[0]->prix_total);
			return $result[0]->prix_total;
		} else {
			// Gérer le cas où aucun résultat n'est retourné pour l'ID du devis
			return 0; // Ou tout autre valeur par défaut appropriée
		}
	}
	

	public function getPrixFinitionParDevis($devis){
		$finition = new FinitionModel();
		$sql = "SELECT idfinition FROM devis WHERE id = ?";
		$result = $this->query($sql, [$devis])->getResult();
		$resultsomme = $this->getSommePrixDevis($devis); 
		// var_dump($devis);
		if (!empty($result)) {
			$idfinition = $result[0]->idfinition;
	
			$pourcentage = $finition->getPourcentage($idfinition);
			$prix_finition = ($pourcentage * $resultsomme) / 100;
			$prix_finition = $prix_finition + $resultsomme;
	
			return $prix_finition;
		} else {
			// Gérer le cas où aucun résultat n'est retourné pour l'ID du devis
			return $resultsomme;
		}
	}
	
	// public function getPrixFinitionParDevis($devis){
	// 	$finition = new FinitionModel();
	// 	$sql = "select idfinition from devis where id = ?";
	// 	$idfinition = $this->query($sql, [$devis])->getResult()->idfinition;


	// 	$pourcentage = $finition->getPourcentage($idfinition);
	// 	$resultsomme = $this->getSommePrixDevis($devis); 
	// 	$prix_finition = ($pourcentage* $resultsomme)/100;
	// 	$prix_finition = $prix_finition + $resultsomme;

	// 	var_dump($prix_finition);
	// 	return $prix_finition;
	// }
	// public function getPrixFinition($idmaison,$idfinition){
	// 	$finition = new FinitionModel();
	// 	$pourcentage = $finition->getPourcentage($idfinition);

	// 	$resultsomme = $this->getSomme($idmaison); 
	// 	$prix_finition = ($pourcentage* $resultsomme)/100;
	// 	$prixtotal = $prix_finition + $resultsomme;
	// 	return $prixtotal;
	// }


	//ITO LE FONCTION AMPIANA DATE FIN SY DEBUT
	public function getDevisParClient($idclient){
		// Effectuer une jointure avec les tables maison et finition
		$devis = $this->select('devis.*, devis.id as iddevis, maison.id, maison.nom as nom_maison,devis.debut as debut, (devis.debut + (maison.duree_jours || \'days\')::interval)as fin, finition.*')
					->join('maison', 'maison.id = devis.idmaison')
					->join('finition', 'finition.id = devis.idfinition')
					->where('idclient', $idclient)
					->findAll();
	
		// Modifier chaque élément du tableau de devis pour inclure les détails de la maison et de la finition
		foreach ($devis as &$devi) {
			$devi['maison'] = [
				'id' => $devi['idmaison'],
				'nom' => $devi['nom_maison'],
				// Autres colonnes de la table maison
			];
	
			$devi['finition'] = [
				'id' => $devi['idfinition'],
				'nom' => $devi['nom'],
				// Autres colonnes de la table finition
			];

			$devi["date"] = [
				"debut" =>$devi["debut"],
				"fin" =>$devi["fin"],
			];
		}
	
		return $devis;
	}
	

    public function InsertionNouveauDevis($data){
        $this->save($data);
		$last = $this->db->query("SELECT id FROM devis ORDER BY id DESC LIMIT 1")->getRow()->id;
		// var_dump($last);
		$sql ="
		INSERT INTO historique_detailtravaux_devis (iddevis, iddetailtravaux, quantite, prix, idunite, idtravaux)
		SELECT devis.id, iddetailtravaux, quantite, prix, idunite, idtravaux
		FROM detailtravaux
		join devis on devis.idmaison = detailtravaux.idmaison
		where devis.id=?;
		";
		$this->db->query($sql, [$last]);
		$sqls = "
			insert into history_prix_devis values (?,?,?);
		";

		$prixsansfinition = $this->getSommePrixDevis($last);
		$prixavecfinition = $this->getPrixFinitionParDevis($last);

		$this->db->query($sqls, [$last, $prixavecfinition,$prixsansfinition]);	
    }

	public function GetPrixApresInsertionSansFinition($devis){
		$sql = "select prixsansdevis from history_prix_devis where iddevis = ?";
		$prix = $this->db->query($sql, $devis)->getResult()[0]->prixsansdevis;
		return $prix;
	}

	public function GetPrixApresInsertionAvecFinition($devis){
		$sql = "select prixavecfinition from history_prix_devis where iddevis = ?";
		$prix = $this->db->query($sql, $devis)->getResult()[0]->prixavecfinition;
		return $prix;
	}
	public function insertData($id, $idclient, $idmaison, $idfinition, $datedevis, $debut, $lieu){
        $sql= "insert into devis (id, idclient, idmaison, idfinition, datedevis, debut, lieu) values (?, ?, ?, ?, ?, ?, ?)";
        $this->db->query($sql, [$id, $idclient, $idmaison, $idfinition, $datedevis, $debut, $lieu]);
		$last = $id;
		$sql ="
			INSERT INTO historique_detailtravaux_devis (iddevis, iddetailtravaux, quantite, prix, idunite, idtravaux)
			SELECT devis.id, iddetailtravaux, quantite, prix, idunite, idtravaux
			FROM detailtravaux
			join devis on devis.idmaison = detailtravaux.idmaison
			where devis.id=?;
		";
		$this->db->query($sql, [$last]);

		$this->db->query($sql, [$last]);
		$sqls = "
			insert into history_prix_devis values (?,?,?);
		";

		$prixsansfinition = $this->getSommePrixDevis($last);
		$prixavecfinition = $this->getPrixFinitionParDevis($last);

		$this->db->query($sqls, [$last, $prixavecfinition,$prixsansfinition]);	

    }

	public function DevisEnCours(){
		$sql = "select * from v_Encoursdeconstruction";
		$result = $this->query($sql)->getResult();
		return $result;
	}
}