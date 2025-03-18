<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | BG Ecosystem</title>
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
    </header>
    <main>
        <?php
        if (isset($_SESSION["loggedin"]) && ($_SESSION["loggedin"] == true)) {
            $userid = $_SESSION["userid"];
            if (isset($userid)) {
                echo "<h1>Edit Your Profile</h1>";
                $profileQuery = mysqli_prepare($checkdb, "SELECT * FROM profile WHERE id = ?");
                mysqli_stmt_bind_param($profileQuery, "i", $userid);
                mysqli_stmt_execute($profileQuery);
                $profileResult = mysqli_stmt_get_result($profileQuery);
                $post = mysqli_fetch_assoc($profileResult);

                if ($_SESSION["userid"] == $userid) {
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $nametag = $_POST['nametag'];

                        $nametag = mysqli_real_escape_string($checkdb, $nametag);
                        $nametag = htmlspecialchars($nametag);

                        if (empty($nametag)) {
                            echo "Nametag cannot be empty.";
                            exit;
                        }

                        $updateQuery = mysqli_prepare($checkdb, "UPDATE profile SET nametag = ? WHERE id = ?");
                        mysqli_stmt_bind_param($updateQuery, "si", $nametag, $userid);
                        $updateResult = mysqli_stmt_execute($updateQuery);

                        if ($updateResult) {
                            $_SESSION['nametag'] = $nametag;
                            header("Location: profile.php?userid=" . $userid);
                            exit;
                        } else {
                            echo "An error occurred while updating profile.";
                        }
                    }

                    echo "<form action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "' method='POST'>
                    <b>Nametag:</b> <input type='text' name='nametag' value='" . $post["nametag"] . "' placeholder='Nametag' required><br><br>
                    <input type='submit' value='Update'>
                    </form>";
                } else {
                    echo "You are not authorized to edit this profile.";
                }
            } else {
                echo "Missing user.";
            }
        } else {
            header("Location: login.php");
        }
        ?>
    </main>
    <?php
    include("footer.php");
    ?>
</body>

</html>