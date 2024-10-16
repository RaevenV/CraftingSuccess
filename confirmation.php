<?php
include './scripts/include/functions.php';
session_start(); // Starting Session

if (!isset($_SESSION['csrftoken'])) {
    $_SESSION['csrftoken'] = md5(uniqid(mt_rand(), true));
}

if (!isset($_SESSION["info_data"])) {
    header("location: ./");
}


$eventId = $_SESSION["event_id"];
$packageId = $_SESSION["package_id"];
$qty = $_SESSION["qty"];

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

// have to calculate price total here
$totalPurchases = array();

$package = array(
    "title" => $selectedPackage->title,
    "price" => $selectedPackage->price,
    "qty" => $qty
);

$discountableTicketPrice = (int) $selectedPackage->price;

array_push($totalPurchases, $package);

$item = array();
if (isset($_SESSION["package_data"])) {
    foreach ($_SESSION["package_data"] as $order) {
        if (isset($item[$order["item_id"]])) {
            $item[$order["item_id"]] = (int) $item[$order["item_id"]] + (int) $order["count"];
        } else {
            $item[$order["item_id"]] = (int) $order["count"];
        }
    }
}

$selectedItemsArray = array();

foreach ($item as $key => $row) {
    $id = explode("-", $key);
    $itemId = $id[0];
    $productId = isset($id[1]) ? $id[1] : null;

    $itemResponse = CallAPIWithoutAuth("GET", "event_item/$itemId");

    // event_item.type = 3 is for Single Claim Item repurposed as merchandise
    if ($itemResponse->data->type != $GLOBALS['item_type_id_for_merch']) {
        $package = array(
            "title" => $itemResponse->data->title,
            "price" => $itemResponse->data->price,
            "qty" => $row
        );
    } else {
        $productResponse = CallAPIWithoutAuth("GET", "product/$productId");

        $package = array(
            "title" => $productResponse->data->name,
            "price" => $productResponse->data->price,
            "qty" => $row
        );
    }

    array_push($totalPurchases, $package);

    $selectedItemsArray[$key] = $row;
}

$_SESSION["selected_items"] = json_encode($selectedItemsArray);

$total = 0;
foreach ($totalPurchases as $item) {
    $total += (int) $item["price"] * (int) $item["qty"];
}

$isDiscountApplied = false;
$finalPrice = $total;

if ($_SESSION["promo_code"] != "") {
    $discountedRequest = array(
        "price" => $discountableTicketPrice,
        "code" => $_SESSION["promo_code"],
        "event_id" => $eventId,
    );

    $discountedResponse = CallAPIWithoutAuth("POST", "promo_code/check_code", $discountedRequest);
    if (!$discountedResponse->error) {
        $discountedData = $discountedResponse->data;

        $isDiscountApplied = $discountedData->is_discount_applied;
        $finalPrice = $total - $discountedData->discounted_amount;
    }
}

// if it turns out the discount is not applicable, nullify it
// this is used for the next page so it doesn't add it to the transaction
if (!$isDiscountApplied) {
    $_SESSION["promo_code"] = "";
}

$_SESSION["ticket_price"] = $discountedData->final_price ?? $discountableTicketPrice;
$_SESSION["charged_amount"] = $finalPrice;

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
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GJ7KCY26CT"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'G-GJ7KCY26CT');
    </script>
</head>

<body>
    <div class="force-mobile">
        <div class="force-mobile-inner">
            <div class="content-force-mobile">
                <div class="black-background" style=" width:100%;height:100%;font-family: Montserrat, sans-serif;;background-image: url('images/information_header.png'); background-size:cover; background-position: center;">
                    <div class="row text-center" style="padding-top:40px;;color:#fff;margin-bottom : 20px;">
                        <h2 style="font-family: Montserrat, sans-serif;font-size:24px; font-weight:800;">RINGKASAN PESANAN</h2>
                    </div>
                    <div class="row" style="color:#fff">
                        <div class="col-md-12" style="padding-left:50px; padding-right: 50px;font-size:18px;">
                            <div
                                style="padding-top:20px;padding-left:30px; padding-right: 30px;font-size:18px;border-radius: 15px;background-color:#fff;color:black">

                                <?php foreach ($totalPurchases as $item) { ?>
                                    <div
                                        style="border:1px solid #ccc;border-radius:10px;padding-left:10px;padding-right:10px;margin-bottom:20px">
                                        <div>
                                            <h6>
                                                <?php echo $item["qty"] . 'x ' . $item["title"]; ?>
                                            </h6>
                                        </div>
                                        <div style="display: flex; justify-content: flex-end;">
                                            <h5>
                                                <?php echo 'Rp ' . number_format($item["price"] * $item["qty"], 0, ',', '.') . '.-'; ?>
                                            </h5>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div><a href="./package.php"><h6 style="font-size:14px">Tambah Item</h6></a></div>

                                <div style="display: flex; justify-content: space-between;">
                                    <div style="font-size:20px">
                                        <?php
                                        if ($isDiscountApplied) {
                                            echo "<h5 >Subtotal</h5>";
                                        } else {
                                            echo "<h5>Total</h5>";
                                        }
                                        ?>
                                    </div>
                                    <div style="font-size: 18px;">
                                        <h6>
                                            Rp.
                                            <?php echo number_format($total, 0, ',', '.') . '.-'; ?>
                                        </h6>
                                    </div>
                                </div>

                                <?php if ($isDiscountApplied) { ?>
                                    <div style="display: flex; justify-content: space-between;">
                                        <div>
                                            <h6>Diskon</h6>
                                        </div>
                                        <div>
                                            <h6>
                                                - Rp.
                                                <?php echo number_format($total - $finalPrice, 0, ',', '.') . '.-'; ?>
                                            </h6>
                                        </div>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <div>
                                            <h4>Total</h4>
                                        </div>
                                        <div>
                                            <h4>
                                                Rp.
                                                <?php echo number_format($finalPrice, 0, ',', '.') . '.-'; ?>
                                            </h4>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div style="font-size: 16px;font-weight: 400;text-align: right;">Harga sudah termasuk PPN 11%</div>

                                <form class="form-header" action="./payment.php" role="form" method="POST" id="form">
                                    <input type="hidden" name="token"
                                        value="<?php echo $_SESSION['csrftoken'] ?? '' ?>">

                                    <input type="hidden" name="package_id" value="<?php echo $packageId; ?>">
                                    <input type="hidden" name="qty" value="<?php echo $qty; ?>">
                                    <input type="hidden" name="event_id" value="<?php echo $eventId; ?>">
                                    <input type="hidden" name="name" value="<?php echo $name; ?>">
                                    <input type="hidden" name="email" value="<?php echo $email; ?>">
                                    <input type="hidden" name="phone" value="<?php echo $phone; ?>">

                                    <div class="form-group last" style="margin-top: 64px; display: flex; justify-content: center;">
                                        <input type="submit" class="hov" style="
                                            background-color:#0EBEFF ;
                                            width: 250px;
                                            border-radius: 8px;
                                            color: #fff;
                                            font-size: 18px !important;
                                            transition: background-color 0.3s ease, transform 0.3s ease;
                                            border:none;
                                            font-weight:400;
                                            cursor:pointer;
                                            height:45px;
                                            font-weight:700
                                            " 
                                            onmouseover=" this.style.transform='scale(1.015)';"
                                            onmouseout=" this.style.transform='scale(1)';"
                                            value="Lanjut ke pembayaran"
                                            >
                                    </div>
                                </form>
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
        LogVisit(<?php echo ($eventId == $GLOBALS['event_id_surabaya']) ? '"/surabaya"' : '""'; ?>);
    </script>
</body>

</html>