<?php
require_once __DIR__ . "../../includes/init.php";


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
   $conn = Database::getConn();
    $companyId = intval($_GET['id']);
  
    $result = Company::delete($conn, $companyId);

  
   // var_dump($companyId);
   // exit;
     if($result['success']){
      $_SESSION['company-action'] = [
         'type' => 'success',
         'message' =>'Company deleted successfully'
      ];
      
      Url::redirect("/Users.php");
      exit;

     }else{
        $_SESSION['company-action'] = [
         'type' => 'danger',
         'message' =>'Company cannot be deleted as it is referenced by other records'
      ];
      Url::redirect("/admin/list-company.php");
        
     exit;
        

     }

    
      

    
}