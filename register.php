<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | BG Ecosystem</title>
    <link rel="icon" type="image/x-icon" href="img/bg_favicon.png">
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
    </header>
    <main>
        <?php
        session_start();
        $error_message = "";
        if (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] == true)) {
            header('Location: index.php');
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include("connect.php");

            $nametag = $_POST["nametag"];
            $email = $_POST["email"];
            $password = $_POST["password"];

            $nametag_check_db = mysqli_real_escape_string($checkdb, $nametag);
            $email_check_db = mysqli_real_escape_string($checkdb, $email);
            $password_check_db = mysqli_real_escape_string($checkdb, $password);

            if (!filter_var($email_check_db, FILTER_VALIDATE_EMAIL)) {
                $_SESSION["error"] = true;
                $_SESSION["error_message"] = "Invalid email format";
                header('Location: register.php');
                exit;
            }

            $nametag_query = mysqli_prepare($checkdb, "SELECT * FROM profile WHERE nametag = ?");
            mysqli_stmt_bind_param($nametag_query, "s", $nametag_check_db);
            mysqli_stmt_execute($nametag_query);
            $resulttag = mysqli_stmt_get_result($nametag_query);

            if ($resulttag->num_rows > 0) {
                $_SESSION["error_message"] = "Nametag already exists";
                header('Location: register.php');
                exit;
            }

            $email_query = mysqli_prepare($checkdb, "SELECT * FROM profile WHERE email = ?");
            mysqli_stmt_bind_param($email_query, "s", $email_check_db);
            mysqli_stmt_execute($email_query);
            $resultemail = mysqli_stmt_get_result($email_query);

            if ($resultemail->num_rows > 0) {
                $_SESSION["error_message"] = "Email already exists";
                header('Location: register.php');
                exit;
            }

            if ($resulttag->num_rows == 0 && $resultemail->num_rows == 0) {
                $insertprofile = mysqli_prepare($checkdb, "INSERT INTO profile (nametag, email, password) VALUES (?, ?, ?)");
                $insertapps = mysqli_prepare($checkdb, "INSERT INTO apps (email, solidwriting, solidsheets) VALUES (?, ?, ?)");
                $insertlog = mysqli_prepare($checkdb, "INSERT INTO log (email, devicename, product, activity, log) VALUES (?, ?, ?, ?, ?)");
                $insertusersettings_rs = mysqli_prepare($checkdb, "INSERT INTO user_settings (email, product, theme, language) VALUES (?, 'SolidWriting', ?, ?)");
                $insertusersettings_sr = mysqli_prepare($checkdb, "INSERT INTO user_settings (email, product, theme, language) VALUES (?, 'SolidSheets', ?, ?)");

                $nametag_check = $nametag_check_db;
                $email_check = $email_check_db;
                $password_hash = password_hash($password_check_db, PASSWORD_DEFAULT);
                $devicename = $_SERVER["HTTP_USER_AGENT"];
                $product = "BG Ecosystem";
                $activity = "Register";
                $log = "Register Success";
                $solidwriting = 0;
                $solidsheets = 0;
                $theme = "light";
                $language = "English";

                mysqli_stmt_bind_param($insertprofile, "sss", $nametag_check, $email_check_db, $password_hash);
                mysqli_stmt_bind_param($insertapps, "sii", $email_check_db, $solidwriting, $solidsheets);
                mysqli_stmt_bind_param($insertlog, "sssss", $email_check_db, $devicename, $product, $activity, $log);
                mysqli_stmt_bind_param($insertusersettings_rs, "sss", $email_check_db, $theme, $language);
                mysqli_stmt_bind_param($insertusersettings_sr, "sss", $email_check_db, $theme, $language);

                if (mysqli_stmt_execute($insertprofile) && mysqli_stmt_execute($insertapps) && mysqli_stmt_execute($insertlog) && mysqli_stmt_execute($insertusersettings_rs) && mysqli_stmt_execute($insertusersettings_sr)) {
                    $user_id_db = mysqli_prepare($checkdb, "SELECT id FROM profile WHERE email = ?");
                    mysqli_stmt_bind_param($user_id_db, "s", $email_check_db);
                    mysqli_stmt_execute($user_id_db);
                    $result = mysqli_stmt_get_result($user_id_db);
                    $user_id = mysqli_fetch_assoc($result)["id"];
                    $_SESSION["loggedin"] = true;
                    $_SESSION["userid"] = $user_id;
                    $_SESSION["nametag"] = $nametag_check;
                    $_SESSION["email"] = $email_check_db;
                    mysqli_stmt_close($insertprofile);
                    mysqli_stmt_close($insertapps);
                    mysqli_stmt_close($insertlog);
                    mysqli_stmt_close($insertusersettings_rs);
                    mysqli_stmt_close($insertusersettings_sr);
                    mysqli_close($checkdb);

                    header('Location: index.php');
                    exit;
                } else {
                    echo "Error: " . mysqli_error($checkdb);
                }
            } else {
                $_SESSION["error_message"] = "Nametag or email already exists";
                header('Location: register.php');
                exit;
            }
        }
        ?>

        <form class="register-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"
            enctype="multipart/form-data">
            <h1>Register</h1>
            <h4>Join the unlimited content world of <b style="background: -webkit-linear-gradient(#E8F6EF, #FFE194);
        -webkit-background-clip: text;background-clip: text;    
        -webkit-text-fill-color: transparent; font-weight:bold;">BG Ecosystem</b>!</h4>
            <?php
            if (isset($_SESSION["error_message"])) {
                echo '<p style="color: red;">' . $_SESSION["error_message"] . '</p>';
                unset($_SESSION["error_message"]);
            }
            ?>
            <label for="nametag">Nametag:</label>
            <input type="text" id="nametag" name="nametag" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Register">
            <p style="margin-top: 0.5vh;">Already have an account?<br><a href="login.php"
                    style="text-decoration:none; color:white; font-weight:bold; padding: top 1vh; font-size: 1.2rem; text-shadow: 2px 2px #ff0000;">Log
                    In</a></p>
        </form>
    </main>
    <?php
    include("footer.php");
    ?>
</body>

</html>