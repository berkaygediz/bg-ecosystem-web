<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>
        <?php
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
            echo "Home";
        } else {
            echo "Welcome";
        }
        ?>
        | BG Ecosystem
    </title>
    <link rel="icon" type="image/x-icon" href="img/bg_favicon.png">
    <?php
    session_start();
    include("connect.php");
    ?>
    <link rel="stylesheet" href="css/style.css">
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
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
            echo "<div><h1>Welcome, " . $_SESSION["nametag"] . "!</h1><br>";
            $solidwritinglogcount = mysqli_prepare($checkdb, "SELECT * FROM apps WHERE email = ? AND solidwriting = 1 LIMIT 25");
            mysqli_stmt_bind_param($solidwritinglogcount, "s", $_SESSION["email"]);
            mysqli_stmt_execute($solidwritinglogcount);
            $result = mysqli_stmt_get_result($solidwritinglogcount);
            $products = mysqli_fetch_assoc($result);
            if (mysqli_num_rows($result) > 0) {
                echo "<div style='text-align: center; background-color: rgba(0, 0, 0, 0.37); padding: 20px;'>";
                echo "<h1 style='color: #009688;'>BG SolidWriting</h1>";
                echo "<p style='font-size: 18px;'><b>BG SolidWriting</b> is a powerful word processor with a rich text editor.</p>";
                echo "</div>";
            } else {
                echo "<div style='text-align: center; background-color: rgba(0, 0, 0, 0.37); padding: 20px;'>";
                echo "<h1 style='color: #FF5722;'>Activate BG SolidWriting Now!</h1>";
                echo "<p style='font-size: 18px;'><b>BG SolidWriting</b> is a powerful word processor with a rich text editor.</p>";
                echo "</div>";
            }

            echo "<br>";

            $solidsheetslogcount = mysqli_prepare($checkdb, "SELECT * FROM apps WHERE email = ? AND solidsheets = 1 LIMIT 25");
            mysqli_stmt_bind_param($solidsheetslogcount, "s", $_SESSION["email"]);
            mysqli_stmt_execute($solidsheetslogcount);
            $result = mysqli_stmt_get_result($solidsheetslogcount);
            $products = mysqli_fetch_assoc($result);

            if (mysqli_num_rows($result) > 0) {
                echo "<div style='text-align: center; background-color: rgba(0, 0, 0, 0.37); padding: 20px;'>";
                echo "<h1 style='color: #009688;'>BG SolidSheets</h1>";
                echo "<p style='font-size: 18px;'><b>BG SolidSheets</b> - A powerful table processor with formulas and functions.</p>";
                echo "</div>";
            } else {
                echo "<div style='text-align: center; background-color: rgba(0, 0, 0, 0.37); padding: 20px;'>";
                echo "<h1 style='color: #FF5722;'>Activate BG SolidSheets Now!</h1>";
                echo "<p style='font-size: 18px;'><b>BG SolidSheets</b> - A powerful table processor with formulas and functions.</p>";
                echo "</div>";
            }
        } else {
            echo "<div style='background-color:#3F3F3F; opacity:0.93; box-shadow:0 0 25px black; padding: 2% 3% 2% 3%; border-radius: 2rem;'><h1>Welcome!</h1><br>";
            echo "<p>Welcome to BG Ecosystem, a powerful office tool for your business.</p>";
            echo "<br><br><b>What is BG Ecosystem?</b><br>";
            echo "<p>BG Ecosystem is a secure authenticator and a powerful office tool for your business.</p>";
            echo "<br><br><h1>Products</h1><br>";
            echo "<table width='100%' style='text-align: center;'>";
            echo "<tr>";
            echo "<th>SolidWriting</th><th>SolidSheets</th>";
            echo "</tr>";
            echo "<tr>";
            echo "<td><img src='img/solidwriting.png' style='width: 50%;'></td><td><img src='img/solidsheets.png' style='width: 50%;'></td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td><br><a href='products/SolidWriting.zip'><button class='btn btn-success'>İndir</button></a></td><td><a href='products/SolidSheets.zip'><button class='btn btn-success'>İndir</button></a></td>";
            echo "</tr>";
            echo "</table>";
        }
        ?>
    </main>
    <?php
    include("footer.php");
    ?>
</body>

</html>