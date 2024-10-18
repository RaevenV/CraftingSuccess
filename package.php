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

$response = CallAPIWithoutAuth("GET", "event/$eventId/item/list/addon");
if (!$response->error) {
    $data = $response->data;
}

$registrarData = $_SESSION["info_data"];

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

    <style>
        .ticket {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .selected {
            background-color: #f0f0f0;
        }

        .collapsible {
            background-color: #fff;
            color: #000;
            cursor: pointer;
            padding: 10px;
            width: 100%;
            border: 1px solid #CCCCCC;
            border-radius: 5px;
            text-align: left;
            outline: none;
            font-size: 15px;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        .active,
        .collapsible:hover {
            background-color: #eee;
        }

        .content {
            display: none;
            overflow: hidden;
            background-color: #fff;
        }

        td {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        td:not(:first-child):not(:last-child) {
            padding-right: 6px;
        }

        .button-image {
            padding-left: 240px;
        }

        .tab {
            display: none;
        }

        img {
            max-width: 100%;
            max-height: 100%;
        }

        .hover{
            transition: background-color 0.3s linear;
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

<body>
    <div class="force-mobile">
        <div class="force-mobile-inner">
            <div class="content-force-mobile">
                <div class="black-background" style="font-family: Montserrat, sans-serif;background-color:#BB2819;height:100%;">
                    <div class="row text-center" style=" padding-top:40px;background-color:#BB2819;color:#fff;font-family: Montserrat, sans-serif;margin-bottom:24px;">
                        <h2 style="font-weight:800; font-size:20px;">LENGKAPI PENGALAMAN ANDA</h2>
                    </div>
                    <div class="row" style="background-color:#BB2819;color:#fff">
                        <div class="col-md-12" style="padding-left:50px; padding-right: 50px;font-size:18px">
                            <form class="form-header" action="./confirmation.php" role="form" method="POST" id="form"
                                style="padding-top:20px;padding-left:30px; padding-right: 30px;font-size:18px;border-radius: 15px;background-color:#fff;color:black">
                                <div class="tab">
                                    <div class="hover">
                                        <div  id="product" class="collapsible" onclick="showTab(2)">
                                            <div>
                                                <img src="./images/product.png" style="border-radius:5px">
                                            </div>
                                            <div style="text-align: center; width:100%;padding-top:10px">
                                                <h4 style="font-family: Montserrat,sans-serif;font-weight:700; font-size:20px;">Merchandise</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab">
                                    <?php foreach ($data as $item) { ?>
                                        <?php if ($item->type != $GLOBALS['item_type_id_for_merch']) { ?>
                                            <div id="<?php echo $item->id; ?>" class="ticket">
                                                <input type="hidden" class="button_replacement"
                                                    value="item_<?php echo $item->id; ?>">
                                                <?php echo $item->title; ?>
                                                <h6 style='font-size:16px'>
                                                    <?php echo $item->description; ?>
                                                </h6>
                                                <div style="display: flex;align-items:center;justify-content:space-between;">
                                                    <h5>Rp
                                                        <?php echo number_format($item->price, 0, ',', '.'); ?>,-
                                                    </h5>

                                                    <table id="item_<?php echo $item->id; ?>">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th style="width:0%">
                                                                </th>
                                                                <th style="width:100px"></th>
                                                                <th style="width:0%"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                
                                                <div style="display: flex; justify-content: flex-end;">
                                                    
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                    <button id="coaching_back" type="button"
                                        style="background-color:#BB2819;color:#fff;height:40px;border-radius: 10px;border:none;margin-top:10px"
                                        onclick="showTab(0)">Back</button>
                                </div>

                                <div class="tab">
                                    <?php foreach ($data as $item) { ?>
                                        <?php if ($item->type == 3) { ?>
                                            <?php foreach ($item->products as $product) { ?>
                                                <div id="<?php echo $item->id . '-' . $product->id; ?>" class="ticket">
                                                    <input type="hidden"
                                                        id="remain_item_<?php echo $item->id . '-' . $product->id; ?>"
                                                        value="<?php echo $product->remaining; ?>">
                                                    <input type="hidden" class="button_replacement"
                                                        value="item_<?php echo $item->id . '-' . $product->id; ?>">
                                                    <?php echo $product->name; ?>
                                                    <h6 style='font-size:16px'>
                                                        <?php echo $product->description; ?>
                                                    </h6>
                                                    <?php
                                                    if ($product->picture != null) {
                                                        echo "<div style='display: flex; justify-content: center;'><img src='$product->picture'></div>";
                                                    }
                                                    ?>
                                                    <br>
                                                    <div style="display: flex;align-items:center;justify-content:space-between;">
                                                        <div>
                                                            <h5>Rp
                                                                <?php echo number_format($product->price, 0, ',', '.'); ?>,-
                                                            </h5>
                                                            <h6 style='font-size:14px'>
                                                                <?php
                                                                if ($product->remaining > 0) {
                                                                    echo "Remaining: " . $product->remaining;
                                                                } else {
                                                                    echo "<font color='red'>SOLD OUT</font>";
                                                                }
                                                                ?>
                                                            </h6>
                                                        </div>
                                                        <table id="item_<?php echo $item->id . '-' . $product->id; ?>">
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th style="width:0%"></th>
                                                                    <th style="width:100px"></th>
                                                                    <th style="width:0%"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    
                                                    

                                                    
                                                    <div style="display: flex; justify-content: flex-end;">
                                                        
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                    <button id="product_back" type="button"
                                        style="background-color:#BB2819;color:#fff;height:40px;border-radius: 10px;border:none;margin-top:10px"
                                        onclick="showTab(0)">Back
                                    </button>
                                </div>

                                <div class="form-group last"
                                    style="margin-top: 40px;display: flex; justify-content: center;">
                                    <input type="submit" 
                                        style="
                                            background-color: #BB2819;
                                            width: 140px;
                                            border-radius: 8px;
                                            color: #fff;
                                            font-size: 20px !important;
                                            transition: background-color 0.3s ease, transform 0.3s ease;
                                            border:none;
                                            font-weight:400;
                                            " 
                                        value="Next"
                                        onmouseover="this.style.backgroundColor='#D32F2F'; this.style.transform='scale(1.015)';"
                                        onmouseout="this.style.backgroundColor='#BB2819'; this.style.transform='scale(1)';">
                                </div>

                                <div class="text-center">
                                    <label>
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
                    <div class="row" style="height: 100px;background-color:#BB2819;color:#fff">
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

        var event_items = <?php echo json_encode($registrarData); ?>;
        var row_created = 0;
        var currentTab = 0;
        var selected_items = <?php echo $_SESSION["selected_items"] ?? '{}'; ?>;

        $(document).ready(function () {
            showTab(currentTab);

            var buttonReplacements = document.getElementsByClassName("button_replacement");

            for (let i = 0; i < buttonReplacements.length; i++) {
                addRow(buttonReplacements[i].value);
            }
        });

        function showTab(n) {
            var x = document.getElementsByClassName("tab");

            for (let i = 0; i < x.length; i++) {
                if (i == n) {
                    x[i].style.display = "block";
                }else{
                    x[i].style.display = "none";
                }
            }
        }

        document.querySelector('form').addEventListener('submit', function (_event) {
            _event.preventDefault();

            let itemId = [];
            let registrar = [];
            let count = [];

            $("select").each(function (item) {
                itemId.push($(this)[0].name.split('_')[1]);
                registrar.push($(this)[0].value);
            });

            $("input[type='number']").filter(function () {
                return $(this).attr('name');
            }).each(function (item) {
                count.push($(this)[0].value);
            });

            var dataObject = [];
            for (let i = 0; i < itemId.length; i++) {
                let temp = {
                    item_id: itemId[i],
                    registrar: registrar[i],
                    count: count[i]
                };

                if (count[i] > 0) {
                    dataObject.push(temp);
                }
            }

            $.ajax({
                type: 'POST',
                url: "./session/session.package.php",
                data: {
                    data: dataObject
                },
                dataType: 'json',
                cache: false,
                success: function (json) {
                    window.location.href = "confirmation.php";
                }
            });
        });

        function addRow(tableID) {
            console.log(selected_items[tableID.split('_')[1]]);

            var table = document.getElementById(tableID);

            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);

            var cell1 = row.insertCell(0);
            var element = document.createElement("input");
            element.type = "number";
            element.id = tableID + "_id[]";
            element.value = row_created;
            element.hidden = true;
            cell1.appendChild(element);

            var cell0 = row.insertCell(1);
            var select1 = document.createElement("select");
            select1.setAttribute("name", tableID + "_registrar[]");
            select1.setAttribute("id", tableID + "_registrar[]");
            select1.setAttribute("style", "width:100%;height:40px;display:none");

            option = document.createElement("option");
            for (var i = 0; i < event_items.length; i++) {
                option = document.createElement("option");
                option.setAttribute("value", event_items[i].id);
                option.innerHTML = event_items[i].name;
                select1.appendChild(option);
            }
            cell0.appendChild(select1);

            let cellPrice = row.insertCell(2);
            var elementContainer = document.createElement("div");
            elementContainer.style = "display: flex;justify-content:space-between;align-items:center;border-radius:8px;height: 40px;border: 1px solid black";
            
            var decrementButton = document.createElement("div");
            decrementButton.style = "width: 25%;height:100%;background-color:lightgray;display:flex;align-items:center;justify-content:center;cursor:pointer;border-top-left-radius:8px;border-bottom-left-radius:8px;border-right:1px solid black;";
            decrementButton.innerHTML = "-";
            decrementButton.onclick = function () {
                var inputElement = this.nextElementSibling;
                var currentValue = parseInt(inputElement.value);
                if (currentValue > inputElement.min) {
                    inputElement.value = currentValue - 1;
                }
            };

            var inputElement = document.createElement("input");
            inputElement.type = "number";
            inputElement.name = tableID + "_amount[]";
            inputElement.id = tableID + "_amount[]";
            inputElement.step = "1";
            inputElement.value = selected_items[tableID.split('_')[1]] ?? "0";
            inputElement.min = 0;
            inputElement.style = "width: 50%;height:100%; text-align:center;border:none;";
            if ($("#remain_" + tableID).val() != null) {
                inputElement.max = $("#remain_" + tableID).val();
            }

            var incrementButton = document.createElement("div");
            incrementButton.style = "width: 25%;height:100%;background-color:lightgray;display:flex;align-items:center;justify-content:center;cursor:pointer;border-top-right-radius:8px;border-bottom-right-radius:8px;border-left:1px solid black;";
            incrementButton.innerHTML = "+";
            incrementButton.onclick = function () {
                var inputElement = this.previousElementSibling;
                var currentValue = parseInt(inputElement.value);
                if (inputElement.max == "" || currentValue < inputElement.max) {
                    inputElement.value = currentValue + 1;
                }
            };

            elementContainer.appendChild(decrementButton);
            elementContainer.appendChild(inputElement);
            elementContainer.appendChild(incrementButton);
            cellPrice.appendChild(elementContainer);

            let cell4 = row.insertCell(3);
            let element4 = document.createElement("input");
            element4.type = "button";
            element4.value = "HAPUS";
            element4.style = "width:100%;height:40px;background-color:#545454;color:#fff;display:none";
            element4.onclick = function () {
                table.deleteRow(this.parentNode.parentNode.rowIndex);
            };
            cell4.appendChild(element4);

            row_created++;
        }

        $('#coaching').on('click', () => LogAction(<?php echo ($_SESSION["event_id"] == $GLOBALS['event_id_surabaya']) ? '"/surabaya"' : '""' ;?>, "coaching", "enter"));
        $('#product').on('click', () => LogAction(<?php echo ($_SESSION["event_id"] == $GLOBALS['event_id_surabaya']) ? '"/surabaya"' : '""' ;?>, "product", "enter"));
        $('#coaching_back').on('click', () => LogAction(<?php echo ($_SESSION["event_id"] == $GLOBALS['event_id_surabaya']) ? '"/surabaya"' : '""' ;?>, "coaching", "exit"));
        $('#product_back').on('click', () => LogAction(<?php echo ($_SESSION["event_id"] == $GLOBALS['event_id_surabaya']) ? '"/surabaya"' : '""' ;?>, "product", "exit"));
    </script>
</body>

</html>