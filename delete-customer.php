<?php
require "includes/init.php";


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $conn = require "includes/db.php";
    $customerId = intval($_GET['id']);
    $company_id = Auth::companyId();
    $result = Customer::delete($conn, $company_id, $customerId);
      
     if($result['success']){
      $_SESSION['customer'] = [
         'type' => 'success',
         'message' =>'Customer deleted successfully'
      ];
      
      Url::redirect("/customers.php");
      exit;

     }

    $_SESSION['customer'] = [
         'type' => 'danger',
         'message' =>'Customer cannot be deleted as it is referenced by other records'
      ];
      Url::redirect("/customers.php");
        
     exit;
        
      

    
}