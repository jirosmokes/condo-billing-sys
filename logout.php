<?php
session_start();
session_unset();
session_destroy();
header('location: landing-page.php');
exit();

