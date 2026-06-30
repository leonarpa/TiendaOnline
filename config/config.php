<?php

define("CLIENT_ID","AYaZ5zCibj8CfkEWdo9ArLpsgYCZBHrURjzcwOtbZwEZdApEFB8DHxNU-RNVfus-32Yy-xcMm5Gtslz9");
define("CURRENCY", "USD");
define("KEY_TOKEN", "APR.wqc-354*");
define("MONEDA", "$");

session_start();

$num_cart = 0;
if(isset($_SESSION['carrito']['productos'])){
    $num_cart = count($_SESSION['carrito']['productos']);
}


?>