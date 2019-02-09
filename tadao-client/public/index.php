<?php

// déclaration des classes PHP qui seront utilisées
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
include("../../db.config.php");

// activation de la fonction autoloading de Composer
require __DIR__.'/../vendor/autoload.php';

$loader = new Twig_Loader_Filesystem(__DIR__.'/../templates');
// activer le mode debug et le mode de variables strictes
$twig = new Twig_Environment($loader, [
    'debug' => true,
    'strict_variables' => true,
    ]);
    
    // charger l'extension Twig_Extension_Debug
    $twig->addExtension(new Twig_Extension_Debug());
    
    // création d'une variable avec une configuration par défaut
    $config = new Configuration();
    
    // création d'un tableau avec les paramètres de connection à la BDD
    $connectionParams = [
        'driver'    => 'pdo_mysql',
        'host'      => '127.0.0.1',
        'port'      => '3306',
        'dbname'    =>  $dbname,
        'user'      =>  $user,
        'password'  =>  $password,
        'charset'   => 'utf8mb4',
    ];
    
    // connection à la BDD
    // la variable `$conn` permet de communiquer avec la BDD
    $conn = DriverManager::getConnection($connectionParams, $config);


$reponse = $conn->query("SELECT * FROM Towns WHERE Towns_name LIKE '% College %' OR Towns_name LIKE '% Lycees %' OR Towns_name LIKE '% Lycee %' OR Towns_name LIKE '% Etab. %' OR Towns_name LIKE '% Scolaires %' GROUP BY Towns_name");
while($req = $reponse->fetch()){
    $towns_name[] = $req['Towns_name'];
}

if($_POST){
    $etab_scolaire = $_POST["city"];
    // echo $etab_scolaire;
}



// $reponse = $conn->query("SELECT * FROM Towns JOIN routestowns ON routestowns.Towns_id = Towns.id 
// WHERE Towns.Towns_name = '$etab_scolaire'");
// while($req = $reponse->fetch()){
//     $tableau = array (
//         "id" => $req["route_id"],
//     );
    
// }

$reponse = $conn->query("SELECT * FROM Towns INNER JOIN routestowns ON routestowns.Towns_id = Towns.id INNER JOIN route ON route.route_id = routestowns.route_id WHERE Towns.Towns_name = '$etab_scolaire' GROUP BY route_short_name");

while($req = $reponse->fetch()){
    
    
    $tableau = array (
        "id" => $req["route_id"],
        // "passages" => "",
        "ville" => $req["route_long_name"],
    );
    
    $routes[] = $tableau["id"];
    // $passages[] = $tableau["passages"];
    $passages = "";
    $city_long_names[] = $tableau["ville"] ;

    // foreach($routes as $route){
    //     if($route == $route){
            
    //     }
    //     else {
            
    //     }
    // }    
}


echo $twig->render('home.html.twig', [
    "towns_name" => $towns_name,
    "routes" => $routes,
    "city_long_names" => $city_long_names,
    "passages" => $passages,
    "etab_scolaire" => $etab_scolaire,
    "monter_descente" => $monter_descente,
        
]);