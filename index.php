<?php
include './scripts/include/functions.php';
session_start(); // Starting Session

if (!isset($_SESSION['csrftoken'])) {
    $_SESSION['csrftoken'] = md5(uniqid(mt_rand(), true));
}


$indexHeader = "images/index-header.png";


if (isset($_GET["event_id"])) {
    $eventId = $GET['event_id'];
} else {
    $eventId = $GLOBALS['event_id'];
}

$responseEventPackage = CallAPIWithoutAuth("GET", "event/$eventId/packages");
if (!$responseEventPackage->error) {
    $eventPackages = $responseEventPackage->data;
} else {
    echo "Error obtaining package list";
    exit;
}



$selectEventPackage = $eventPackages[0];




$packageId = $selectEventPackage->id;

$remaining = $selectEventPackage->quantity - $selectEventPackage->bought;



$jsonEventPackages = json_encode($eventPackages);

$error = "";
if (isset($_GET["error"])) {
    $error = $_GET["error"];
}

$soldOut = false;
if ($remaining <= 0) {
    $soldOut = true;
}

$alumniCode = "AG198HRL";
$isAlumni = 1;


unset($_SESSION["selected_items"]);

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
    <style>
        .form-group input[type="text"],
        .form-group button {
            margin-right: 5px;
        }
    </style>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-CYP4SP401L"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'G-CYP4SP401L');
    </script>
</head>

<body style="overflow-y: hidden;">
    <div class="force-mobile">
        <div class="force-mobile-inner">
            <div class="content-force-mobile">
                <div class="black-background"  style="min-height:100vh;">
                    <div class="row text-center" id="img-placeholder2" style="margin:0;height:325px;margin-bottom:40px;">
                        <img src="<?php echo $indexHeader; ?>" style="width: 100%;height: 100%;border-radius:0 0 8px 8px;">
                    </div>
                    <div class="row" style="margin:0;">
                        <div class="col-md-12" style="padding-left:40px; padding-right: 40px;font-size:18px;">
                            <form class="form-header" action="./information.php" role="form" method="POST" id="form">
                                <input type="hidden" id="package_id" name="package_id"
                                    value="<?php echo $packageId; ?>">

                                <?php
                                ?>
                                <label>Harga</label>
                                <div class="form-group" style="margin-bottom:16px;margin-top: 8px;">
                                    <div
                                        style="border-radius: 8px;border: solid 1px #dadada; background-color: #fff;color: #000;height: 55px">
                                        <div style="display: flex">
                                            <span
                                                style="padding-left: 16px; padding-right: 16px; border-right: 2px solid #dadada;display:flex;justify-content:center;align-items:center;">Rp.</span>
                                            <input
                                                style="height:50px;border: 0px; outline: none;vertical-align:middle;background-color:transparent;width:100%;"
                                                type="text" id="subtotal" name="subtotal" value="0" readonly />
                                        </div>
                                    </div>
                                </div>
                                <label hidden style="margin-top: 16px;">Quantity</label>
                                <div hidden class="form-group" style="margin-bottom:16px;margin-top: 8px;">
                                    <div hidden style="border-radius: 8px;border: solid 1px #dadada; background-color: #fff;color: #000;height: 55px">
                                        
                                        <input hidden style="height:50px;border: 0px; outline: none;vertical-align:middle;background-color:transparent;width:100%;padding-left:20px" type="text" id="qty" name="qty" value="1">
                                        
                                    </div>
                                </div>
                                
                                <label style="margin-top: 20px;">Kode Promo</label>
                                <div class="form-group"
                                    style="margin-bottom: 8px; margin-top: 8px;display:flex;align-items:center;cursor:pointer; ">
                                    <div style="flex:1; width: calc(60% - 8px); cursor:pointer; ">
                                        <input class="form-control input-lg"
                                            style="border-radius: 8px; display: inline-block;cursor:pointer; " name="promo_code"
                                            id="promo_code" type="text">
                                    </div>
                                    <div style="margin-left:8px;width: calc(30% - 8px);">
                                        <button type="button" class="btn btn-block btn-warning btn-lg"
                                            style="background-color: #0EBEFF; color: #fff; font-size: 20px !important; border-radius: 8px; display: inline-block; height:46px;padding:0px;font-weight:700"
                                            onmouseover=" this.style.transform='scale(1.015)';"
                                            onmouseout=" this.style.transform='scale(1)';"
                                            id="use_code_button" onclick="CheckPromoCode()">CLAIM</button>
                                    </div>
                                </div>
                                <div>Masukkan kode promo (jika ada) dan klik tombol klaim</div>
                                <div style="height:50px;margin-top:20px;margin-bottom:20px">
                                    <div id="promo_message" class="alert alert-success" style="display:none;"></div>
                                </div>
                                <div class="form-group last"
                                    style="margin-top: 10px; margin-bottom:21px;display: flex; justify-content: center;">
                                    <input type="submit" class=""
                                        style="
                                            background-color: #4F0097;
                                            width: 150px;
                                            border-radius: 8px;
                                            color: #fff;
                                            font-size: 20px !important;
                                            transition: background-color 0.3s ease, transform 0.3s ease;
                                            border:none;
                                            font-weight:400;
                                            cursor:pointer;
                                            height:45px;
                                            font-weight:700;
                                            margin-bottom:40px;
                                            " 
                                            onmouseover=" this.style.transform='scale(1.015)';"
                                            onmouseout=" this.style.transform='scale(1)';"
                                        value="<?php echo $soldOut ? "Sold Out" : "Next"; ?>" <?php echo $soldOut ? "disabled" : ""; ?>>
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
            </div>
        </div>

    </div>

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/user-journey.js"></script>
    <script src="js/jquery.ba-throttle-debounce.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var indexHeaderImg = document.querySelector('#img-placeholder2');
            var contentForceMobile = document.querySelector('.content-force-mobile');
            function updateImageHeight() {
                if (contentForceMobile.offsetWidth > 480) {
                    indexHeaderImg.style.height = '500px';
                } else {
                    indexHeaderImg.style.height = '400px';
                }
            }

            updateImageHeight();
            window.addEventListener('resize', updateImageHeight);
        });
    </script>
    <script>

        var jsArray = <?php echo $jsonEventPackages; ?>;
        let hasSubmit = false;
        var isClaimCode = false;
        var totalPrice = 0;
        const eventId = <?php echo $eventId ?>;
        var isAlumni = <?php echo json_encode($isAlumni); ?>;
        var alumniCode = <?php echo json_encode($alumniCode)?>;
        var isAlumni2;
        
        document.querySelector('form').addEventListener('submit', function (_event) {
            _event.preventDefault();

            let promoCode = "";
            if (isClaimCode) {
                promoCode = $("#promo_code").val().trim();
            }

            if(isAlumni == 2){
                isAlumni2 = 2;
            }else{
                isAlumni2 = 1;
            }

            
            $.ajax({
                type: 'POST',
                url: "./session/session.index.php",
                data: {
                    event_id: "<?php echo $eventId; ?>",
                    package_id: $("#package_id").val(),
                    promo_code: promoCode,
                    qty: $("#qty").val(),
                    isAlumni: isAlumni2
                },
                dataType: 'json',
                cache: false,
                success: function (json) {
                    window.location.href = "information.php";
                }
            });
        });

        $(document).ready(function () {
            UpdatePrice();
        });

        function UpdatePrice() {
            var pkg_id = document.getElementById('package_id').value;
            var qty = document.getElementById('qty').value;

            var price = 0;

            for (let i = 0; i < jsArray.length; i++) {
                if (jsArray[i].id == pkg_id) {
                    price = jsArray[i].price;
                    break;
                }
            }

            totalPrice = price * qty;
            document.getElementById('subtotal').value = (price * qty).toLocaleString('id', { useGrouping: true });
        }

        function CheckPromoCode() {
            var subtotal = totalPrice;
            var code = $("#promo_code").val().trim();


            $.ajax({
                type: 'POST',
                url: "<?php echo $GLOBALS['url_server_api']; ?>promo_code/check_code",
                data: {
                    price: subtotal,
                    code: code,
                    event_id: eventId,
                },
                dataType: 'json',
                cache: false,
                success: function (json) {
                    if (json.data.is_discount_applied == true) {
                        $("#subtotal").val((json.data.final_price).toLocaleString('id', { useGrouping: true }));
                        $("#promo_message").html("<strong>Selamat!</strong> Anda hemat Rp. " + (json.data.discounted_amount).toLocaleString('id', { useGrouping: true }));
                        $("#promo_message").attr("style", "display:block;padding-left:16px;margin-bottom:10px !important;");
                        $("#promo_message").removeClass("alert-danger");
                        $("#promo_message").addClass("alert-success");
                        isClaimCode = true;
                        if ($("#promo_code").val().trim() === alumniCode) {
                            isAlumni = 2;
                        }
                    } else {
                        if (json.message) {
                            $("#promo_message").html("<strong>Error!</strong> " + json.message);
                            $("#promo_message").attr("style", "display:block;padding-left:16px;margin-bottom:20px; !important;");
                            $("#promo_message").removeClass("alert-success");
                            $("#promo_message").addClass("alert-danger");

                        } else {
                            $("#promo_message").html("");
                            $("#promo_message").attr("style", "display:none");
                        }
                        $("#subtotal").val((totalPrice).toLocaleString('id', { useGrouping: true }));
                        isClaimCode = false;
                    }
                }
            });
        }

        $('#promo_code').keyup($.debounce(1000, () => LogAction(<?php echo ($eventId == $GLOBALS['event_id_surabaya']) ? '"/surabaya"' : '""' ;?>, "promo code", $("#promo_code").val())));
    </script>
</body>

</html>