<?php

// déclaration des classes PHP qui seront utilisées
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
include ("../../db.config.php");
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

    $truncate = $conn->query('TRUNCATE TABLE `route`');
    $truncate = $conn->query('TRUNCATE TABLE `Towns`');
    $truncate = $conn->query('TRUNCATE TABLE `routestowns`');

    
    $fp = fopen("tadao/gtfs/routes.txt", "r"); 
    // on saute la première ligne avec les noms de colone en la lisant pour rien
    $ligne = fgetcsv($fp);
    // On parcourt les autres lignes 
    while($ligne = fgetcsv($fp))
    {
        // /* On lit ligne par ligne du fichier
        $listes = $ligne;
        error_log(implode("--",$listes));
        
        // we need to remove some non-breakable whitespaces
        $towns = str_replace("\xc2\xa0", " ", $listes[2]);
        //on split les valeurs avant le " - ";
        $towns = explode(" - ", $towns);
        // parcour tous le fichiers et récupère les ajoutes dans la base de donnée
        foreach ($towns as $town) {
            // echo $listes[0]." ";
            //on ajoute toute les données à la base de donnée
            $town_split = $conn->insert('route', [
                'route_id' => $listes[0],
                'route_short_name ' => $listes[1] ,
                'route_long_name ' => $town,
                'route_desc ' => $listes[3],
                'route_type' => $listes[4],
                'route_url' => $listes[5],
    
            ]);

            // select toute la table Towns quand Towns_name = Towns_name
            $req = $conn->prepare('SELECT * FROM Towns WHERE Towns_name = (:town_name)');
            $req->execute(array(
                'town_name' => $town,
            ));
            // on vérifie s'il y as une valeur
            $donnees = $req->fetch();
            //s'il y as une valeur alors , on ne fais rien
            if($donnees) {
                $town_id = $donnees["id"];
                error_log("Town id déjà crée:" . $town_id . " " . $town);
            // sinon on ajoute a la base de donnée
            } else {

                $req = $conn->prepare('INSERT INTO Towns(Towns_name) VALUES(:town_name)');
                $req->execute(array(
                    'town_name' => $town,
                ));
                $town_id = $conn->lastInsertId();
                error_log($town_id . ", " . $town . " créée");
            }
            

            
            // on insère dans la table de jointure l'id de la route et l'id de la town
            error_log("routeid:" . $listes[0] . " - townid:" . $town_id);
            $insert = $conn->prepare('INSERT INTO routestowns(route_id , Towns_id) VALUES(:route_id , :town_id)');
            $insert->execute(array(
                'route_id' => $listes[0],
                'town_id' => $town_id,
            ));
        }
}


echo $twig->render('home.html.twig', [
           
]);

