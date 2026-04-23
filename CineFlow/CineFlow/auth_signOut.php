<?php
session_start();
session_unset();
session_destroy();
setcookie('cf_remember', '', time() - 3600, "/");
header("Location: 01_page_home.php");
exit();
?>
