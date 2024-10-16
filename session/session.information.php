<?php

session_start();

$data = $_POST["data"];

$_SESSION["info_data"] = $data;

// Check the value of 'kehadiran' and update the session package_id accordingly
foreach ($data as $participant) {
    if (isset($participant['kehadiran'])) {
        if ($participant['kehadiran'] === 'Alumni Gathering') {
            $_SESSION["package_id"] = 97;
        } elseif ($participant['kehadiran'] === 'Both') {
            $_SESSION["package_id"] = 98;
        }
    }
}

$response = array(
    "success" => true
);

echo json_encode($response);

exit;