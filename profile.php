<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    session_start();
    include("connect.php");
    ?>
    <title>
        <?php
        $userid = $_GET["userid"];
        $profileQuery = mysqli_prepare($checkdb, "SELECT * FROM profile WHERE id = ?");
        mysqli_stmt_bind_param($profileQuery, "s", $userid);
        mysqli_stmt_execute($profileQuery);
        $result = mysqli_stmt_get_result($profileQuery);

        if (mysqli_num_rows($result) > 0) {
            $profileData = mysqli_fetch_assoc($result);
            echo $profileData["nametag"];
        } else {
            echo "Profile not found. | BG Ecosystem";
        }
        ?> | BG Ecosystem
    </title>
    <link rel="icon" type="image/x-icon" href="img/bg_favicon.png">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <div class="navbar-icon">
            <a href="index.php" style="text-decoration: none; display: flex; align-items: center;">
                <img src="img/bg_favicon.png">
                <h1>Ecosystem</h1>
            </a>
        </div>
        <?php
        include("nav.php");
        ?>
    </header>
    <main>
        <div id="profile"
            style="background: linear-gradient(45deg, purple, rgb(128, 0, 167), rgb(149, 0, 194), yellow); color: black;">
            <div class="profile-item">
                <?php
                if (isset($_GET["userid"])) {
                    $userid = $_GET["userid"];
                    $profileQuery = mysqli_prepare($checkdb, "SELECT * FROM profile WHERE id = ?");
                    mysqli_stmt_bind_param($profileQuery, "s", $userid);
                    mysqli_stmt_execute($profileQuery);
                    $result = mysqli_stmt_get_result($profileQuery);

                    if (mysqli_num_rows($result) > 0) {
                        $profileData = mysqli_fetch_assoc($result);
                        echo "<div style='padding-top: 2%;'>" . $profileData["nametag"] . "</div>";
                        echo "<div style='padding-top: 2%;'>" . $profileData["email"] . "</div>";
                        echo "<div style='padding-top: 2%;'>" . $profileData["creationtime"] . "</div>";
                    } else {
                        echo "profile not found.";
                    }
                } else {
                    echo "profile not found.";
                }
                ?>
            </div>
            <div id="contact" style="clear:both; padding-top:2%;position:relative; float: left; color: white;"></div>
            <div class="profile-item">
                <div class="context">
                    <h1>Products</h1>
                    <?php
                    if (isset($_GET["userid"])) {
                        $userid = $_GET["userid"];
                        $useremail = $_SESSION["email"];
                        $productsQuery = mysqli_prepare($checkdb, "SELECT richspan, spanrc FROM apps WHERE email = ?");
                        mysqli_stmt_bind_param($productsQuery, "s", $useremail);
                        mysqli_stmt_execute($productsQuery);
                        $result = mysqli_stmt_get_result($productsQuery);
                        $products = mysqli_fetch_assoc($result);

                        if ($products["richspan"] == 1) {
                            $productQuery = mysqli_prepare($checkdb, "SELECT * FROM apps WHERE email = ?");
                            mysqli_stmt_bind_param($productQuery, "i", $useremail);
                            mysqli_stmt_execute($productQuery);
                            $result = mysqli_stmt_get_result($productQuery);
                            if (mysqli_num_rows($result) > 0) {
                                echo "<div><b>RichSpan</b></div>";
                                echo "<div style='background-color: limegreen; color: white; padding: 1%; border-radius: 1rem; margin-top: 1%;'>Activated</div><br>";
                            }
                        } else {
                            echo "<div><b>RichSpan</b></div>";
                            echo "<div style='background-color: red; color: white; padding: 1%; border-radius: 1rem; margin-top: 1%;'>Not Activated</div><br>";
                        }
                        if ($products["spanrc"] == 1) {
                            $productQuery = mysqli_prepare($checkdb, "SELECT * FROM apps WHERE email = ?");
                            mysqli_stmt_bind_param($productQuery, "i", $useremail);
                            mysqli_stmt_execute($productQuery);
                            $result = mysqli_stmt_get_result($productQuery);
                            if (mysqli_num_rows($result) > 0) {
                                echo "<div><b>SpanRC</b></div>";
                                echo "<div style='background-color: limegreen; color: white; padding: 1%; border-radius: 1rem; margin-top: 1%;'>Activated</div>";
                            }
                        } else {
                            echo "<div><b>SpanRC</b></div>";
                            echo "<div style='background-color: red; color: white; padding: 1%; border-radius: 1rem; margin-top: 1%;'>Not Activated</div>";
                        }

                    }
                    ?>
                </div>
            </div>
            <div class="context" style="margin-top: 2%;">
                <h1>Orders</h1>
                <?php
                if (isset($_GET["userid"])) {
                    $userid = $_GET["userid"];
                    $useremail = $_SESSION["email"];
                    $ordersQuery = mysqli_prepare($checkdb, "SELECT * FROM orders WHERE email = ? ORDER BY paymenttime DESC");
                    mysqli_stmt_bind_param($ordersQuery, "s", $useremail);
                    mysqli_stmt_execute($ordersQuery);
                    $result = mysqli_stmt_get_result($ordersQuery);

                    if (mysqli_num_rows($result) > 0) {
                        echo "<table style='width:100%; border-collapse: collapse;'>";
                        echo "<tr style='border-bottom: 1px solid #ccc;'>";
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
                            echo "<td style='border-bottom: 1px solid #ccc; padding: 10px;'>" . $order["paymentstatus"] . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "No orders found.";
                    }
                }
                ?>
            </div>
        </div>
        <?php
        if (isset($_GET["deleteaccount"])) {
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
                $profileId = $_SESSION["userid"];
                $deleteAppsQuery = mysqli_prepare($checkdb, "DELETE FROM apps WHERE email = ?");
                mysqli_stmt_bind_param($deleteAppsQuery, "s", $useremail);
                mysqli_stmt_execute($deleteAppsQuery);
                $result = mysqli_stmt_get_result($deleteAppsQuery);

                $deleteUserSettingsQuery = mysqli_prepare($checkdb, "DELETE FROM user_settings WHERE email = ?");
                mysqli_stmt_bind_param($deleteUserSettingsQuery, "s", $useremail);
                mysqli_stmt_execute($deleteUserSettingsQuery);
                $result = mysqli_stmt_get_result($deleteUserSettingsQuery);

                $deleteQuery = mysqli_prepare($checkdb, "DELETE FROM profile WHERE id = ?");
                mysqli_stmt_bind_param($deleteQuery, "i", $profileId);
                mysqli_stmt_execute($deleteQuery);
                $result = mysqli_stmt_get_result($deleteQuery);

                header("Location: logout.php");
            } else {
                header("Location: profile.php?username=" . $_SESSION["username"]);
            }
        }
        ?>

        <?php
        if (isset($_GET["userid"]) && !(isset($_GET["deleteaccount"]))) {
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
                if ($_SESSION["userid"] == $_GET["userid"]) {
                    echo "<div style='padding-top: 2%;'><a href='profile.php?userid=" . $_SESSION["userid"] . "&deleteaccount=true' style='color:red;'>Delete Account</a></div>";
                }
            }
        }
        ?>
    </main>
    <?php
    include("footer.php");
    ?>
</body>

</html>