<?php

session_start();

if(isset($_POST["data"])){
    $data = $_POST["data"];

    $_SESSION["package_data"] = $data;
} else {
    $_SESSION["package_data"] = array();
}

$response = array(
    "success" => true
);

echo json_encode($response);

exit;