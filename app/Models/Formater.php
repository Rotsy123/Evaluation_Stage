<?php

namespace App\Models;

use CodeIgniter\Model;

class Formater extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Méthode pour supprimer toutes les entrées de la table

    public function deleteAllUsers()
{
    $this->db->query('DELETE FROM tbl_users'); // Supprime toutes les lignes de la table tbl_users
}


    // Méthode pour réinitialiser la séquence
    public function resetSequence()
    {
        $this->db->query("ALTER SEQUENCE seqUser RESTART WITH 1"); // Réinitialise la séquence
    }

    public function reinitialiserBase(){
        $this->db->query("delete from historique_detailtravaux_devis;
        delete from detailstravaux_history;
        delete from paiementdevis;
        delete from historiquepaiement;
        delete from paiement;
        delete from typepaiement;
        delete from history_prix_devis;
        delete from devis;
        delete from detailtravaux;
        delete from finition;
        delete from maison;
        ALTER SEQUENCE seqMaison RESTART WITH 1;
    
        delete from travaux;
        delete from unite;
        delete from client;");
    }
}