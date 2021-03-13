<?php
session_start();
session_destroy();
header("Location:../etc/index.php");
exit();