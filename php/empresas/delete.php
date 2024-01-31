<?php
// Declaración estricta de tipos para garantizar la coherencia en el tipo de datos
declare(strict_types=1);

// Establece los encabezados CORS para permitir solicitudes desde cualquier origen y métodos POST
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

// Verifica si la solicitud es OPTIONS (solicitud de pre-vuelo)
if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    // El navegador está realizando una solicitud de pre-vuelo OPTIONS, se establecen los encabezados permitidos
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Max-Age: 86400'); // Cache preflight request for 1 day
    header("HTTP/1.1 200 OK");
    exit;
}

// Incluye el archivo de configuración de la base de datos
include("../conf.php");

include('../auth.php');

if($token->nivel < $LVLADMIN){
    header('HTTP/1.1 401 Unauthorized'); // Devolver un código de error de autorización si el token no es válido
    echo json_encode(['error' => 'Autoridad insuficiente']);
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $json_data = file_get_contents("php://input"); // Obtener datos JSON de la solicitud POST

    $data = json_decode($json_data, true); // Decodificar los datos JSON

    if ($data !== null){
        // Obtener datos desde el JSON
        $id = $data["id"];

        // Verificar si la empresa existe en la base de datos
        $chck = $conn->prepare("SELECT nombre FROM empParking WHERE idemp = ?");
        $chck->bind_param("i",$id);
        $chck->execute();
        $result = $chck->get_result();

        if($result->num_rows >= 1){ // Si la empresa existe
            // Preparar y ejecutar la consulta SQL para eliminar la empresa
            $stmt = $conn->prepare("DELETE FROM empParking WHERE idemp = ?");
            $stmt->bind_param("i", $id);
    
            if($stmt->execute()){ // Si la eliminación se realizó correctamente
                header('Content-Type: application/json');
                echo json_encode(true); // Devolver respuesta JSON con true
            } else {
                header('Content-Type: application/json');
                echo json_encode(false); // Devolver respuesta JSON con false
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(false); // Devolver respuesta JSON con false (empresa no encontrada)
        }

        $conn->close(); // Cerrar la conexión a la base de datos
    } else {
        http_response_code(400); // Devolver código de error 400 (Bad Request)
        echo $data;
        echo "Error al decodificar JSON"; // Mensaje de error
    }
} else {
    http_response_code(405); // Devolver código de error 405 (Method Not Allowed)
    echo "Solicitud no permitida"; // Mensaje de error
}
?>