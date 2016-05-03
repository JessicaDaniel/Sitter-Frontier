<?php
//starts session
session_start();
//removes all session variables 
session_unset();
//destroys session
session_destroy();
//redirects to login page 
header("location:Login.php");
?>


