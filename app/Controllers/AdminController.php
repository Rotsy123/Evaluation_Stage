<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DetailTravauxModel;
use App\Models\DevisModel;
use App\Models\FinitionModel;
use App\Models\Formater;
use App\Models\MaisonModel;
use App\Models\PaiementModel;
use App\Models\TravauxModel;
use App\Models\TypePaiement;
use App\Models\UniteModel;



use Dompdf\Dompdf;
use Dompdf\Options;

class AdminController extends BaseController
{
    public function __construct()
    {
        $session = \Config\Services::session();
        // if(!$this->session->id){
        // if (!$session->has('id')) {

        //     echo "ACCESS DENIED";
        //     exit;
        // }
        
    }

    public function reinitialiserBase(){
        $Formater = new Formater();
        $Formater->reinitialiserBase();
    }
    public function listeEncours(){
        $devis = new DevisModel();
        $devisencours = $devis->DevisEnCours();
        $data = [

            'listedevis' => $devisencours
        ];
        return view('listeEnCours',$data);
    }

    public function AfficherDetail(){
        $devis = new DevisModel();
        
        // $maisonid = $this->request->getPost("maison");
        // $finitionid = $this->request->getPost("finition");
        // $prixfinition = $devis->getPrixFinition($maisonid, $finitionid);
        // $prix = $devis->getSomme($maisonid);
        
        // $travail = new TravauxModel();
        // $travaux = $travail->getHierarchie($maisonid);
        // $data=[
        //     'hierarchie' => $travaux,
        //     'finition' => $prixfinition,
        //     'prix' => $prix
        // ]; 


        $devis = new DevisModel();
        
        $devisid = $this->request->getPost("devis");

        $prixfinition = $devis->GetPrixApresInsertionAvecFinition($devisid);
        $prix = $devis->GetPrixApresInsertionSansFinition($devisid);
        $travail = new TravauxModel();

        $travaux = $travail->getHierarchie($devisid);
        $data=[
            'hierarchie' => $travaux,
            'finition' => $prixfinition,
            'prix' => $prix
        ];
        return view('listeDetailEnCours',$data);
    }

    

    public function statistique(){
        $devisemodel = new DevisModel();
        $paiementmodel = new PaiementModel();

        $paiement = $paiementmodel->getPaiementEffectue();
        $annee = $devisemodel->getAnnee();
        $prixall = $devisemodel->getPrixAllDevis();
        $prixencours = $devisemodel->getPrixDevisEnCours();

        $data['annee'] = $annee;
        $data['prixall'] = $prixall;
        $data['prixencours'] = $prixencours;
        $data['paiement'] = $paiement;

        $resultats = $devisemodel->Dostat();
        $data['mois'] = $resultats['mois'];
        $data['total'] = $resultats['total'];
        if($this->request->getMethod() == 'post'){
            if($this->request->getPost('annee')==0){
                $resultats = $devisemodel->Dostat();
                $data['mois'] = $resultats['mois'];
                $data['total'] = $resultats['total'];
                return view('stat',$data);
            }
        }
        if ($this->request->getMethod() == 'post') {
            $annee = intval($this->request->getPost('annee')); 
            var_dump($annee);

            $resultats= $devisemodel->Histogramme($annee); 
            $data['mois'] = $resultats['mois'];
            $data['total'] = $resultats['total'];
            return view('stat', $data);
        }
        return view('stat', $data);
    }

    public function getListTravaux(){
        $travaux = new TravauxModel();
        $data= [
            "travaux" => $travaux->getAll()
        ];
        return view ('ListeTravaux', $data);
    }
    public function mettreajour(){
        $travaux = json_decode(urldecode($this->request->getPost('travaux')), true);
        $maisonModel = new MaisonModel();
        $finitionModel = new FinitionModel();
        $unitemodel = new UniteModel();

        $unite = $unitemodel->findAll();

        $maison = $maisonModel->findAll();
        $finition = $finitionModel->findAll();
        $data=[
            "travaux"=>$travaux,
            "unite" =>$unite
        ]; 
        return view('modificationTravaux', $data);
    }

    public function mettreAJourTravaux(){
        $detailtravaux = new DetailTravauxModel();
        $donnees = $this->request->getPost();
        
        // Récupérer les données du formulaire
        $idDetailTravaux = $donnees['id']; // Récupérer l'ID du détail des travaux
        $quantite = $donnees['quantite'];
        $prix = $donnees['prix'];
        $idUnite = $donnees['unite'];
    
        // Mettre à jour le détail des travaux
        $detailtravaux->update($idDetailTravaux, [
            'quantite' => $quantite,
            'prix' => $prix,
            'idunite' => $idUnite
        ]);
    
        // Rediriger vers une autre page après la mise à jour
        return redirect()->to(site_url('getListTravaux'));
    }

    public function getListFinition(){
        $finitionmodel = new FinitionModel();
        $finition = $finitionmodel->findAll();
        $data= [
            "finition"=>$finition
        ];
        return view("ListeFinition", $data); 
    }
    
    public function mettreajourfinition(){
        $finition = json_decode(urldecode($this->request->getPost('fin')), true);
        $data=[
            "finition"=>$finition
        ];
        return view ("ModifierFinition",$data);
    }
    

    public function updatefinition(){
        $finition = new FinitionModel();
        $donnees = $this->request->getPost();
        
        // Récupérer les données du formulaire
        $idDetailTravaux = $donnees['id']; // Récupérer l'ID du détail des travaux
        $nom = $donnees['nom'];
        $pourcentage = $donnees['pourcentage'];

        if ($pourcentage < 0 || $pourcentage > 100) {
            // Pourcentage invalide, renvoyer un message d'erreur à l'utilisateur
            return redirect()->back()->withInput()->with('error', 'Le pourcentage doit être compris entre 0 et 100.');
        }
        
        // Mettre à jour le détail des travaux
        $finition->update($idDetailTravaux, [
            'nom' => $nom,
            'pourcentage' => $pourcentage
        ]);
    
        // Rediriger vers une autre page après la mise à jour
        return redirect()->to(site_url('getListFinition'));
    }

    public function uploadFiles(){

    }
}