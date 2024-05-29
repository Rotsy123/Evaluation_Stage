<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProduitModel;
use App\Models\UserModel;
use App\Models\Formater;
use App\Models\ClientModel;
use App\Models\DevisModel;


class UserController extends BaseController
{
    public function loginClientIndex(){
        return view ('loginclient');
    }

    
    // public function loginClient(){
    //     $phone = $this->request->getPost("phone");
      
    //     $clientModel = new ClientModel();
    //     $idclient = $clientModel->Insertion($phone);
    //     $_SESSION['idclient'] = $idclient;
    //     // var_dump($_SESSION['idclient']);
    //     $client = $_SESSION['idclient'];
    //     $devismodel = new DevisModel();
    //     $listedevis = $devismodel->getDevisParClient($client);
    //     $data = [
    //         "listedevis" => $listedevis
    //     ];
    //     return view('listeDevisClient',$data);   
    // }

    public function loginClient(){
        // Charger le service Validation
        $validation = \Config\Services::validation();
    
        // Récupérer le numéro de téléphone depuis la requête POST
        $phone = $this->request->getPost("phone");
    
        // Définir les règles de validation
        $rules = [
            'phone' => 'required|regex_match[/^(032|033|034|038)[0-9]{7}$/]',
        ];
    
        // Définir les messages d'erreur personnalisés
        $messages = [
            'phone' => [
                'required' => 'Le numéro de téléphone est obligatoire.',
                'regex_match' => 'Le numéro de téléphone n\'est pas au bon format.',
            ],
        ];
    
        // Appliquer les règles de validation
        if (!$validation->setRules($rules)->run(['phone' => $phone])) {
            // Si la validation échoue, renvoyer les erreurs de validation au client
            return json_encode($validation->getErrors());
        }else{
            $clientModel = new ClientModel();
            $idclient = $clientModel->Insertion($phone);
            $_SESSION['idclient'] = $idclient;
            $client = $_SESSION['idclient'];
            $devismodel = new DevisModel();
            $listedevis = $devismodel->getDevisParClient($client);
            $data = [
                "listedevis" => $listedevis
            ];
        
            return view('listeDevisClient',$data);
        }
    
        // Si la validation réussit, continuer le traitement
      
    }
    
    public function setSessionClient(){
        $session = session();
    }


    public function login()
    {
        $data = [];
        // if ($this->request->getMethod(true) === 'post') {

        if ($this->request->getMethod() == 'post') {
            echo('');
            $rules = [
                'email' => 'required|min_length[6]|max_length[50]|valid_email',
                'password' => 'required|min_length[8]|max_length[255]|validateUser[email,password]',
            ];

            //message personnalise
            $errors = [
                'password' => [
                    'validateUser' => "Email or Password don't match",
                ],
            ];

            if (!$this->validate($rules, $errors)) {

                return view('login', [
                    "validation" => $this->validator,
                ]);

            } else {
                $model = new UserModel();

                $user = $model->where('email', $this->request->getVar('email'))
                    ->first();

                // Stroing session values
                $this->setUserSession($user);
                // Redirecting to dashboard after login
                return redirect()->to(base_url('statistique'));

            }
        }
        return view('login');
    }

    private function setUserSession($user)
    {
        $data = [
            'id' => $user['id'],
            'name' => $user['name'], 
            'email' => $user['email'],
            'role' =>$user['role'],
            'isLoggedIn' => true,
        ];

        session()->set($data);
        return true;
    }

    public function register()
    {
        $data = [];

        if ($this->request->getMethod() == 'post') {
            //let's do the validation here
            $rules = [
                'name' => 'required|min_length[3]|max_length[20]', 
                'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[tbl_users.email]',
                'password' => 'required|min_length[8]|max_length[255]',
                'password_confirm' => 'matches[password]',
            ];

            if (!$this->validate($rules)) {

                return view('register', [
                    "validation" => $this->validator,
                ]);
            } else {
                $model = new UserModel();

                $newData = [
                    'name' => $this->request->getVar('name'),
                    'phone_no' => $this->request->getVar('phone_no'),
                    'email' => $this->request->getVar('email'),
                    'password' => $this->request->getVar('password'),
                    'role' => 'admin',
                ];
                $model->save($newData);
                $session = session();
                $session->setFlashdata('success', 'Successful Registration');
                return redirect()->to(base_url('login'));
            }
        }
        return view('register');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }

    public function deleteAll(){
        $formater = new Formater();
        $formater->deleteAllUsers();
        $formater->resetSequence();
    }
}