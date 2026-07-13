<?php
require "includes/init.php";


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $conn = require "includes/db.php";
    $supplierId = intval($_GET['id']);
    $company_id = $_SESSION['user']['company_id'];
    $result = Supplier::delete($conn, $company_id, $supplierId);
      
     if($result['success']){
      $_SESSION['suppl'] = [
         'type' => 'success',
         'message' =>'Supplier deleted successfully'
      ];
      
      Url::redirect("/supplier.php");
      exit;

     }

    $_SESSION['suppl'] = [
         'type' => 'danger',
         'message' =>'Supplier cannot be deleted as it is referenced by other records'
      ];
      Url::redirect("/supplier.php");
        
     exit;
        
      

    
}