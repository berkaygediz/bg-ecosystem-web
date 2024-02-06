<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Products | BG Ecosystem</title>
    <?php
    session_start();
    include("connect.php");
    ?>
    <link rel="icon" type="image/x-icon" href="img/bg_favicon.png">
    <link rel="stylesheet" href="css/style.css">
    <style>
    </style>
</head>

<body>
    <header>
        <div class="navbar-icon">
            <a href="index.php" style="text-decoration: none; display: flex; align-items: center;">
            <img src="img/core/bg_favicon.png">
                <h1>Ecosystem</h1>
            </a>
        </div>
        <?php
        include("nav.php");
        ?>
    </header>
    <main>     
        <?php
        if (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] == true)) {
            $useremail = $_SESSION["email"];

            $productQuery = mysqli_prepare($checkdb, "SELECT * FROM apps WHERE email = ?");
            mysqli_stmt_bind_param($productQuery, "s", $useremail);
            mysqli_stmt_execute($productQuery);
            $result = mysqli_stmt_get_result($productQuery);
            $products = mysqli_fetch_assoc($result);

            if (mysqli_num_rows($result) > 0) {
                if ($products["richspan"] == 1) {
                    echo "<div><b>RichSpan</b></div>";
                    echo "<div style='background-color: limegreen; color: white; padding: 1%; border-radius: 1rem; margin-top: 1%;'>Activated</div><br>";
                } else {
                    echo "<div><b>RichSpan</b></div>";
                    echo "<div style='background-color: red; color: white; padding: 1%; border-radius: 1rem; margin-top: 1%;'>Not Activated</div><br>";
                }
                if ($products["spanrc"] == 1) {
                    echo "<div><b>SpanRC</b></div>";
                    echo "<div style='background-color: limegreen; color: white; padding: 1%; border-radius: 1rem; margin-top: 1%;'>Activated</div><br>";
                } else {
                    echo "<div><b>SpanRC</b></div>";
                    echo "<div style='background-color: red; color: white; padding: 1%; border-radius: 1rem; margin-top: 1%;'>Not Activated</div><br>";
                }
            } else {
                echo "You have no products.<br><br>";
            }

        } else {
            echo "You must be logged in to view this page.<br><br>";
        }
        ?>

        <div class="context profile-item" style="margin-top: 2%; background-color: rgba(0, 0, 0, 0.37); border-radius: 1rem;">
                <h1>Orders</h1>
                <?php
                if (isset($_SESSION["userid"])) {
                    $userid = $_SESSION["userid"];
                    $useremail = $_SESSION["email"];
                    $ordersQuery = mysqli_prepare($checkdb, "SELECT * FROM orders WHERE email = ? ORDER BY paymenttime DESC");
                    mysqli_stmt_bind_param($ordersQuery, "s", $useremail);
                    mysqli_stmt_execute($ordersQuery);
                    $result = mysqli_stmt_get_result($ordersQuery);

                    if (mysqli_num_rows($result) > 0) {
                        echo "<table style='width:100%; border-collapse: collapse;'>";
                        echo "<tr style='border-bottom: 1px solid #ccc;color: #FF9843;'>";
                        echo "<th style='padding: 10px;'>Product</th>";
                        echo "<th style='padding: 10px;'>Payment Amount</th>";
                        echo "<th style='padding: 10px;'>Payment Time</th>";
                        echo "<th style='padding: 10px;'>Payment Status</th>";
                        echo "</tr>";

                        while ($order = mysqli_fetch_assoc($result)) {
                            echo "<tr style='color: white;'>";
                            echo "<td style='border-bottom: 1px solid #ccc; padding: 10px;'>" . $order["product"] . "</td>";
                            echo "<td style='border-bottom: 1px solid #ccc; padding: 10px;'>" . $order["paymentamount"] . "</td>";
                            echo "<td style='border-bottom: 1px solid #ccc; padding: 10px;'>" . $order["paymenttime"] . "</td>";
                            if ($order["paymentstatus"] == "Completed") {
                                echo "<td style='border-bottom: 1px solid #ccc; padding: 10px; color: white; background-color: limegreen;'>" . $order["paymentstatus"] . "</td>";
                            } else {
                                echo "<td style='border-bottom: 1px solid #ccc; padding: 10px; color: black; background-color: gold;'>" . $order["paymentstatus"] . "</td>";
                            }
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p style='color: white;'>No orders found.</p>";
                    }
                }
                ?>
            </div>

            <div class="context profile-item" style="margin-top: 2%; background-color: rgba(0, 0, 0, 0.37); border-radius: 1rem;">
                <?php
                $richspanlogcount = mysqli_prepare($checkdb, "SELECT * FROM log WHERE email = ? AND product = 'RichSpan' LIMIT 15");
                mysqli_stmt_bind_param($richspanlogcount, "s", $_SESSION["email"]);
                mysqli_stmt_execute($richspanlogcount);
                $result = mysqli_stmt_get_result($richspanlogcount);
                $products = mysqli_fetch_assoc($result);
                if (mysqli_num_rows($result) > 0) {
                    echo "<p style='text-align:center;'><b>BG RichSpan</b></p>";
                    echo "<div style='margin-top: 20px; text-align: center;'>";
                    echo "<center><h1>Log</h1></center><br>";
                    echo "<table width='100%' style='text-align: center;'>";
                    echo "<tr style='border-bottom: 1px solid #ccc;color: #FF5043;'>";
                    echo "<th>Device Name</th>";
                    echo "<th>Activity</th>";
                    echo "<th>Log</th>";
                    echo "<th>Log Date</th>";
                    echo "</tr>";
                    while ($products = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td style='color:white;'>" . $products["devicename"] . "</td>";
                        echo "<td>" . $products["activity"] . "</td>";
                        echo "<td>" . $products["log"] . "</td>";
                        echo "<td>" . $products["logdate"] . "</td>";
                        echo "</tr>";
                        echo "<tr><td colspan='4'><hr></td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p><b>BG RichSpan</b> - A powerful word processor with a rich text editor.</p>";
                    echo "<p><b>RichSpan has not been activated yet.</b></p>";
                }

                $spanrclogcount = mysqli_prepare($checkdb, "SELECT * FROM log WHERE email = ? AND product = 'SpanRC' LIMIT 15");
                mysqli_stmt_bind_param($spanrclogcount, "s", $_SESSION["email"]);
                mysqli_stmt_execute($spanrclogcount);
                $result = mysqli_stmt_get_result($spanrclogcount);
                $products = mysqli_fetch_assoc($result);

                if (mysqli_num_rows($result) > 0) {
                    echo "<br><br><p><b>BG SpanRC</b></p>";
                    echo "<div style='margin-top: 20px; text-align: center;'>";
                    echo "<center><h1>Log</h1></center><br>";
                    echo "<table width='100%' style='text-align: center;'>";
                    echo "<tr style='border-bottom: 1px solid #ccc;color: #FF5043;'>";
                    echo "<th>Device Name</th>";
                    echo "<th>Activity</th>";
                    echo "<th>Log</th>";
                    echo "<th>Log Date</th>";
                    echo "</tr>";
                    while ($products = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $products["devicename"] . "</td>";
                        echo "<td>" . $products["activity"] . "</td>";
                        echo "<td>" . $products["log"] . "</td>";
                        echo "<td>" . $products["logdate"] . "</td>";
                        echo "</tr>";
                        echo "<tr><td colspan='4'><hr></td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<br><br><p><b>BG SpanRC</b> - A powerful table processor with formulas and functions.</p>";
                    echo "<p><b>SpanRC has not been activated yet.</b></p>";
                }
                ?>
            </div>
    </main>
    <?php
    include("footer.php");
    ?>
</body>

</html>