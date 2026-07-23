<?php
require_once __DIR__ . "../../includes/init.php";


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
   $conn = Database::getConn();
    $userId = intval($_GET['id']);
    $companyId = User::getCompanyId($conn, $userId);
    $result = User::delete($conn, $companyId, $userId);

  
   // var_dump($companyId);
   // exit;
     if($result['success']){
      $_SESSION['user-action'] = [
         'type' => 'success',
         'message' =>'User deleted successfully'
      ];
      
      Url::redirect("/Users.php");
      exit;

     }else{
        $_SESSION['user-action'] = [
         'type' => 'danger',
         'message' =>'User cannot be deleted as it is referenced by other records'
      ];
      Url::redirect("/admin/users.php");
        
     exit;
        

     }

    
      

    
}