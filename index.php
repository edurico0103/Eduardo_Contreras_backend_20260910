<?php

require("core/conn.php");

// Valores por defecto
$arreglo = array(
    "success" => false,
    "status" => array(
        "status_code" => 400,
        "status_text" => "Bad Request"
    ),
    "data" => "",
    "message" => "",
    "cant" => 0
);

// Content-Type por defecto
$contenttype = "Content-Type: application/json";

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    // Verificamos que exista el parametro type
    if (isset($_GET["type"]) && $_GET["type"] != "") {

        $Conexion = new conexion;
        $conn = $Conexion->conectar();

        $datos = $conn->query("SELECT * FROM empleado");
        $resultados = $datos->fetchAll();

        switch ($_GET["type"]) {

            case "json":

                $arreglo = array(
                    "success" => true,
                    "status" => array(
                        "status_code" => 200,
                        "status_text" => "OK"
                    ),
                    "data" => $resultados,
                    "message" => "",
                    "cant" => sizeof($resultados)
                );

                break;

            case "xml":

                $contenttype = "Content-Type: text/xml";

                $xml = new SimpleXMLElement("<empleados/>");

                foreach ($resultados as $empleado) {

                    $subnodo = $xml->addChild("empleado");

                    foreach ($empleado as $campo => $valor) {

                        // Evitamos campos numéricos duplicados de PDO
                        if (!is_numeric($campo)) {
                            $subnodo->addChild($campo, htmlspecialchars($valor));
                        }
                    }
                }

                header($contenttype);
                echo $xml->asXML();
                exit;

            default:

                $arreglo = array(
                    "success" => false,
                    "status" => array(
                        "status_code" => 412,
                        "status_text" => "Precondition Failed"
                    ),
                    "data" => "",
                    "message" => "Por favor defina un formato valido: json o xml",
                    "cant" => 0
                );

                break;
        }

    } else {

        // No se envio type
        $arreglo = array(
            "success" => false,
            "status" => array(
                "status_code" => 412,
                "status_text" => "Precondition Failed"
            ),
            "data" => "",
            "message" => "SE ESPERABA EL PARAMETRO 'type', CON EL TIPO DE RESULTADO ESPERADO",
            "cant" => 0
        );
    }

} else {

    // Metodo diferente de GET
    $arreglo = array(
        "success" => false,
        "status" => array(
            "status_code" => 405,
            "status_text" => "Method Not Allowed"
        ),
        "data" => "",
        "message" => "NO SE ACEPTA UN METODO DIFERENTE DE GET",
        "cant" => 0
    );
}

// Enviamos los headers
header($contenttype);
header(
    "HTTP/1.1 " .
    $arreglo["status"]["status_code"] .
    " " .
    $arreglo["status"]["status_text"]
);

// Respuesta JSON
echo json_encode($arreglo);

?>