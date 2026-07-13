<?php
require "includes/init.php";


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $conn = require "includes/db.php";
    $categoryId = intval($_GET['id']);
    $company_id = $_SESSION['user']['company_id'];
    $result = Category::delete($conn, $company_id, $categoryId);
      
      if($result['success']){
      $_SESSION['category-action'] = [
         'type' => 'success',
         'message' =>'Category deleted successfully'
      ];
      
      Url::redirect("/categories.php");
      exit;

     }

    $_SESSION['category-action'] = [
         'type' => 'danger',
         'message' =>'Category cannot be deleted as it is referenced by other records'
      ];
      Url::redirect("/categories.php");
        
     exit;
        
      

    
}