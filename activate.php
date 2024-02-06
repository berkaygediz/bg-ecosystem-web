<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Activate Products | BG Ecosystem</title>
    <?php
    session_start();
    include("connect.php");
    ?>
    <link rel="icon" type="image/x-icon" href="img/bg_favicon.png">
    <link rel="stylesheet" href="css/style.css">
    <style>
    .product-container {
        display: flex;
        justify-content: space-around;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .product-card {
        width: 300px;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    .product-card h2 {
        margin-bottom: 10px;
    }

    .product-card p {
        margin-bottom: 15px;
    }

    .product-card button {
        width: 100%;
        padding: 8px 0;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
    }

    .btn-success {
        background-color: #28a745;
        color: #fff;
    }

    .btn-danger {
        background-color: #dc3545;
        color: #fff;
    }
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
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_GET["activate"])) {
                $activateType = $_GET["activate"];
                $useremail = $_SESSION["email"];
                $product = "BG Ecosystem";
                $activity = "Activate";
                $devicename = $_SERVER["HTTP_USER_AGENT"];

                $productQuery = mysqli_prepare($checkdb, "SELECT * FROM apps WHERE email = ?");
                mysqli_stmt_bind_param($productQuery, "s", $useremail);
                mysqli_stmt_execute($productQuery);
                $result = mysqli_stmt_get_result($productQuery);
                $products = mysqli_fetch_assoc($result);

                if (mysqli_num_rows($result) > 0) {
                    if ($activateType == "richspan") {
                        if ($products["richspan"] == 1) {
                            $activateQuery = mysqli_prepare($checkdb, "UPDATE apps SET richspan = 0 WHERE email = ?");
                            mysqli_stmt_bind_param($activateQuery, "s", $useremail);
                            mysqli_stmt_execute($activateQuery);

                            $orderQuery = mysqli_prepare($checkdb, "UPDATE orders SET paymentstatus = ? WHERE email = ? AND product = ?");
                            $paymentstatus = "Refunded";
                            $product = "RichSpan";
                            mysqli_stmt_bind_param($orderQuery, "sss", $paymentstatus, $useremail, $product);
                            mysqli_stmt_execute($orderQuery);

                            $logquery = mysqli_prepare($checkdb, "INSERT INTO log (email, devicename, product, activity, log) VALUES (?, ?, ?, ?, ?)");
                            $log = "RichSpan Deactivated";
                            mysqli_stmt_bind_param($logquery, "sssss", $useremail, $devicename, $product, $activity, $log);

                            mysqli_stmt_execute($logquery);

                            header("location: activate.php");
                        } else {
                            $activateQuery = mysqli_prepare($checkdb, "UPDATE apps SET richspan = 1 WHERE email = ?");
                            mysqli_stmt_bind_param($activateQuery, "s", $useremail);
                            mysqli_stmt_execute($activateQuery);

                            $orderQuery = mysqli_prepare($checkdb, "INSERT INTO orders (email, paymentamount, paymentstatus, product) VALUES (?, ?, ?, ?)");
                            $paymentamount = 19.99;
                            $paymentstatus = "Completed";
                            $product = "RichSpan";
                            mysqli_stmt_bind_param($orderQuery, "sdss", $useremail, $paymentamount, $paymentstatus, $product);
                            mysqli_stmt_execute($orderQuery);

                            $logquery = mysqli_prepare($checkdb, "INSERT INTO log (email, devicename, product, activity, log) VALUES (?, ?, ?, ?, ?)");
                            $log = "RichSpan Activated";
                            mysqli_stmt_bind_param($logquery, "sssss", $useremail, $devicename, $product, $activity, $log);
                            mysqli_stmt_execute($logquery);

                            header("location: activate.php");
                        }
                    } else if ($activateType == "spanrc") {
                        if ($products["spanrc"] == 1) {
                            $activateQuery = mysqli_prepare($checkdb, "UPDATE apps SET spanrc = 0 WHERE email = ?");
                            mysqli_stmt_bind_param($activateQuery, "s", $useremail);
                            mysqli_stmt_execute($activateQuery);

                            $orderQuery = mysqli_prepare($checkdb, "UPDATE orders SET paymentstatus = ? WHERE email = ? AND product = ?");
                            $paymentstatus = "Refunded";
                            $product = "SpanRC";
                            mysqli_stmt_bind_param($orderQuery, "sss", $paymentstatus, $useremail, $product);
                            mysqli_stmt_execute($orderQuery);

                            $logquery = mysqli_prepare($checkdb, "INSERT INTO log (email, devicename, product, activity, log) VALUES (?, ?, ?, ?, ?)");
                            $log = "SpanRC Refunded";
                            mysqli_stmt_bind_param($logquery, "sssss", $useremail, $devicename, $product, $activity, $log);
                            mysqli_stmt_execute($logquery);

                            header("location: activate.php");
                        } else {
                            $activateQuery = mysqli_prepare($checkdb, "UPDATE apps SET spanrc = 1 WHERE email = ?");
                            mysqli_stmt_bind_param($activateQuery, "s", $useremail);
                            mysqli_stmt_execute($activateQuery);

                            $orderQuery = mysqli_prepare($checkdb, "INSERT INTO orders (email, paymentamount, paymentstatus, product) VALUES (?, ?, ?, ?)");
                            $paymentamount = 19.99;
                            $paymentstatus = "Completed";
                            $product = "SpanRC";
                            mysqli_stmt_bind_param($orderQuery, "sdss", $useremail, $paymentamount, $paymentstatus, $product);
                            mysqli_stmt_execute($orderQuery);

                            $logquery = mysqli_prepare($checkdb, "INSERT INTO log (email, devicename, product, activity, log) VALUES (?, ?, ?, ?, ?)");
                            $log = "SpanRC Activated";
                            mysqli_stmt_bind_param($logquery, "sssss", $useremail, $devicename, $product, $activity, $log);
                            mysqli_stmt_execute($logquery);
                            header("location: activate.php");
                        }
                    } else if ($activateType == "productkey") {
                        $productkey = $_POST["productkey"];
                        $productkeyQuery = mysqli_prepare($checkdb, "SELECT * FROM productkeys WHERE productkey = ?");
                        mysqli_stmt_bind_param($productkeyQuery, "s", $productkey);
                        mysqli_stmt_execute($productkeyQuery);
                        $result = mysqli_stmt_get_result($productkeyQuery);
                        $product = mysqli_fetch_assoc($result);

                        if (mysqli_num_rows($result) > 0) {
                            if ($product["status"] == 0) {
                                $activateQuery = mysqli_prepare($checkdb, "UPDATE productkeys SET status = 1, email = ? WHERE productkey = ?");
                                mysqli_stmt_bind_param($activateQuery, "ss", $useremail, $productkey);
                                mysqli_stmt_execute($activateQuery);

                                $logquery = mysqli_prepare($checkdb, "INSERT INTO log (email, devicename, product, activity, log) VALUES (?, ?, ?, ?, ?)");
                                $log = "Product Key Activated";
                                mysqli_stmt_bind_param($logquery, "sssss", $useremail, $devicename, $product["product"], $activity, $log);
                                mysqli_stmt_execute($logquery);
                                header("location: activate.php");
                            } else {
                                $_SESSION["error_message"] = "Product key already used.";
                                header("location: activate.php");
                            }
                        } else {
                            $_SESSION["error_message"] = "Product key not found.";
                            header("location: activate.php");
                        }
                    }
                } else {
                    echo "<h1 class='form-header'>No products found for the user.</h1>";
                }
            }
        }
        ?>
        <?php
        if (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] == true)) {
            $useremail = $_SESSION["email"];

            $productQuery = mysqli_prepare($checkdb, "SELECT * FROM apps WHERE email = ?");
            mysqli_stmt_bind_param($productQuery, "s", $useremail);
            mysqli_stmt_execute($productQuery);
            $result = mysqli_stmt_get_result($productQuery);
            $products = mysqli_fetch_assoc($result);

            if (mysqli_num_rows($result) > 0) {
                echo "<center><h1 class='form-header'>Activate Products</h1></center>";
                echo "<div class='product-container'>";
                if ($products["richspan"] == 1) {
                    echo "<div class='product-card bg-dark'>
                    <img src='img/richspan.png' style='width: 100%;'>
                    <h2 style='font-weight: bold; margin-top: 10px;'>RichSpan</h2>
                    <p>Rich word processor application.</p>
                    <b>Features available:</b>
                    <ul>
                        <li>Text Formatting</li>
                        <li>Synchronization</li>
                        <li>Font Type</li>
                        <li>Font Size</li>
                        <li>Font Color</li>
                        <li>Multimedia</li>
                        <li>Bulleted and Numbered Lists</li>
                        <li>Workspace</li>
                        <li>Synchronization</li>
                        <li>Printing</li>
                        and more...
                    </ul>
                    <div>
                    <h2 style='color: green;'>Active</h2></div>
                    Download available
                    <a href='products/SpanRC.zip' ><button class='btn btn-success'>Download</button></a>
                    <br><br>
                    <form action='activate.php?activate=richspan' method='post'>
                        <button type='submit' class='btn btn-warning'>Refund</button>
                    </form>
                </div>";
                } else {
                    echo "<div class='product-card bg-dark'>
                    <img src='img/richspan.png' style='width: 100%;'>
                    <h2 style='font-weight: bold; margin-top: 10px;'>RichSpan</h2>
                    <p>Rich word processor application.</p>
                    <b>Features include:</b>
                    <ul>
                        <li>Text Formatting</li>
                        <li>Synchronization</li>
                        <li>Font Type</li>
                        <li>Font Size</li>
                        <li>Font Color</li>
                        <li>Multimedia</li>
                        <li>Bulleted and Numbered Lists</li>
                        <li>Workspace</li>
                        <li>Synchronization</li>
                        <li>Printing</li>
                        and more...
                    </ul>
                    <p><b>Price:</b> 20 TL</p>
                    <form action='activate.php?activate=richspan' method='post'>
                        <button type='submit' class='btn btn-success'>Buy Now</button>
                    </form>
                </div>";
                }
                if ($products["spanrc"] == 1) {
                    echo "<div class='product-card bg-dark'>
                <img src='img/spanrc.png' style='width: 100%;'>
                <h2 style='font-weight: bold; margin-top: 10px;'>SpanRC</h2>
                <p>Table processor application.</p>
                <b>Features available:</b>
                <ul>
                    <li>Formulas</li>
                    <li>Functions</li>
                    <li>Data Analysis</li>
                    <li>Data Visualization</li>
                    <li>Synchronization</li>
                    <li>Printing</li>
                    and more...
                </ul>
                <div><h2 style='color: green;'>Active</h2></div>
                Download available
                <a href='products/SpanRC.zip' ><button class='btn btn-success'>Download</button></a>
                <br><br>
                <form action='activate.php?activate=spanrc' method='post'>
                    <button type='submit' class='btn btn-warning'>Refund</button>
                </form>
                </div>";
                } else {
                    echo "<div class='product-card bg-dark'>
                <img src='img/spanrc.png' style='width: 100%;'>
                <h2 style='font-weight: bold; margin-top: 10px;'>SpanRC</h2>
                <p>Table processor application.</p>
                <b>Features include:</b>
                <ul>
                    <li>Formulas</li>   
                    <li>Functions</li>
                    <li>Data Analysis</li>
                    <li>Data Visualization</li>
                    <li>Synchronization</li>
                    <li>Printing</li>
                    and more...
                </ul>
                <p><b>Price:</b> 20 TL</p>
                <form action='activate.php?activate=spanrc' method='post'>
                    <button type='submit' class='btn btn-success'>Buy Now</button>
                </form>
                </div>";
                }
                echo "</div>";
                echo "<div style='text-align: center; margin: 2% 0 1% 0; background-color: rgba(0, 0, 0, 0.37); border-radius: 1rem; padding: 1% 0 1% 0;'><b>Activate with Product Key</b>";
                if (isset($_SESSION["error_message"])) {
                    echo "<div style='color: red; text-align: center; margin-top: 1%;'>" . $_SESSION["error_message"] . "</div>";
                    unset($_SESSION["error_message"]);
                }
                echo "<form action='activate.php?activate=productkey' method='post' style='text-align: center; margin-top: 1%'>
                <input type='text' name='productkey' placeholder='AAAA-BBBB-CCCC-DDDD'>
                <button type='submit' class='btn btn-success'>Activate</button>
                </form></div>";

            } else {
                echo "<h1 class='form-header'>No products found for the user.</h1>";
            }
        } else {
            echo "<h1 class='form-header'>You are not logged in.</h1>";
        }
        ?>
    </main>
    <?php
    include("footer.php");
    ?>
</body>

</html>