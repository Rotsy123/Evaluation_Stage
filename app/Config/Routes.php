<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
 




 

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
*/
$routes->get('/', 'UserController::loginClientIndex'); 
$routes->match(['get', 'post'], 'register', 'UserController::register', ['filter' => 'noauth']);
$routes->match(['get', 'post'], 'login', 'UserController::login', ['filter' => 'noauth']);
$routes->match(['get', 'post'], 'listeEncours', 'AdminController::listeEncours', ['filter' => 'auth']);
$routes->match(['get', 'post'], 'AfficherDetail', 'AdminController::AfficherDetail', ['filter' => 'auth']);
$routes->match(['get', 'post'], 'getListTravaux', 'AdminController::getListTravaux', ['filter' => 'auth']);
$routes->match(['get', 'post'], 'mettreajour', 'AdminController::mettreajour', ['filter' => 'auth']);
$routes->match(['get', 'post'], 'mettreAJourTravaux', 'AdminController::mettreAJourTravaux', ['filter' => 'auth']);

$routes->match(['get', 'post'], 'reinitialiserBase', 'AdminController::reinitialiserBase', ['filter' => 'auth']);


$routes->match(['get', 'post'], 'getListFinition', 'AdminController::getListFinition', ['filter' => 'auth']);
$routes->match(['get', 'post'], 'mettreajourfinition', 'AdminController::mettreajourfinition', ['filter' => 'auth']);
$routes->match(['get', 'post'], 'updatefinition', 'AdminController::updatefinition', ['filter' => 'auth']);


$routes->match(['get', 'post'], 'statistique', 'AdminController::statistique');



$routes->get('deleteAll', 'UserController::deleteAll');
$routes->get('loginClientIndex', 'UserController::loginClientIndex');
$routes->post('loginClient', 'UserController::loginClient');

$routes->get('insererDevis', 'ClientController::index');
$routes->post('insertDevis', 'ClientController::insertDevis');
$routes->get('listeDevis', 'ClientController::listeDevis');

$routes->post('getSousDevis', 'ClientController::getSousDevis');


$routes->match(['get', 'post'], 'choisirPaiement', 'ClientController::ChoisirPaiement');

$routes->post('validerchoix', 'ClientController::validerchoix');

$routes->get('upload-files', 'CsvController::index');
$routes->post('getFiles', 'CsvController::getFiles');
$routes->post('uploadpaiement', 'CsvController::uploadpaiement');
$routes->get('upload_paiement', 'CsvController::upload_paiement'); 







// ChoisirPaiement





