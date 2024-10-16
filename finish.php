<?php
include './scripts/include/functions.php';
session_start(); // Starting Session

if (!isset($_SESSION['csrftoken'])) {
    $_SESSION['csrftoken'] = md5(uniqid(mt_rand(), true));
}

if (!isset($_SESSION["registration_code"])) {
header("location: ./");
}

$regCode = array(
    "registration_code" => $_SESSION["registration_code"]
);

$response = CallAPIWithoutAuth("POST", "reserve_ticket/check_reservation_code", $regCode);

if ($response->error) {
    header("location: ./");
}

$info_data = isset($_SESSION["info_data"]) ? $_SESSION["info_data"] : null;


unset($_SESSION["promo_code"]);
unset($_SESSION["package_data"]);

$packageId = $_SESSION["package_id"];


foreach ($_SESSION["info_data"] as $registrar) {
        
        $participantId = null;
        $participantIdResponse = null;

        $phoneNum = $registrar["phone"];

        if ($phoneNum && $_SESSION["isAlumni"] == 2) {
        // Call the API
            $participantIdResponse = CallAPIWithoutAuth("POST", "participant/$phoneNum/getID");


            // Handle the response
            if (!$participantIdResponse->error && isset($participantIdResponse->data->participant_id)) {
                $participantId = $participantIdResponse->data->participant_id;
            } else {
                $participantId = null;
                header("location: ./finish.php?error=" . $participantIdResponse->message);
                
            }
        }


        if($registrar["work"]!=null){
            $workQuestions = [];
            $workQuestion = array(
                "question_id" => 385,
                "answer" => $registrar["work"],
            );
            array_push($workQuestions, $workQuestion);

            $workData = array(
                'questions' => json_encode($workQuestions),
                'event_id' => $_SESSION["event_id"],
            );

            $workResponse = CallAPIWithoutAuth("POST", "participant/" . $participantId . "/update_answers", $workData);
            if ($workResponse->error) {
                header("location: ../finish.php?error=" . $workResponse->message);
            }
        }

        //durasiMLCT
        if($registrar["durasiMLCT"]!=null){
            $durasiMLCTQuestions = [];
            $durasiMLCTQuestion = array(
                "question_id" => 386,
                "answer" => $registrar["durasiMLCT"],
            );
            array_push($durasiMLCTQuestions, $durasiMLCTQuestion);

            $durasiMLCTData = array(
                'questions' => json_encode($durasiMLCTQuestions),
                'event_id' => $_SESSION["event_id"],
            );

            $durasiMLCTResponse = CallAPIWithoutAuth("POST", "participant/" . $participantId . "/update_answers", $durasiMLCTData);
            if ($durasiMLCTResponse->error) {
                header("location: ../finish.php?error=" . $durasiMLCTResponse->message);
            }
        }

        //Kehadiran
        if($registrar["kehadiran"]!=null){
            $kehadiranQuestions = [];
            $kehadiranQuestion = array(
                "question_id" => 387,
                "answer" => $registrar["kehadiran"],
            );
            array_push($kehadiranQuestions, $kehadiranQuestion);

            $kehadiranData = array(
                'questions' => json_encode($kehadiranQuestions),
                'event_id' => $_SESSION["event_id"],
            );

            $kehadiranResponse = CallAPIWithoutAuth("POST", "participant/" . $participantId . "/update_answers", $kehadiranData);
            if ($kehadiranResponse->error) {
                header("location: ../finish.php?error=" . $kehadiranResponse->message);
            }
        }

        //Social Media
        if($registrar["social_media"]!=null){
            $social_mediaQuestions = [];
            $social_mediaQuestion = array(
                "question_id" => 388,
                "answer" => $registrar["social_media"],
            );
            array_push($social_mediaQuestions, $social_mediaQuestion);

            $social_mediaData = array(
                'questions' => json_encode($social_mediaQuestions),
                'event_id' => $_SESSION["event_id"],
            );

            $social_mediaResponse = CallAPIWithoutAuth("POST", "participant/" . $participantId . "/update_answers", $social_mediaData);
            if ($social_mediaResponse->error) {
                header("location: ../finish.php?error=" . $social_mediaResponse->message);
            }
        }

        //CoachSpeakerTrainer
        if($registrar["coachspeakertrainer"]!=null){
            $coachspeakertrainerQuestions = [];
            $coachspeakertrainerQuestion = array(
                "question_id" => 389,
                "answer" => $registrar["coachspeakertrainer"],
            );
            array_push($coachspeakertrainerQuestions, $coachspeakertrainerQuestion);

            $coachspeakertrainerData = array(
                'questions' => json_encode($coachspeakertrainerQuestions),
                'event_id' => $_SESSION["event_id"],
            );

            $coachspeakertrainerResponse = CallAPIWithoutAuth("POST", "participant/" . $participantId . "/update_answers", $coachspeakertrainerData);
            if ($coachspeakertrainerResponse->error) {
                header("location: ../finish.php?error=" . $coachspeakertrainerResponse->message);
            }
        }
    }

if ($_SESSION["event_id"] == $GLOBALS['event_id']) {
    $welcomeImg = "images/index-header.jpg";
} else if ($_SESSION["event_id"] == $GLOBALS['event_id_surabaya']) {
    $ticketImg = "images/ticket-surabaya.png";
    $welcomeImg = "images/welcome-sby.png";
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
    <link href="fonts/icon-7-stroke/css/pe-icon-7-stroke.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet" media="screen">
    <link href="css/owl.theme.css" rel="stylesheet">
    <link href="css/owl.carousel.css" rel="stylesheet">

    <!-- Colors -->
    <link href="css/css-index.css" rel="stylesheet" media="screen">

    <!-- Google Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic" />

    <style>
        .loader {
            position: relative;
            left: calc(50% - 60px);
            top: 40px;
            z-index: 1;
            width: 120px;
            height: 120px;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GJ7KCY26CT"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'G-GJ7KCY26CT');
    </script>

</head>

<body data-spy="scroll" data-target="#navbar-scroll">
    <div class="force-mobile" >
        <div class="force-mobile-inner" style="overflow-y: hidden;">
            <div class="content-force-mobile black-background">
                <div class="row text-center" style="color:#fff;margin-bottom:20px">
                    <img id="img-placeholder3" src="<?php echo $welcomeImg; ?>" style="width:100%;height:350px;">
                </div>
                <div class="row" style="color:#fff;">
                    <div class="col-md-12" style="padding-left:50px; padding-right: 50px;font-size:18px;">
                        <div class="text-center" style="margin-top:10px;padding:20px;font-size:18px;border-radius: 15px;background-color:#fff;color:black">
                            
                            <div id="qrContainer" style="margin-top: 10px;" >
                                <div id="loader" class="loader"></div>
                                <div id="qrcode" style="margin:auto"></div>
                                
                            </div>
                            <!-- <?php if($packageId == 151 || $packageId == 152 || $packageId == 156): ?>
                                <div style="padding-top:30px;font-size:22px;color:black;font-weight:700;">
                                    Congratulations, you will get 12 credits to any of our events!
                                </div>
                            <?php endif; ?> -->

                            <div style="padding-top:30px;font-size:22px;color:black;font-weight:700;">
                                Welcome to the event!
                            </div>
                            
                            
                            <div>
                                <h4 style="margin-top:20px" id="transact_code">Order ID: <?php echo $_SESSION["registration_code"]; ?></h4>
                            </div>
                            
                            <!-- <div style="margin-top: 70px;margin-bottom:40px">
                                <img src="<?php echo $ticketImg; ?>" style="width:100%;height:120px;margin-top:-40px;">
                                
                            </div> -->
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- /.javascript files -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/qrcode.min.js"></script>
    <script src="js/user-journey.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var indexHeaderImg = document.querySelector('#img-placeholder3');
            var contentForceMobile = document.querySelector('.content-force-mobile');
            function updateImageHeight() {
                if (contentForceMobile.offsetWidth > 480) {
                    indexHeaderImg.style.height = '450px';
                } else {
                    indexHeaderImg.style.height = '360px';
                }
            }

            updateImageHeight();
            window.addEventListener('resize', updateImageHeight);
        });
    </script>
    <script>
        LogVisit(<?php echo ($_SESSION["event_id"] == $GLOBALS['event_id_surabaya']) ? '"/surabaya"' : '""' ;?>);

        var qrCodeObtained = false;
        var loopCount = 0;
        var loopMax = 15;

        $(function () {
            GetQrCode();
        });

        function myLoop() {
            setTimeout(function () {
                GetQrCode();
            }, 3000);
        }

        function GetQrCode() {
            $.ajax({
                type: 'GET',
                url: '<?php echo $GLOBALS['url_server_api']; ?>reserve_ticket/check_status_ticket',
                data: { code: "<?php echo $_SESSION["registration_code"] ?>" },
                success: function (json) {
                    if (!json.error) {
                        document.getElementById("loader").style.display = "none";
                        var qrContainer = document.getElementById("qrContainer");

                        json.data.forEach(function (item, index) {
                            // Create a div for each QR code
                            var qrDiv = document.createElement("div");
                            qrDiv.id = "qrcode_" + index;
                            
                            qrContainer.appendChild(qrDiv);

                            // Generate QR code
                            var divWidth = $(qrDiv).width();
                            var actualWidth = Math.min(300, divWidth);
                            $(qrDiv).width(actualWidth);
                            qrDiv.style = "display:flex;justify-content:center;align-items:center;margin-bottom:20px;"

                            new QRCode(qrDiv, {
                                text: "ticket-" + item.special_code,
                                width: actualWidth,
                                height: actualWidth,
                            });

                            // Create a div for the name
                            var nameDiv = document.createElement("div");
                            nameDiv.innerHTML = "<h3>" + item.participant.name + "</h3>";
                            nameDiv.style = "margin-bottom:20px;"
                            qrContainer.appendChild(nameDiv);
                        });
                    } else {
                        console.error("Failed to retrieve QR codes");
                    }
                }
            });
        }

        function OutputError() {
            console.log("After " + loopCount + " attempts, could not obtain the qrcode");
        }

        var downloadBtn = document.getElementById('downloadBtn');
        downloadBtn.addEventListener('click', function () {
            // Create a temporary anchor element
            let downloadLink = document.createElement('a');
            downloadLink.target = "_blank";
            // Set the href attribute to the image source
            downloadLink.href = "<?php echo $GLOBALS['url_server_api']; ?>reserve_ticket/download_ticket?registration_code=<?php echo $_SESSION["registration_code"] ?>";
            // Set the download attribute to specify the file name
            downloadLink.download = 'qrcode.png';
            // Append the anchor element to the document body
            document.body.appendChild(downloadLink);
            // Programmatically trigger a click event on the anchor element
            downloadLink.click();
            // Remove the anchor element from the document body
            document.body.removeChild(downloadLink);
        });
    </script>
</body>

</html>

<?php unset($_SESSION["info_data"]); ?>
<?php unset($_SESSION["package_id"]); ?>