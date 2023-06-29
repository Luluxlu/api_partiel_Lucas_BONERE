<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// On vérifie que la méthode utilisée est correcte
if($_SERVER['REQUEST_METHOD'] == 'GET') {
    // On inclut les fichiers de configuration et d'accès aux données
    include_once '../config/Database.php';
    include_once '../models/Employee.php';

    // On instancie la base de données
    $database = new Database();
    $db = $database->getConnection();

    // On instancie les employees
    $employee = new Employee($db);

    $donnees = json_decode(file_get_contents("php://input"));

    if(!empty($donnees->id)){
        $employee->id = $donnees->id;

        // On récupère l'employee
        $employee->lireUn();

        // On vérifie si l'employee existe
        if($employee->id != null){

            $prod = [
                "id" => $employee->id,
                "name" => $employee->name,
                "email" => $employee->email,
                "age" => $employee->age,
                "designation" => $employee->designation,
                "created" => $employee->created
            ];
            // On envoie le code réponse 200 OK
            http_response_code(200);

            // On encode en json et on envoie
            echo json_encode($prod);
        }else{
            // 404 Not found
            http_response_code(404);
         
            echo json_encode(array("message" => "Le produit n'existe pas."));
        }
        
    }
} else {
    // On gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
}