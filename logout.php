<?php
session_start();
session_destroy();
header("Location: partner.php");
exit();
?> 