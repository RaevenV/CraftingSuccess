<?php 

// simpan data dari index.php

session_start();

$_SESSION["event_id"] = $_POST["event_id"];
$_SESSION["package_id"] = $_POST["package_id"];
$_SESSION["promo_code"] = $_POST["promo_code"];
$_SESSION["qty"] = $_POST["qty"];
$_SESSION["isAlumni"] = $_POST["isAlumni"];

$response = array(
    "success" => true
);

echo json_encode($response);

exit;