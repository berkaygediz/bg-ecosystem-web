<?php
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
    echo "<div class='nav-container'>";
    echo "<a href='index.php' class='nav-element'>Home</a>";
    echo "<a href='my-products.php' class='nav-element'>My Products</a>";
    echo "<a href='activate.php' class='nav-element'>Activate Product</a>";
    echo "<a class='nav-element' href='profile.php?userid=" . $_SESSION["userid"] . "'>Profile</a>";
    echo "<a href='logout.php' class='nav-element'>Logout</a>";
    echo "</div>";
} else {
    echo "<div class='nav-container'>";
    echo "<a href='login.php' class='nav-element'>Log In</a>";
    echo "<a href='register.php' class='nav-element'>Sign Up</a>";
    echo "</div>";
}
?>