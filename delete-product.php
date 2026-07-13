<?php
require "includes/init.php";


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $conn = require "includes/db.php";
    $productId = intval($_GET['id']);
    $company_id = $_SESSION['user']['company_id'];
    $result = Product::delete($conn, $company_id, $productId);
      
     if($result['success']){
      $_SESSION['product-alert'] = [
         'type' => 'success',
         'message' =>'Product deleted successfully'
      ];
      
      Url::redirect("/product.php");
      exit;

     }

    $_SESSION['product-alert'] = [
         'type' => 'danger',
         'message' =>'Product cannot be deleted as it is referenced by other records'
      ];
      Url::redirect("/product.php");
        
     exit;
        
      

    
}