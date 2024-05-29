<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DevisModel;
use App\Models\FinitionModel;
use App\Models\MaisonModel;
use App\Models\PaiementModel;
use App\Models\TravauxModel;
use App\Models\TypePaiement;


use Dompdf\Dompdf;
use Dompdf\Options;

class ClientController extends BaseController
{
    public function __construct()
    {
        $session = \Config\Services::session();

    // Vérifier si la clé 'idclient' existe dans la session
        if (!$session->has('idclient')) {
            echo 'Access denied';
            exit;
        }
    }
    public function index()
    {
        $maisons = new MaisonModel();
        $finitions = new FinitionModel();

        $allmaisons = $maisons->getAllMaisons();
        $allfinitions = $finitions->getAllFinitions();

        $data = [
            "maisons" => $allmaisons,
            "finitions" => $allfinitions
        ];
        return view("insertDevis", $data);
    }
    public function insertDevis(){
        $client = $_SESSION['idclient'];
        
        $newData = [
            'idclient' => $client,
            'idmaison' => $this->request->getVar('maisons'),
            'idfinition' => $this->request->getVar('finitions'),
            'debut' => $this->request->getVar('debut'),
            'lieu' =>$this->request->getVar('lieu')
        ];
        $devis = new DevisModel();
        $devis->InsertionNouveauDevis($newData);
        return redirect()->to("listeDevis");
    }
    
    public function listeDevis(){
        $client = $_SESSION['idclient'];
        $devismodel = new DevisModel();
        $listedevis = $devismodel->getDevisParClient($client);
        $data = [
            "listedevis" => $listedevis
        ];
        return view('listeDevisClient',$data);        
    }

    public function getSousDevis(){
        $devis = new DevisModel();
        $paiement = new PaiementModel();
        $devisid = $this->request->getPost("devis");
        $finitionid = $this->request->getPost("finition");

        $prixfinition = $devis->GetPrixApresInsertionAvecFinition($devisid);
        $prix = $devis->GetPrixApresInsertionSansFinition($devisid);
        $travail = new TravauxModel();

        $listepaiement = $paiement->getPaiementParDevis($devisid);
        $total = $paiement->getSommePayer($devisid);

        $travaux = $travail->getHierarchie($devisid);
        // var_dump($travaux);
        $data=[
            'hierarchie' => $travaux,
            'finition' => $prixfinition,
            'prix' => $prix,
            'listepaiement' => $listepaiement,
            'total' => $total
        ];
        $options = new Options();
        $options->set('isRemoteEnable', true);
        $dompdf = new Dompdf($options); 
        $html = view('listeDevisDetail',$data);
        $dompdf->loadHtml($html);

        $dompdf->render();
        $filename ='test.pdf';

        $dompdf->stream($filename);

    }

    public function ChoisirPaiement(){
        
        $devis = $this->request->getPost("devis");
        
        $type = new TypePaiement();
        $allpaiement = $type->getAll();
        $data = [
            'type'=>$allpaiement,
            'devis' =>$devis
        ];
        
        return view('typepaiement',$data);
    }
    // public function validerchoix(){
    //     $idtype = $this->request->getPost('type');
    //     $texts = $this->request->getPost('text');
    //     $dates = $this->request->getPost('date');

    //     $db = \Config\Database::connect(); // Connecter à la base de données
    //     $builder = $db->table('paiementdevis'); // Sélectionner la table

    //     $iddevis = $this->request->getPost('devis');
    //     $data=[
    //         "idtype"=>$idtype,
    //         "iddevis"=> $iddevis
    //     ];
    //     //RAHA MBOLA TSY MI-EXISTE NO ATSOFOKA
    //     $builder->insert($data);
        

       

    //     $builders = $db->table('paiement'); // Sélectionner la table
    //     // Pour afficher les valeurs, vous pouvez les parcourir et les afficher
    //     for($i=0; $i<count($texts); $i++) {
    //         $datapaiement = [
    //             "datepaiement" => $dates[$i],
    //             "a_paye" => $texts[$i],
    //             "iddevis" => $iddevis
    //         ];
    //         $builders->insert($datapaiement);
    //     }
        
    // }

    public function validerchoix()
{
    $devis = new DevisModel();
    $texts = $this->request->getPost('texts');
    $dates = $this->request->getPost('dates');
    $iddevis = $this->request->getPost('devis');

    $db = \Config\Database::connect(); // Connexion à la base de données
    $builders = $db->table('paiement');
    $validationErrors = [];
    
    // Valider les données
    // foreach ($texts as $key => $text) {
        try {
            $result = $devis->VerifierPaiement($iddevis, $texts);
            
            if ($result !== true) {
                
                $validationErrors[] = 'Le prix est trop élevé, il vous reste à payer: ' . $result;
            }else{
                $datapaiement = [
                    "datepaiement" => $dates,
                    "a_paye" => $texts,
                    "iddevis" => $iddevis
                ];
                $builders->insert($datapaiement);
                    return redirect()->to("listeDevis");
                
            }
        } catch (\Exception $e) {
            $validationErrors[] = 'Une erreur s\'est produite lors de la vérification du paiement : ' . $e->getMessage();
        }
    // }

    // Si des erreurs de validation sont détectées, les renvoyer
    if (!empty($validationErrors)) {
        return $this->response->setJSON(['success' => false, 'errors' => $validationErrors]);
    }

    // Si les données sont valides, renvoyer une réponse de succès
    return $this->response->setJSON(['success' => true]);
}

    // public function validerchoix() {
    //     $devis = new DevisModel();
    //     $idtype = $this->request->getPost('type');
    //     $texts = $this->request->getPost('text');
    //     $dates = $this->request->getPost('date');
        
    //     $db = \Config\Database::connect(); // Connexion à la base de données
    //     // $builder = $db->table('paiementdevis'); // Sélectionner la table
        
        
    //     $iddevis = $this->request->getPost('devis');
    //     // Vérifier si une entrée existe déjà
    //     // $existingEntry = $builder->where('idtype', $idtype)
    //     //                          ->where('iddevis', $iddevis)
    //     //                          ->get()
    //     //                          ->getRow();
    
    //     // if (!$existingEntry) {
    //     //     // Si aucune entrée correspondante n'existe, insérer les données dans la table
    //     //     $data = [
    //     //         "idtype" => $idtype,
    //     //         "iddevis" => $iddevis
    //     //     ];
    //     //     var_dump($data);
    //     //     $builder->insert($data);
    //     // }else{
    //     //     var_dump($iddevis);
    //     // }
    
    //     // Insérer les données dans la table paiement
    //     $builders = $db->table('paiement'); // Sélectionner la table
    //     // Parcourir et insérer les données
    //     for ($i = 0; $i < count($texts); $i++) {
    //         try{

    //             $result = $devis->VerifierPaiement($iddevis, $texts[$i]);
                

    //             if($result === true) {
    //                 $datapaiement = [
    //                     "datepaiement" => $dates[$i],
    //                     "a_paye" => $texts[$i],
    //                     "iddevis" => $iddevis
    //                 ];
    //                 $builders->insert($datapaiement);
    //                 return redirect()->to("listeDevis");
                    
    //             }else{
    //                 return redirect()->back()->withInput()->with('error', 'Le prix est trop eleve, il vous reste a paye:'. $result);

    //             }


    //         }catch(\Exception $e){
    //             echo $e->getMessage();
    //             // return redirect()->back()->withInput()->with('error', 'Une erreur s\'est produite lors de la vérification du paiement : ' );
    //         }  
    //     }
    // }
    // public function Login(){
    //     helper(['form']);

    //     // Définir les règles de validation
    //     $rules = [
    //         'telephone' => [
    //             'rules' => 'required|callback_validate_phone_number',
    //             'errors' => [
    //                 'required' => 'Le numéro de téléphone est obligatoire.',
    //             ],
    //         ],
    //     ];

    //     if ($this->request->getMethod() == 'post' && $this->validate($rules)) {
    //         // La validation a réussi, traiter le formulaire (ex. authentification)
    //         echo "Validation réussie!";
    //     } else {
    //         // La validation a échoué, renvoyer l'utilisateur au formulaire de connexion avec les erreurs
    //         $data['validation'] = $this->validator;
    //         echo view('login_form', $data);
    //     }
    // }

    // public function validate_phone_number($phone) {
    //     // Vérifier si la longueur est de 10 caractères
    //     if (strlen($phone) != 10) {
    //         return 'Le numéro de téléphone doit contenir exactement 10 caractères.';
    //     }

    //     // Vérifier si les 3 premiers caractères sont valides
    //     $prefix = substr($phone, 0, 3);
    //     if (!in_array($prefix, ['034', '032', '038', '033'])) {
    //         return 'Le préfixe du numéro de téléphone doit être 034, 032, 038 ou 033.';
    //     }

    //     // Vérifier si les 7 caractères suivants sont des chiffres
    //     $remaining_digits = substr($phone, 3);
    //     if (!ctype_digit($remaining_digits)) {
    //         return 'Les 7 derniers caractères du numéro de téléphone doivent être des chiffres.';
    //     }

    //     // Toutes les validations ont réussi
    //     return TRUE;
    // }
    
}