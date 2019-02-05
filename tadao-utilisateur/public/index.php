<?php

// déclaration des classes PHP qui seront utilisées
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

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
        'dbname'    => 'bus',
        'user'      => 'nahjo',
        'password'  => 'J0han62410',
        'charset'   => 'utf8mb4',
    ];
    
    // connection à la BDD
    // la variable `$conn` permet de communiquer avec la BDD
    $conn = DriverManager::getConnection($connectionParams, $config);


$reponse = $conn->query("SELECT * FROM Towns WHERE Towns_name LIKE '% College %' OR Towns_name LIKE '% Lycees %' OR Towns_name LIKE '% Lycee %' ORDER BY Towns_name");
while($req = $reponse->fetch()){
    $towns_name[] = $req['Towns_name'];
}

if($_POST){
    $teste = $_POST["city"];
    echo $teste; 
}

$reponse = $conn->query("SELECT DISTINCT * FROM Towns INNER JOIN routestowns ON routestowns.Towns_id = Towns.id WHERE Towns.Towns_name = '$teste'");
while($req = $reponse->fetch()){

    $routes[] = $req["route_id"];
}

echo $twig->render('home.html.twig', [
    "towns_name" => $towns_name,
    "routes" => $routes,
        
]);