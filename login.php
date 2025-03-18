<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | BG Ecosystem</title>
    <?php
    include("connect.php");
    session_start();
    ?>
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
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
            header('Location: index.php');
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["email"]) && isset($_POST["password"])) {
                $email = mysqli_real_escape_string($checkdb, $_POST["email"]);
                $password = mysqli_real_escape_string($checkdb, $_POST["password"]);

                $query = "SELECT * FROM profile WHERE email = ? AND password = ?";
                $stmt = $checkdb->prepare($query);
                $stmt->bind_param("ss", $email, $password);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                $stmt->close();

                if ($result->num_rows > 0 && $user["email"] == $email && $user["password"] == $password) {
                    $_SESSION["loggedin"] = true;
                    $_SESSION["userid"] = $user["id"];
                    $_SESSION["email"] = $user["email"];
                    $_SESSION["nametag"] = $user["nametag"];

                    $loginlog = "INSERT INTO log(email, devicename, product, activity, log) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $checkdb->prepare($loginlog);
                    $stmt->bind_param("sssss", $email, $devicename, $product, $activity, $log);
                    $email = $_SESSION["email"];
                    $devicename = $_SERVER["HTTP_USER_AGENT"];
                    $product = "BG Ecosystem";
                    $activity = "Login";
                    $log = "Login Success";
                    $stmt->execute();
                    $stmt->close();

                    header('Location: index.php');
                    exit;
                } else {
                    $_SESSION["error"] = true;
                    header('Location: login.php');
                    exit;
                }
            } else {
                $_SESSION["error"] = true;
                header('Location: login.php');
                exit;
            }
        }
        ?>

        <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"
            enctype="multipart/form-data">
            <h1 class="form-header">Log In</h1>

            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Log In"><a href="#" class="forgot-password-link"
                onclick="alert('Mail server is not available.');">Forgot Password</a>

            <?php
            if (isset($_SESSION["error"]) && $_SESSION["error"] == true) {
                echo "<p class='error-message'>Incorrect email or password.</p>";
                $_SESSION["error"] = false;
            }
            ?>

            <p class="signup-link">Don't have an account?<br><a href="register.php" class="signup-link-text">Sign Up</a>
            </p>
        </form>
    </main>
    <?php
    include("footer.php");
    ?>
</body>

</html>