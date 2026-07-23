<?php 
require "includes/init.php";



Auth::requireLogin();
Auth::requireRole('USER');

$conn = require "includes/db.php";

$companyId = Auth::companyId();

$products = Product::getAvaliableProducts($conn, $companyId);

 $stockMap = [];

foreach ($products as $product) {

    $stockMap[$product['id']] = $product['quantity_available'];

}

var_dump($stockMap);
$customers = Customer::getCustomers($conn, $companyId);

?>


