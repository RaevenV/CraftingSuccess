<?php
include './scripts/include/functions.php';
session_start(); // Starting Session

if (!isset($_SESSION['csrftoken'])) {
    $_SESSION['csrftoken'] = md5(uniqid(mt_rand(), true));
}

$error = "";
if (isset($_GET["error"])) {
    $error = $_GET["error"];
}

// if (isset($_SESSION["reservation_code"])){
//     $reservationCode = $_SESSION["reservation_code"];
// } else {
//     echo "Reservation code is not set";
//     exit;
// }

// $requestCancelReservation = array(
//     "reservation_code" => $reservationCode
// );

// $responseCancelReservation = CallAPIWithoutAuth("POST", "reserve_ticket/cancel", $requestCancelReservation);

// if ($responseCancelReservation->error){
//     echo $responseCancelReservation->message;
//     exit;
// }

unset($_SESSION["reservation_code"]);
?>

<!DOCTYPE html>
<html>

<head>

    <!-- /.website title -->
    <title><?php echo $GLOBALS['site_title']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="icon" href="favicon.ico">

    <!-- CSS Files -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="fonts/icon-7-stroke/css/pe-icon-7-stroke.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet" media="screen">
    <link href="css/owl.theme.css" rel="stylesheet">
    <link href="css/owl.carousel.css" rel="stylesheet">

    <!-- Colors -->
    <link href="css/css-index.css" rel="stylesheet" media="screen">
    <!-- <link href="css/css-index-green.css" rel="stylesheet" media="screen"> -->
    <!-- <link href="css/css-index-purple.css" rel="stylesheet" media="screen"> -->
    <!-- <link href="css/css-index-red.css" rel="stylesheet" media="screen"> -->
    <!-- <link href="css/css-index-orange.css" rel="stylesheet" media="screen"> -->
    <!-- <link href="css/css-index-yellow.css" rel="stylesheet" media="screen"> -->

    <!-- Google Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic" />

</head>

<body data-spy="scroll" data-target="#navbar-scroll">

    <div class="force-mobile">
        <div class="force-mobile-inner">
            <div class="content-force-mobile black-background">
                <div class="row text-center" style="padding-top:32px;">
                    <h2><b>Registrasi anda telah dibatalkan.</h2>
                    <h4>Anda akan diarahkan kembali ke halaman registrasi<br>dalam 5 detik.</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- /.javascript files -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/qrcode.min.js"></script>
    <script>
        $(function () {
            setTimeout(() => {
                window.location.replace("<?php echo $GLOBALS['uri'] ?>/<?php echo $GLOBALS['app_name']; ?>");
            }, 5000);
        });
    </script>
</body>

</html>