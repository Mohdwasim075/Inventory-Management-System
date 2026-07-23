<?php
//import the class autoloader and timeout script
require_once __DIR__ . "../../includes/init.php";
require_once __DIR__ .  "../../includes/timeout.php";

Auth::requireRole('SUPERADMIN');

$conn = Database::getConn(); 
$users = User::getAllUser($conn);
$companies = Company::getCompanies($conn);
// var_dump($companies);

var_dump($_SESSION['user']);

?>


<?php require "./includes/header.php"?>


<div class="card card-primary">
    <div class="card-header">

        <h3 class="card-title">Users</h3>

       

    </div>
   
    <div class="card-body">
        <?php if (isset($_SESSION['user-action'])): ?>

            <div class="alert alert-<?= $_SESSION['user-action']['type']; ?> alert-dismissible fade show">

                <?= htmlspecialchars($_SESSION['user-action']['message']); ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">

                </button>

            </div>

            <?php unset($_SESSION['user-action']); ?>

        <?php endif; ?>
<?php if(empty($users)) :?>
    <p>No users found</p>
<?php else: ?>
    <div >
        <a style="color:white" href="add-user.php"><button  id ="add-user" class="btn btn-primary float-end" >Add User</button></a>
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
                <td>
                    <a href="delete-user.php?id=<?= $user['id'] ?>"
                    class="btn btn-danger"> Delete
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