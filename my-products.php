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
                <img src="img/bg_favicon.png">
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
    </main>
    <?php
    include("footer.php");
    ?>
</body>

</html>