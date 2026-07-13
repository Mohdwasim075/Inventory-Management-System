<?php
//import the class autoloader and timeout script
require_once __DIR__ . "../../includes/init.php";
require_once __DIR__ .  "../../includes/timeout.php";

Auth::requireRole('SUPERADMIN');

$conn = Database::getConn(); 
$users = User::getAllUser($conn);
$companies = Company::getCompanies($conn);
// var_dump($companies);

var_dump($users);

?>


<?php require "./includes/header.php"?>


<div class="card">
   
    <div class="card-body">
<?php if(empty($users)) :?>
    <p>No users found</p>
<?php else: ?>
    <div >
        <h2>Users</h2>
        <a style="color:white" href="add-user.php"><button  id ="add-user" class="btn btn-primary" >Add User</button></a>
       </div>
    
        <table class="table table-striped ">
        <thead>
            <tr>
                <th>S.no</th>
                <th>User</th>
                <th>Company</th>
                <th>Email</th>
                <th>Role</th>
                <th></th>

            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $id => $user): ?>
            <tr class = "table-row">
                <td><?= $id + 1 ;?></td>
                <td><?= $user['name'] ?></td>
                <td><?= $user['company_name'] ?></td>
                <td><?= $user['email'] ?></td>
                
                <td><?= $user['role'] ?></td>
                <td>
                    <a href="edit-user.php?id=<?= $user['id'] ?>"
                    class="btn btn-success"> Edit
                    </a>
                </td>
                
                
              
            </tr>
            <?php endforeach;?>
        </tbody>    
        </table>
     <?php endif;?>
            </div>
            </div>
<?php require "./includes/footer.php"?>