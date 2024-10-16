<?php
include './scripts/include/functions.php';
session_start(); // Starting Session

if (!isset($_SESSION['csrftoken'])) {
    $_SESSION['csrftoken'] = md5(uniqid(mt_rand(), true));
}

if (
    !isset($_SESSION["event_id"]) ||
    !isset($_SESSION["package_id"]) ||
    !isset($_SESSION["promo_code"]) ||
    !isset($_SESSION["qty"])||
    !isset($_SESSION["isAlumni"])
) {
    header("location: ./");
}

$qty = $_SESSION["qty"];
$isAlumni = $_SESSION["isAlumni"];


$error = "";
if (isset($_GET["error"])) {
    $error = $_GET["error"];
}

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
                <div class="black-background" style="min-height: 100vh; display:flex; flex-direction:column; justify-content:center; align-items:center; background-color:#BB2819; row-gap:30px; background-image: url('images/information_header.png'); background-size:cover; background-position: center;">
                    <div class="row text-center" style="width:100%;margin:0;padding-top:40px;color:#fff;">
                        <h2 style="font-weight:800; font-size:28px;">DETAIL PEMESANAN</h2>
                    </div>
                    <div class="row" style="width:100%;margin:0;color:#fff;margin-bottom:60px;">
                        <div class="col-md-12" style=" font-size:18px; font-weight:700;display:flex; justify-content:center; align-items:center; padding:0;">
                            <form class="form-header" action="./confirmation.php" role="form" method="POST" id="form"
                                style="font-family:Arial, Helvetica, sans-serif;height:auto;width:90%;padding-top:30px;padding-left:8%;padding-right: 8%;font-size:16px;border-radius: 24px;background-color:#fff;color:black">

                                <?php for ($i = 0; $i < $qty; $i++) { ?>
                                    <?php if ($qty > 1) {
                                        echo "<h3 style='font-family: Arial, Helvetica, sans-serif;font-size: 20px; font-weight: bold; margin-top: 20px;'>Participant " . ($i + 1) . "</h3>";
                                    } ?>

                                    <label style=" font-weight:700; margin-top:10px; ">Nama Lengkap</label>
                                    <div class="form-group" style="margin-bottom:24px;margin-top: 8px;">
                                        <input class="form-control input-lg" style="border-radius: 8px;font-size:16px;font-weight:400;color:#1A1A1A;" name="name[]"
                                            id="name" type="text" required>
                                    </div>

                                    <label >No Handphone (WA)</label>
                                    <div class="form-group" style="margin-bottom:24px;margin-top: 8px;">
                                        <div
                                            style="border-radius: 8px;border: solid 1px #dadada; background-color: #fff;color: #000;height: 55px">
                                            <div style="display: inline-block">
                                                <span
                                                    style="padding-left: 16px; padding-right: 16px; border-right: 2px solid #dadada;vertical-align:middle;font-size:16px;">+62</span>
                                                <input
                                                    style="height:50px;width:70%;border:0px;outline:none;vertical-align:middle;background-color:transparent; font-weight:400;font-size:16px;"
                                                    type="tel" id="phone" name="phone[]" placeholder="8xxxxxxxxx" value=""
                                                    maxLength="13" pattern="[0-9]{8,13}"
                                                    title="Silahkan masukan hanya angka nomor handphone" required />
                                            </div>
                                        </div>
                                        <h4 style="color:white; font-size: 12px" id="phone_error"></h4>
                                    </div>
                                    <label>Email</label>
                                    <div class="form-group" style="margin-bottom:8px;margin-top: 8px;">
                                        <input class="form-control input-lg" style="border-radius: 8px; font-weight:400; font-size:16px;color:#1A1A1A;" name="email[]"
                                            id="email" type="email" placeholder="Optional"
                                            title="Silahkan masukan alamat email yang benar">
                                        <h4 style="color:red; font-size: 12px" id="email_error"></h4>
                                    </div>

                                <?php } ?>

                                <div class="form-group last"
                                    style="margin-top: 50px;display: flex; justify-content: center;">
                                    <input type="submit" class=""
                                        style="
                                            background-color: #0EBEFF;
                                            width: 150px;
                                            border-radius: 8px;
                                            color: #fff;
                                            font-size: 20px !important;
                                            transition: background-color 0.3s ease, transform 0.3s ease;
                                            border:none;
                                            font-weight:400;
                                            cursor:pointer;
                                            height:45px;
                                            font-weight:700
                                            " 
                                            onmouseover=" this.style.transform='scale(1.015)';"
                                            onmouseout=" this.style.transform='scale(1)';"
                                        value="Next">
                                </div>

                                <div class="text-center" style="margin-bottom:20px;">
                                    <label id="error_message">
                                        <?php
                                        if ($error != "" && $error != null) {
                                            echo '<h4><font color="#f00">' . $error . '</font></h4>';
                                        }
                                        ?>
                                    </label>
                                </div>
                            </form>
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
        LogVisit(<?php echo ($_SESSION["event_id"] == $GLOBALS['event_id_surabaya']) ? '"/surabaya"' : '""' ;?>);

        let error_phone;
        let hasSubmit = false;

        document.querySelector('form').addEventListener('submit', function (_event) {
            _event.preventDefault();

            var temp;
            var dataObject = [];
            var invalidPhoneNumber = [];
            var allPhonesValid = true;

            let tgl_lahir_elements = document.querySelectorAll("input[name='tgl_lahir[]']");
            let work_elements = document.querySelectorAll("input[name='work[]']");
            let durasiMLCT_elements = document.querySelectorAll("input[name='durasiMLCT[]']");
            let kehadiran_elements = document.querySelectorAll("select[name='kehadiran[]']");
            let social_media_elements = document.querySelectorAll("input[name='social_media[]']");


            for (var i = 0; i < $("input[name='name[]']").length; i++) {
                let phoneNumber = $("input[name='phone[]']")[i].value;

                if (phoneNumber.startsWith('0')) {
                    // If it does, remove the first character
                    phoneNumber = phoneNumber.substring(1);
                }

                $.ajax({
                    type: 'POST',
                    url: '<?php echo $GLOBALS['url_server_api']; ?>event/<?php echo $_SESSION["event_id"]; ?>/participant/check',
                    data: {
                        phone: phoneNumber
                    },
                    async: false,
                    dataType: 'json',
                    cache: false,
                    success: function (json) {
                        if (json.error == false || json.error == true && json.message == "Register") {
                            // this is fine, continue
                        } else {
                            allPhonesValid = false;
                            invalidPhoneNumber.push(phoneNumber);
                        }
                    }
                });

                let tempMail = $("input[name='email[]']")[i].value;
                if (tempMail.match(/^ *$/) !== null) {
                    tempMail = null;
                }

                let tgl_lahir = tgl_lahir_elements[i] ? tgl_lahir_elements[i].value : '';
                let work = work_elements[i] ? work_elements[i].value : '';
                let durasiMLCT = durasiMLCT_elements[i] ? durasiMLCT_elements[i].value : '';
                let kehadiran = kehadiran_elements[i] ? kehadiran_elements[i].value : '';
                let social_media = social_media_elements[i] ? social_media_elements[i].value : '';

                // Collect all selected coachspeakertrainer checkboxes for this participant
                let coachspeakertrainer_elements = document.querySelectorAll(`input[name='coachspeakertrainer[${i}][]']:checked`);
                let coachspeakertrainer = Array.from(coachspeakertrainer_elements).map(element => element.value).join(', ');

                temp = {
                    id: i,
                    name: $("input[name='name[]']")[i].value,
                    email: tempMail,
                    phone: phoneNumber,
                    tgl_lahir: tgl_lahir,
                    work: work,
                    durasiMLCT:durasiMLCT,
                    kehadiran:kehadiran,
                    social_media:social_media,
                    coachspeakertrainer:coachspeakertrainer,
                }

                dataObject.push(temp);
            }

            if (allPhonesValid) {
                $.ajax({
                    type: 'POST',
                    url: "./session/session.information.php",
                    data: {
                        data: dataObject
                    },
                    dataType: 'json',
                    cache: false,
                    success: function (json) {
                        window.location.href = "confirmation.php";
                    }
                });
            } else {
                $("input[type='submit']").val("Lanjut");
                $("#error_message").empty();
                $("#error_message").append("<font color=\"red\">The following phone number(s) already own a ticket: " + invalidPhoneNumber.join(', ') + " </font>");
            }
        });

        $(document).ready(function () {
            
        });

        $('#name').keyup($.debounce(1000, () => LogAction(<?php echo ($_SESSION["event_id"] == $GLOBALS['event_id_surabaya']) ? '"/surabaya"' : '""' ;?>, "name", $("#name").val())));
        $('#phone').keyup($.debounce(1000, () => LogAction(<?php echo ($_SESSION["event_id"] == $GLOBALS['event_id_surabaya']) ? '"/surabaya"' : '""' ;?>, "phone", $("#phone").val())));
        $('#email').keyup($.debounce(1000, () => LogAction(<?php echo ($_SESSION["event_id"] == $GLOBALS['event_id_surabaya']) ? '"/surabaya"' : '""' ;?>, "email", $("#email").val())));
    </script>
</body>

</html>