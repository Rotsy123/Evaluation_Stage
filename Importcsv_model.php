
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Importcsv_model extends CI_Model {

    public $colonne_principale = ''; // Définition d'une valeur par défaut pour éviter l'erreur "undefined variable"
    public $tableEnRelation = ''; // Définition d'une valeur par défaut
    public $tableEnRelation_colonne = ''; // Définition d'une valeur par défaut
    
    public $fetch_value = []; // Définition d'une valeur par défaut

    public function __construct() {
        parent::__construct();
        $this->fetch_value['salle']['insert']['from'] = 'nomsalle';
        $this->fetch_value['salle']['insert']['to'] = 'salle';
        $this->fetch_value['salle']['update']['from'] = 'salle';
        $this->fetch_value['salle']['update']['to'] = 'idsalle';
        $this->fetch_value['salle']['update']['condition'] = 'salle.nomsalle = import.salle';

    
        $this->fetch_value['categorie']['insert']['from'] = 'nomcategorie';
        $this->fetch_value['categorie']['insert']['to'] = 'categorie';
        $this->fetch_value['categorie']['update']['from'] = 'categorie';
        $this->fetch_value['categorie']['update']['to'] = 'idcategorie';
        $this->fetch_value['categorie']['update']['condition'] = 'categorie.nomcategorie = import.categorie';
        
        $this->fetch_value['film']['insert']['from'] = 'nomfilm,idcategorie';
        $this->fetch_value['film']['insert']['to'] = 'film,categorie';
        $this->fetch_value['film']['update']['from'] = 'film';
        $this->fetch_value['film']['update']['to'] = 'idfilm';
        $this->fetch_value['film']['update']['condition'] = 'film.nomfilm = import.film';
        

        $this->fetch_value['seance']['insert']['from'] = 'idsalle,idfilm,date';
        $this->fetch_value['seance']['insert']['to'] = 'salle,film,dateheure';
        $this->fetch_value['seance']['update']['from'] = null;
        $this->fetch_value['seance']['update']['to'] = null;
        $this->fetch_value['seance']['update']['condition'] = null;


    }
    public function setColonneprincipale($colonne){
        $colonne_principale =$colonne;
    }
    public function getAlltable() {
        $query = "SELECT tables.table_name, COUNT(constraints.constraint_name) AS nombre_foreign_keys 
        FROM information_schema.tables AS tables 
        LEFT JOIN information_schema.table_constraints AS constraints 
            ON tables.table_name = constraints.table_name 
            AND constraints.constraint_type = 'FOREIGN KEY'  
        WHERE tables.table_schema = 'public'   
            AND tables.table_type = 'BASE TABLE' -- Exclure les vues
        GROUP BY tables.table_name  
        ORDER BY COUNT(constraints.constraint_name) ;
        ";

        return $this->db->query($query)->result_array();
    }

    public function getTRelatoinConversion($listeColonne) {
        // echo $this->colonne_principale;
        #avoir la liste des table en relation avec la table de conversion
        $resulta=array();
        for($i=0;$i<count($listeColonne);$i++){
            $split= explode("_", $listeColonne);
            $resulta[$i]=$split;
        }
        // var_dump($resulta);

        return $resulta;
    }
    public function trim_tableau($tableau) {
        if (!is_array($tableau)) {
            return $tableau;
        }
        foreach ($tableau as $key => &$element) {
            if (is_array($element)) {
                $tableau[$key] = $this->trim_tableau($element); // Utilisation de $this->trim_tableau() pour l'appel récursif
            } else {
                $element=preg_replace('/\s+/', ' ', $element);
                $tableau[$key] = trim($element);
            }
        }
        return $tableau; // Retournez le tableau modifié
    }
    
    public function  buildKey($firstline,$tablename){
        $resulta=[];
        $query =   $this->db->query("SELECT column_name FROM information_schema.columns WHERE table_name = '".$tablename."' ");
        $table = $query->result_array();
        $column_names = array_column($table, 'column_name');
       
        $a=0; 
        for($i=0;$i<count($firstline);$i++){
            if(in_array(strtolower($firstline[$i]), $column_names)){
                $resulta[$a]=[$i,strtolower($firstline[$i])];
                $a++;
            }
        }
        // var_dump($resulta);
        return $resulta;
    }
    public function fetch($nomtable){
        echo $nomtable;
        foreach ($this->fetch_value as $key1 => $value1) {
            $sql = sprintf("INSERT INTO %s (%s)
            SELECT distinct %s
            FROM %s;",$key1,$value1['insert']['from'],$value1['insert']['to'],$nomtable);
            // echo $sql;
            $this->db->query($sql);

            $sql = sprintf("UPDATE %s
            SET %s = (SELECT %s FROM %s 
            WHERE %s )",
            $nomtable,$value1['update']['from'],
            $value1['update']['to'],$key1,
            $value1['update']['condition']);
            // echo  $value1['update']['condition'];
            // echo $sql;
            $this->db->query($sql);
            var_dump( $value1);
            // $this->error->log('debug', print_r($value1, true));
            // echo $key1;
            // $this->error->log('debug', $key1);
        }
    }
}
