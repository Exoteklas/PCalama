<?php
header("Access-Control-Allow-Origin: *"); // Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Permitir solicitudes POST y OPTIONS

if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    // El navegador está realizando una solicitud de pre-vuelo OPTIONS
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Max-Age: 86400'); // Cache preflight request for 1 day
    header("HTTP/1.1 200 OK");
    exit;
}

include("../conf.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $json_data = file_get_contents("php://input");

    $data = json_decode($json_data, true);

    if ($data !== null){
        // Obtener datos desde JSON
        $nombre = $data["nombre"];
        $contacto = $data["contacto"];

        $chck = $conn->prepare("SELECT nombre FROM empParking WHERE nombre = ?");
        $chck->bind_param("s",$nombre);
        $chck->execute();
        $result = $chck->get_result();

        if($result->num_rows == 0){
            // SQL Seguro
            $stmt = $conn->prepare("INSERT INTO empParking (nombre, contacto) VALUES (?, ?)");
            $stmt->bind_param("ss", $nombre, $contacto);
    
            if($stmt->execute()){
                $id = $conn->insert_id;
                header('Content-Type: application/json');
                echo json_encode($id);
            } else {
                header('Content-Type: application/json');
                echo json_encode("Error: " . $conn->error);
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(false);
        }

        $conn->close();
    } else {
        http_response_code(400);
        echo $data;
        echo "Error al decodificar JSON";
    }
} else {
    http_response_code(405);
    echo "Solicitud no permitida";
}
?>