<?php
//Elimina registros de la lista blanca 

// Establece cabeceras para permitir solicitudes CORS desde cualquier origen y métodos POST y OPTIONS
header("Access-Control-Allow-Origin: *"); // Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Permitir solicitudes POST y OPTIONS

// Manejo de solicitudes OPTIONS (preflight)
if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    // El navegador está realizando una solicitud de pre-vuelo OPTIONS
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Max-Age: 86400'); // Cache preflight request for 1 day
    header("HTTP/1.1 200 OK");
    exit;
}

// Incluir archivo de configuración que contiene la conexión a la base de datos u otras configuraciones
include("../conf.php");

// Manejo de solicitudes POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Leer datos JSON del cuerpo de la solicitud POST
    $json_data = file_get_contents("php://input");

    // Decodificar los datos JSON en un array asociativo
    $data = json_decode($json_data, true);

    // Verificar si se decodificó correctamente el JSON
    if ($data !== null) {
        // Obtener el valor del campo "id" del array
        $id = $data["id"];

        // Consultar la base de datos para verificar si existe una entrada con el ID proporcionado
        $chck = $conn->prepare("SELECT idwl FROM wlParking WHERE idwl = ?");
        $chck->bind_param("i", $id);
        $chck->execute();
        $result = $chck->get_result();

        // Si existe una entrada con el ID proporcionado, eliminarla
        if ($result->num_rows >= 1) {
            // Preparar y ejecutar una consulta para eliminar la entrada
            $stmt = $conn->prepare("DELETE FROM wlParking WHERE idwl = ?");
            $stmt->bind_param("i", $id);

            // Verificar si la consulta se ejecutó correctamente y devolver una respuesta JSON
            if ($stmt->execute()) {
                header('Content-Type: application/json');
                echo json_encode(true); // Devuelve true si la eliminación fue exitosa
            } else {
                header('Content-Type: application/json');
                echo json_encode(false); // Devuelve false si hubo un error en la eliminación
            }
        } else {
            // Devolver false si no se encontró una entrada con el ID proporcionado
            header('Content-Type: application/json');
            echo json_encode(false);
        }

        // Cerrar la conexión a la base de datos
        $conn->close();
    } else {
        // Devolver un código de respuesta HTTP 400 si hubo un error al decodificar el JSON
        http_response_code(400);
        echo $data;
        echo "Error al decodificar JSON";
    }
} else {
    // Devolver un código de respuesta HTTP 405 si la solicitud no es POST
    http_response_code(405);
    echo "Solicitud no permitida";
}
?>
