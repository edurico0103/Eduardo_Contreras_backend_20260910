<?php
require("core/conn.php");
//VALORES POR DEFECTO A LA POSIBLE RESPUESTA DEL BACKEND
$arreglo = array("success" => false, "status" => 400, "data" => "", "message" => "", "cant" => 0);

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    //ES METODO GET
    if (isset($_GET["type"]) && $_GET["type"] != "") {
        //SI SE HA ENVIADO EL PARAMETRO DE SELECCION DE FORMATO DE RESPUESTA
        $Conexion = new conexion;
        $conn = $Conexion->conectar();
        $datos = $conn->query('SELECT * FROM empleado');
        $resultados = $datos->fetchAll();
        $cantidad = sizeof($resultados);

        switch ($_GET["type"]) {
            case "json":
                result_json($resultados);
                break;
            case "xml":
                result_xml($resultados);
                break;
            default:
                echo ("POR FAVOR DEFINA EL FORMATO DE RESULTADO QUE ESPERA");
                break;
        }
    } else {
        //NO SE HA ENVIADO EL PARAMETRO ESPERADO
        $contenttype = "Content-Type: application/json";
        $arreglo = array(
            "success" => false,
            "status" => array(
                "status_code" => 412,
                "status_text" => "Precondition failed"
            ),
            "data" => "",
            "message" => "SE ESPERABA EL PARAMETRO 'type', CON EL TIPO DE RESULTADO ESPERADO",
            "cant" => 0
        );
        header($contenttype);
        header("HTTP/1.1 " . $arreglo["status"]["status_code"] . " " . $arreglo["status"]["status_text"]);
        echo (json_encode($arreglo));
    }
} else {
    //NO ES EL METODO GET
    $contenttype = "Content-Type: application/json";
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

header($contenttype);
header("HTTP/1.1 " . $arreglo["status"]["status_code"] . " " . $arreglo["status"]["status_text"]);
echo (json_encode($arreglo));

function result_json()
{
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

    header($contenttype);
    header("HTTP/1.1 " . $arreglo["status"]["status_code"] . " " . $arreglo["status"]["status_text"]);
    echo (json_encode($arreglo));
}

function resul_xml()
{
    $contenttype = "Content-Type: text/xml";
    $xml = SimpleXMLElement('<empleados/>');
    foreach ($resultados as $i => $v) {
        $subnodo = $xml->addChild("empleado");
        $a = array_flip($v);
        array_walk_recursive($a, array($subnodo, 'addChild'));
    }
    header($contenttype);
    echo ($xml->asXML);
}

?>