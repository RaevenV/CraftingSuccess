<?php
include './scripts/include/functions.php';
session_start(); // Starting Session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
    if (!$token || $token !== $_SESSION['csrftoken']) {
        echo "Fatal error";
        exit;
    }

    $eventId = $_POST["event_id"];
    $packageId = $_POST["package_id"];
} else {
    header("location: ./");
    exit;
}

$responseEventPackage = CallAPIWithoutAuth("GET", "event/$eventId/packages");
if (!$responseEventPackage->error) {
    $eventPackages = $responseEventPackage->data;
} else {
    echo "Error obtaining package list";
    exit;
}

$selectedPackage = null;
foreach ($eventPackages as $row) {
    if ($row->id == $packageId) {
        $selectedPackage = $row;
        break;
    }
}

if ($selectedPackage == null) {
    echo "No package match found";
    exit;
}

// If i were to make a real request using RegisterNewTicket, how do I do it question mark

$textToRun = array();

foreach ($_SESSION["info_data"] as $registrar) {
    $itemArray = array();
    if (isset($_SESSION["package_data"])) {
        foreach ($_SESSION["package_data"] as $item) {
            if ($item["registrar"] != $registrar["id"])
                continue;

            $id = explode("-", $item["item_id"]);
            $itemId = $id[0];
            $productId = isset($id[1]) ? $id[1] : null;

            $basePrice = 0;
            if ($productId == null) {
                $itemPriceResponse = CallAPIWithoutAuth("GET", "event_item/$itemId");

                if (!$itemPriceResponse->error) {
                    $basePrice = $itemPriceResponse->data->price;
                }
            } else {
                $productPriceResponse = CallAPIWithoutAuth("GET", "product/$productId");

                if (!$productPriceResponse->error) {
                    $basePrice = $productPriceResponse->data->price;
                }
            }

            $items = array(
                "item_id" => $itemId,
                "price" => $basePrice * $item["count"],
                "option" => $productId,
                "amount" => $item["count"]
            );

            array_push($itemArray, $items);
        }
    }


    $registerNewTicketRequest = array(
        "name" => $registrar["name"],
        "phone" => $registrar["phone"],
        "email" => empty(trim($registrar["email"])) ? null : $registrar["email"],
        "total_amount" => $_SESSION["ticket_price"],
        "ticket_id" => $selectedPackage->id,
        "items" => json_encode($itemArray),
        // "tgl_lahir" => empty($registrar["tgl_lahir"])? null : $registrar["tgl_lahir"],
        // "work" => empty($registrar["work"])? null : $registrar["work"],
        // "durasiMLCT" => empty($registrar["durasiMLCT"])? null : $registrar["durasiMLCT"],
        // "kehadiran" => empty($registrar["kehadiran"])? null : $registrar["kehadiran"],
        // "social_media" => empty($registrar["social_media"])? null : $registrar["social_media"],
        // "coachspeakertrainer" => empty($registrar["coachspeakertrainer"])?null : $registrar["coachspeakertrainer"],
    );

    array_push($textToRun, json_encode($registerNewTicketRequest));
}

$saveJsonRequest = array(
    "event_id" => $_SESSION["event_id"],
    "jsons" => json_encode($textToRun)
);

$saveJsonResponse = CallAPIWithoutAuth("POST", "reserve_ticket/save_register_new_ticket_json", $saveJsonRequest);

if (!$saveJsonResponse->error) {
    $saveJsonData = $saveJsonResponse->data;
}

$_SESSION["registration_code"] = $saveJsonData->registration_code;

if ($_SESSION["promo_code"] != "") {
    $useCodeRequest = array(
        "registration_code" => $_SESSION["registration_code"],
        "promotional_code" => $_SESSION["promo_code"]
    );

    $useCodeResponse = CallAPIWithoutAuth("POST", "promo_code/use_code", $useCodeRequest);
}



$error = "";
if (isset($_GET["error"])) {
    $error = $_GET["error"];
}
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
    <link href="css/animate.css" rel="stylesheet" media="screen">
    <link href="css/css-index.css" rel="stylesheet" media="screen">

    <!-- Google Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic" />

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-CYP4SP401L"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'G-CYP4SP401L');
    </script>
</head>

<body>
    <div class="force-mobile">
        <div class="force-mobile-inner">
            <div class="content-force-mobile">
                <div class="black-background" style=" width:100%;height:100%;font-family: Montserrat, sans-serif;background-image: url('images/information_header.png'); background-size:cover; background-position: center;">
                    <div class="row text-center" style="padding-top:40px;color:#fff">
                        <h2 style="font-family: Montserrat, sans-serif;font-size:24px; font-weight:700;">RINGKASAN PESANAN</h2>
                    </div>
                    <div class="row" style="color:#fff; margin-top:20px">
                        <div class="col-md-12" style="padding-left:50px; padding-right: 50px;font-size:18px;">
                            <div
                                style="padding-top:20px;padding-left:30px; padding-right: 30px;font-size:18px;border-radius: 15px;background-color:#fff;color:black">
                                <h6>Mohon menunggu sebentar.</h6>
                                <?php
                                if ($_SESSION["charged_amount"] > 0) {
                                    echo "<h6>Anda akan diarahkan ke Xendit untuk pembayaran.</h6>";
                                } else {
                                    echo "<h6>Order anda sedang diproses.</h6>";
                                }
                                ?>
                                <h6 id="link"></h6>
                                <div class="text-center">
                                    <label>
                                        <?php
                                        if ($error != "" && $error != null) {
                                            echo '<h4><font color="#f00">' . $error . '</font></h4>';
                                        }
                                        ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="height: 100px;color:#fff">
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/user-journey.js"></script>
    <script>
        LogVisit(<?php echo ($_SESSION["event_id"] == $GLOBALS['event_id_surabaya']) ? '"/surabaya"' : '""' ;?>);

        let error_phone;
        let hasSubmit = false;

        var jsonBody = {
            external_id: "invoice_rnt_<?php echo $saveJsonData->registration_code; ?>",
            amount: <?php echo $_SESSION["charged_amount"] ?>,
            description: "Pembelian tiket <?php echo $selectedPackage->title; ?>",
            invoice_duration: 3600,
            success_redirect_url: "<?php echo $GLOBALS['uri']; ?>/<?php echo $GLOBALS['app_name']; ?>/finish.php",
            failure_redirect_url: "<?php echo $GLOBALS['uri']; ?>/<?php echo $GLOBALS['app_name']; ?>/expired.php"
        };

        $(document).ready(function () {
            if (jsonBody.amount > 0) {
                $.ajax({
                    url: 'https://api.xendit.co/v2/invoices',
                    type: 'POST',
                    headers: {
                        "Authorization": "Basic " + btoa("<?php echo $GLOBALS['xendit_api_key']; ?>" + ":")
                    },
                    data: JSON.stringify(jsonBody),
                    contentType: "application/json; charset=utf-8",
                    success: function (json) {
                        let redirectUrl = json.invoice_url;

                        window.location.href = redirectUrl;
                    }
                });
            } else {
                $.ajax({
                    url: './scripts/process_order.php',
                    type: 'POST',
                    data: {
                        external_id: "invoice_rnt_<?php echo $saveJsonData->registration_code; ?>",
                        token: "<?php echo $_SESSION['csrftoken'] ?? '' ?>"
                    },
                    dataType: 'json',
                    success: function (json) {
                        window.location.href = json.url;
                    }
                });
            }
        });
    </script>
</body>

</html>