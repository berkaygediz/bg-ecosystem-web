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
            <img src="img/core/bg_favicon.png">
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
                        echo "<div><img src='img/core/account.svg' style='width: 5%; height: 5%; border-radius: 50%;'><div>" . $profileData["nametag"] . "</div></div>";
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
                    <h1>Info</h1>
                    <?php
                    if (isset($_GET["userid"])) {
                        $userid = $_GET["userid"];
                        $profileQuery = mysqli_prepare($checkdb, "SELECT * FROM profile WHERE id = ?");
                        mysqli_stmt_bind_param($profileQuery, "s", $userid);
                        mysqli_stmt_execute($profileQuery);
                        $result = mysqli_stmt_get_result($profileQuery);

                        if (mysqli_num_rows($result) > 0) {
                            echo "<a href='profile-edit.php' style='text-align:right;'><div style='background-color: rgba(0, 0, 0, 0.37); border-radius:1rem;'><img src='img/core/edit.svg' style='width: 1.5%; height: 1.5%; border-radius: 50%;'>Edit</a></div>";
                            $profileData = mysqli_fetch_assoc($result);
                            echo "<div><b>Email:</b><a href='mailto:" . $profileData["email"] . "'> " . $profileData["email"] . "</a></div>";
                        } else {
                            echo "profile not found.";
                        }

                    } else {
                        echo "profile not found.";
                    }
                    ?>
                </div>
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