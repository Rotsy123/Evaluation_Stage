<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\DetailTravauxModel;
use App\Models\DevisModel;
use App\Models\FinitionModel;
use App\Models\MaisonModel;
use App\Models\PaiementModel;
use App\Models\TravauxModel;
use App\Models\UniteModel;

use Exception; 
use CodeIgniter\Controller;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Symfony\Component\EventDispatcher\DependencyInjection\AddEventAliasesPass;
class CsvController extends BaseController
{
    public function index()
	{
		return view("upload-files");
	}

    public function uploadpaiement(){
        $file = $this->request->getFile('file');

        if ($file && $file->isValid()) {
            $name = $file->getClientName();
            $path =$file->getRealPath();
            $destinationDirectory = WRITEPATH;
            $newFileName = 'paiement.csv'; // Optionnel

            // Déplacer le fichier
            $file->move($destinationDirectory, $newFileName);
            $fichier = $destinationDirectory.'\\'.$newFileName;
            $this->CsvImportPaiement($fichier);
            echo($fichier);
            // echo(WRITEPATH);
            // $data = $this->uploadFile($fichier);
        
        // Retourner la vue avec les données
            // return view('uploadCSV', $data);
        } else {
            // Gérer le cas où aucun fichier n'est téléchargé ou si le fichier n'est pas valide
            echo "Aucun fichier téléchargé ou fichier invalide.";
        }

    }
    // public function getFiles(){
    //     $file1 = $this->request->getFile('file1');
    //     $file2 = $this->request->getFile('file2');

    //     if ($file1 && $file1->isValid()) {
    //         $name = $file1->getClientName();
    //         $path =$file1->getRealPath();
    //         $destinationDirectory = WRITEPATH;
    //         $newFileName = 'maison.csv'; // Optionnel

    //         // Déplacer le fichier
    //         $file1->move($destinationDirectory, $newFileName);
    //         $fichier = $destinationDirectory.'\\'.$newFileName;
    //         $this->CsvImportMaisonTravaux($fichier);
    //         echo($fichier);
    //         // echo(WRITEPATH);
    //         // $data = $this->uploadFile($fichier);
        
    //     // Retourner la vue avec les données
    //         // return view('uploadCSV', $data);
    //     } else {
    //         // Gérer le cas où aucun fichier n'est téléchargé ou si le fichier n'est pas valide
    //         echo "Aucun fichier téléchargé ou fichier invalide.";
    //     }


    //     if ($file2 && $file2->isValid()) {
    //         $name = $file2->getClientName();
    //         $path =$file2->getRealPath();
    //         $destinationDirectory = WRITEPATH;
    //         $newFileName = 'devise.csv'; // Optionnel

    //         // Déplacer le fichier
    //         $file2->move($destinationDirectory, $newFileName);
    //         $fichier = $destinationDirectory.'\\'.$newFileName;
    //         $this->CsvImportMaisonTravaux($fichier);
    //         echo($fichier);
    //         // echo(WRITEPATH);
    //         // $data = $this->uploadFile($fichier);
        
    //     // Retourner la vue avec les données
    //         // return view('uploadCSV', $data);
    //     } else {
    //         // Gérer le cas où aucun fichier n'est téléchargé ou si le fichier n'est pas valide
    //         echo "Aucun fichier téléchargé ou fichier invalide.";
    //     }

    // }
    public function CsvImportPaiement($csvFilePath){
        $data = array();
        $db =  \Config\Database::connect();
        $csvMimes = ['text/csv', 'application/vnd.ms-excel', 'text/plain', 'text/tsv'];
        $qstring = ['status' => ''];
    
        // $csvFilePath = WRITEPATH . 'donne.csv';
    
        // var_dump($csvFilePath); 
        $csvFile = fopen($csvFilePath, 'r');
    
        $lineNumber = 1; 

        if (!$csvFile) {
            $error = error_get_last();
            echo "Erreur lors de l'ouverture du fichier CSV : " . $error['message'];
            die(); // Arrêter l'exécution du script
        } else {
            echo("OK"); 
        }
        
        try{
            if ($csvFile) {
                fgetcsv($csvFile);
         
                $paiementmodel = new PaiementModel();
                 // Parse data from CSV file line by line
                while (($line = fgetcsv($csvFile)) !== false) {

                    try {
                        $paiement = $paiementmodel->where('id', $line[1])->first();
                        if ($paiement) {
                            $idpaiement = $line[1];
                        } else{
                            
                            $prix = str_replace(',', '.', $line[3]);
                            $paiementmodel->insertData($line[1], $line[2], $prix,$line[0]);   
                        }
                        

                    }catch (DatabaseException $e) {
                            // Extract the error message from the exception
                            echo("non");
                            $errorMessage = $e->getMessage();
                            $logFilePath = WRITEPATH. 'error_log.txt';

                            file_put_contents($logFilePath, "An error occurred: ". $errorMessage. " : ON LINE ---". $lineNumber. "-- IN YOUR CSV".PHP_EOL, FILE_APPEND);
                            $erreurs = "An error occurred: ". $errorMessage ." : hereeee".$lineNumber ;
                            $data['erreurs'][] = "An error occurred: " . $errorMessage;
                            
                    }
                     
                }
         
                fclose($csvFile);
        
                $qstring['status'] = 'Success';
            } else {
                $qstring['status'] = 'Invalid file';
            }
        }catch(\Exception $e){
            $data['error'] = $e->getMessage();
        }
    }

    public function CsvImportDevis($csvFilePath){
        $data = array();
        $db =  \Config\Database::connect();
        $csvMimes = ['text/csv', 'application/vnd.ms-excel', 'text/plain', 'text/tsv'];
        $qstring = ['status' => ''];
    
        // $csvFilePath = WRITEPATH . 'donne.csv';
    
        // var_dump($csvFilePath); 
        $csvFile = fopen($csvFilePath, 'r');
    
        $lineNumber = 1; 

        if (!$csvFile) {
            $error = error_get_last();
            echo "Erreur lors de l'ouverture du fichier CSV : " . $error['message'];
            die(); // Arrêter l'exécution du script
        } else {
            echo("OK"); 
        }
        
        try{
            if ($csvFile) {
                fgetcsv($csvFile);
        
                $maisonmodel = new MaisonModel();
                $clientmodel = new ClientModel();
                $devismodel = new DevisModel();
                $finitionmodel = new FinitionModel();

                 // Parse data from CSV file line by line
                while (($line = fgetcsv($csvFile)) !== false) {

                    try {
                        $maison = $maisonmodel->where('nom', $line[2])->first();
                        if ($maison) {
                            $idmaison = $maison['id'];
                        } else{
                            throw new Exception("MAISON N'EXISTE PAS");
                        }

                        $client = $clientmodel->where('phone', $line[0])->first();
                        if ($client) {
                            $idclient = $client['id'];
                        } else {
                            $clientmodel->insert([
                                'phone' => $line[0]
                            ]); 
                            $client = $clientmodel->orderBy('id', 'DESC')->first();
                            $idclient  = $client['id'];
                        }

                        $finition = $finitionmodel->where('nom',$line[3])->first();
                        if($finition){
                            $idfinition = $finition['id'];
                        }else{
                            $percentageString = $line[4];
                            $percentageString = str_replace(',', '.', $percentageString);
                            $percentageDecimal = (float) strtok($percentageString, '%');
                            $finitionmodel->insert([
                                'nom' => $line[3],
                                'pourcentage' => $percentageDecimal
                            ]);
                            $finition = $finitionmodel->orderBy('id', 'DESC')->first();
                            $idfinition = $finition['id'];
                        }
                        
                        $devis = $devismodel->where('id',$line[1])->first();
                        if($devis){
                            $iddevis = $line[1]; 
                        }else{
                            $devismodel->insertData($line[1], $idclient, $idmaison, $idfinition, $line[5], $line[6], $line[7]);
                        }
                        
                        
                     
                    }catch (DatabaseException $e) {
                            // Extract the error message from the exception
                            echo("non");
                            $errorMessage = $e->getMessage();
                            $logFilePath = WRITEPATH. 'error_log.txt';

                            // Append the error message and line number to the log file
                            file_put_contents($logFilePath, "An error occurred: ". $errorMessage. " : ON LINE ---". $lineNumber. "-- IN YOUR CSV".PHP_EOL, FILE_APPEND);
                            // Display the error message
                            $erreurs = "An error occurred: ". $errorMessage ." : hereeee".$lineNumber ;
                            // $data['erreurs'] = $erreurs;
                            $data['erreurs'][] = "An error occurred: " . $errorMessage;
                            // return view('uploadCSV', $data);
                            
                    }
                            // $produit= $produitModel->orderBy('id','DESC')->first();
                        // $id_produit = $produit['id'];
                     
                }
         
                fclose($csvFile);
        
                $qstring['status'] = 'Success';
            } else {
                $qstring['status'] = 'Invalid file';
            }
        }catch(\Exception $e){
            $data['error'] = $e->getMessage();
            //ALAINA HOE LIGNE FIRY TAO ANATY CSV LE ERREUR DE INSERER-NA ANATY TABLE   
        }
    }
    public function CsvImportMaisonTravaux($csvFilePath){
        $data = array();
        $db =  \Config\Database::connect();
        $csvMimes = ['text/csv', 'application/vnd.ms-excel', 'text/plain', 'text/tsv'];
        $qstring = ['status' => ''];
    
        // $csvFilePath = WRITEPATH . 'donne.csv';
    
        // var_dump($csvFilePath); 
        $csvFile = fopen($csvFilePath, 'r');
    
        $lineNumber = 1; 
        $i = 1;
        if (!$csvFile) {
            $error = error_get_last();
            echo "Erreur lors de l'ouverture du fichier CSV : " . $error['message'];
            die(); // Arrêter l'exécution du script
        } else {
            echo("OK"); 
        }
        
        try{
            if ($csvFile) {
                fgetcsv($csvFile);
        
                $maisonmodel = new MaisonModel();
                $travauxmodel = new TravauxModel();
                $detailtravauxmodel = new DetailTravauxModel();
                $unitemodel = new Unitemodel();
                 // Parse data from CSV file line by line
                while (($line = fgetcsv($csvFile)) !== false) {
                    $i++;
                    try {
                        $maison = $maisonmodel->where('nom', $line[0])->where('surface', str_replace(',', '.', $line[2]))->where('descriptions',$line[1])->first();
                        if ($maison) {
                            $idmaison = $maison['id'];
                        } else {
                            $maisonmodel->insert([
                                'nom' => $line[0],
                                'duree_jours' => $line[8],
                                'descriptions'=> $line[1],
                                'surface' => str_replace(',', '.', $line[2])
                            ]); 
                            $maison = $maisonmodel->orderBy('id', 'DESC')->first();
                            $idmaison  = $maison['id'];
                        }
                        
                        $unite = $unitemodel->where('nom', $line[5])->first();
                        if ($unite) {
                            $idunite = $unite['id'];
                        } else {
                            $unitemodel->insert([
                                'nom' => $line[5]
                            ]); 
                            $unite = $unitemodel->orderBy('id', 'DESC')->first();
                            $idunite  = $unite['id'];
                        }
                        
                        
                        
                        
                        $travaux = $travauxmodel->where('id', $line[3])->first();
                        // var_dump($line[3]);
                        var_dump($i);

                        if(!$travaux){
                            var_dump($line[3]);
                            $travauxmodel->insertData($line[3], $line[4]);
                            $travaux = $travauxmodel->orderBy('id','DESC')->first();    
                            $idtravaux = $travaux['id'];
                                
                        }else {
                            $idtravaux = $travaux['id'];
                        }

                        $detailtravaux = $detailtravauxmodel->where('idtravaux',$idtravaux)->where('idmaison', $idmaison)->where('idunite',$idunite)->where('quantite',str_replace(',', '.', $line[7]))->where('prix',str_replace(',', '.', $line[6]))->first();
                        if($detailtravaux){
                            $iddetailtravaux = $detailtravaux['iddetailtravaux'];
                        }else{
                            $detailtravauxmodel->insert([
                                'idtravaux' => $idtravaux,
                                'idmaison' => $idmaison,
                                'idunite' => $idunite,
                                'quantite' => str_replace(',', '.', $line[7]),
                                'prix'=> str_replace(',', '.', $line[6])
                            ]);
                        }
                    }catch (DatabaseException $e) {
                            // Extract the error message from the exception
                            echo("non");
                            $errorMessage = $e->getMessage();
                            $logFilePath = WRITEPATH. 'error_log.txt';

                            // Append the error message and line number to the log file
                            file_put_contents($logFilePath, "An error occurred: ". $errorMessage. " : ON LINE ---". $lineNumber. "-- IN YOUR CSV".PHP_EOL, FILE_APPEND);
                            // Display the error message
                            $erreurs = "An error occurred: ". $errorMessage ." : hereeee".$lineNumber ;
                            // $data['erreurs'] = $erreurs;
                            $data['erreurs'][] = "An error occurred: " . $errorMessage;
                            // return view('uploadCSV', $data);
                            
                    }
                            // $produit= $produitModel->orderBy('id','DESC')->first();
                        // $id_produit = $produit['id'];
                     
                }
         
                fclose($csvFile);
                var_dump($i);
        
                $qstring['status'] = 'Success';
            } else {
                $qstring['status'] = 'Invalid file';
            }
        }catch(\Exception $e){
            $data['error'] = $e->getMessage();
            //ALAINA HOE LIGNE FIRY TAO ANATY CSV LE ERREUR DE INSERER-NA ANATY TABLE   
        } 
        // redirect('upload-files');
        }
    

    
        public function getFiles(){
            $session = session();

                // Récupérer le premier fichier
                $file1 = $this->request->getFile('file1');
                if ($file1 && $file1->isValid()) {
                    $destinationDirectory = WRITEPATH;
                    $newFileName = 'maison.csv'; // Nom du fichier pour le premier fichier
                    $file1->move($destinationDirectory, $newFileName);
                    $fichier1 = $destinationDirectory . '/' . $newFileName;
                    $this->CsvImportMaisonTravaux($fichier1);
                    echo "Fichier 1 téléchargé avec succès : " . $fichier1 . "<br>";
                }

                // Récupérer le deuxième fichier
                $file2 = $this->request->getFile('file2');
                if ($file2 && $file2->isValid()) {
                    $destinationDirectory = WRITEPATH;
                    $newFileName = 'devis.csv'; // Nom du fichier pour le premier fichier
                    $file2->move($destinationDirectory, $newFileName);
                    $fichier2 = $destinationDirectory . '/' . $newFileName;
                    $this->CsvImportDevis($fichier2);
                }
            }

            public function upload_paiement(){
                return view('upload-paiement');
            }
        }
//     public function getFiles()
// {
//     $files = $this->request->getFiles(); // Récupérer tous les fichiers téléchargés

//     $session = session();

//     $i = 1;
//     if ($this->request->getFileMultiple('files')) {
//         foreach ($this->request->getFileMultiple('files') as $file) {
//         if ($file && $file->isValid()) {
//             $name = $file->getClientName();
//             $path = $file->getRealPath();
//             $destinationDirectory = WRITEPATH;
//             if($i == 1){
//                 $newFileName = 'maison.csv'; // Nom du fichier avec le nom d'origine
//                 $file->move($destinationDirectory, $newFileName);
//                 $fichier = $destinationDirectory . '\\' . $newFileName;
//                 var_dump($fichier);
//                 $this->CsvImportMaisonTravaux($fichier);
//                 echo("ok");
//             }else{
//                 $newFileName = 'devis.csv';
//                 $file->move($destinationDirectory, $newFileName);
//                 $fichier = $destinationDirectory . '\\' . $newFileName;
//                 $this->CsvImportDevis($fichier);
//             }   
            
//             $i++; 
//             echo "Fichier téléchargé avec succès : " . $fichier . "<br>";
//         }
//         }

//         $session->setFlashdata("success", "Files uploaded");
//     } else {

//         $session->setFlashdata("error", "Please choose file");
//     }

// }
    
