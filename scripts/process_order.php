<?php

include './include/functions.php';

session_start();

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
    if (!$token || $token !== $_SESSION['csrftoken']) {
        $response["url"] = "";
        echo json_encode($response);
        exit;
    }
} else {
    $response["url"] = "";
    echo json_encode($response);
    exit;
}

$data = array(
    "external_id" => $_POST["external_id"]
);

$result = CallWebhook("POST", "webhook/receive_xendit", $data);

if ($result->message == "ok"){
    
    $response["url"] = "finish.php";
} else {
    $response["url"] = "";
}

echo json_encode($response);
exit;