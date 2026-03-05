<?php
session_start();
session_unset();
session_destroy();
header("Location: formlogin.html?logout=1");
exit;
