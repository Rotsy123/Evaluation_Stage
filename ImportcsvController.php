<?php
// // Activer l'affichage des erreurs PHP
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// // Activer la sortie vers la console
// if (php_sapi_name() !== 'cli') {
//     die('Ce script ne peut être exécuté que depuis la ligne de commande.');
// }
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
// include("file_with_errors.php");

defined('BASEPATH') OR exit('No direct script access allowed');

class ImportcsvController extends CI_Controller {
   

    public function __construct() {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->model('Importcsv_model','import');
        $this->load->model('ColorErreur','error');
        // $this->load->library('error_handler');

    }
    public function index() {
        $data['listetable']=$this->import->getAlltable();
        $this->load->view('Admin/Header');
        $this->load->view('Importcsv',$data);
        $this->load->view('Admin/Footer');
    }
    public function erreur() {
        $data=array();
        $data['erreur']='donner deja importer ';
        $data['listetable']=$this->import->getAlltable();
        $this->load->view('Admin/Header');
        $this->load->view('Importcsv',$data);
        $this->load->view('Admin/Footer');
    }

    
    
    
    #premiere insertion dans la table principale
    public function import_csv() {
        $nomtable= $this->input->post('nomtable');
        $file = $_FILES['file']['tmp_name']; 
        $handle = fopen($file, "r");
        try {
            // $this->db->trans_start();
            if ($handle) {
                $i=0;
                // $this->load->view('Admin/Header');
                // $this->load->view('Importcsv');
                // $this->load->view('Admin/Footer');
                while (($row = fgetcsv($handle, 0, ",")) !== FALSE) {
                    if( $i==0){
                        $i=1; 
                        $columns =$this->import->buildKey($row,$nomtable);
                        continue;
                    }else{
                        $data = array();
                        // var_dump($row);
                        for ($i = 0; $i < count($columns); $i++) {
                            $value = trim($row[$columns[$i][0]]);
                            if ($value !== '') { // Vérifie si la valeur n'est pas vide après suppression des espaces
                                $data[$columns[$i][1]] = $value;
                            }
                        }
                        $data =$this->import->trim_tableau($data);
                        try {
                            $this->error->log('debug', print_r($data, true));
                            if( $this->db->get_where($nomtable, $data)->row_array()==null){
                                if(!empty($data)){
                                    $this->db->insert($nomtable, $data);
                                }
                            }else{
                                // echo "value";
                                echo $this->db->insert($nomtable, $data);
                                if ($this->db->error()) {
                                    throw new Exception('Erreur de base de données : ' . $this->db->error()['message']);
                                }
                                // return redirect('importcsvController/erreur');
                            }
                           
                        }catch (Exception $e) {
                                $this->error->log('debug', 'Une exception a été levée : ' . $e->getMessage());
                            // echo 'Une exception a été levée : ' . $e->getMessage();
                        }
                    
                    }
                }
                // $this->db->trans_complete();
                // if ($this->db->trans_status() === FALSE) {
                //     $this->db->trans_rollback();
                //     $this->error->log('debug', "Transaction échouée.");
                    
                // } else {
                //     $this->db->trans_commit();
                //     $this->error->log('debug', "Transaction réussie.");
                // }
                fclose($handle); 
                $this->import->fetch($this->input->post('nomtable'));
               
            }
        }
        catch(Exception $e) {
            $this->db->trans_rollback();
            $this->error->log('debug', "Erreur : " . $e->getMessage());
        }

        // redirect('importcsvController/index'); 
    }
}
