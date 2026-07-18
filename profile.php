<?php
require "includes/init.php";
require "includes/timeout.php";


//Authenticate valid user to access the page
Auth::requireLogin();
Auth::requireRole('USER');

$conn = require 'includes/db.php';

$user = User::getUserProfile($conn, Auth::id());


$currentPassword = "";
$newPassword = "";
$confirmPassword = ""; 
$errors = [];
if(isset($_POST['change-password'])){
    $conn = Database::getConn();
    $userDBPassword = User::getUserPassword($conn, Auth::id());
    $currentPassword = $_POST["currentPassword"]?? "";
    $newPassword = $_POST["newPassword"]?? "";
    $confirmPassword = $_POST["confirmPassword"]?? "";

    if($currentPassword === ''){
        $errors['currentPassword'] = "Current Password is required";
    }elseif(!password_verify($currentPassword, $userDBPassword)){
        $errors['currentPassword'] = "Passwords  do not  match";
    }
    if($newPassword == ''){
        $errors['newPassword'] = "A new Password should be given";
    }
    if ($newPassword !== '' && password_verify($newPassword, $userDBPassword)
) {
    $errors['newPassword'] =
        "New password must be different from the current password.";
}
    if($confirmPassword == ''){
        $errors['confirmPassword'] = "The above password should be retyped";
    }elseif($newPassword !== $confirmPassword){
        $errors['confirmPassword'] = 'Passwords do not match';
    }

    if(empty($errors)){
           $passwordHash = password_hash(
        $newPassword,
        PASSWORD_DEFAULT
    );

    $result = User::updatePassword(
        $conn,
        Auth::Id(),
        $passwordHash
    );
    if($result['success']){
      $_SESSION['changePassword'] = [
         'type' => 'success',
         'message' =>'Password Updated successfully'
      ];
      Url::redirect('/profile.php');

    }

}
}

?>

<?php require "includes/header.php"?>

<div class="row">
    <?php if (isset($_SESSION['changePassword'])): ?>

            <div class="alert alert-<?= $_SESSION['changePassword']['type']; ?> alert-dismissible fade show">

                <?= htmlspecialchars($_SESSION['changePassword']['message']); ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">

                </button>

            </div>

            <?php unset($_SESSION['changePassword']); ?>

        <?php endif; ?>

    <!-- User Profile -->
    <div class="col-md-8">

        <div class="card card-primary mt-3">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-person-circle"></i>
                    My Profile
                </h3>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <tr>
                        <th width="220">Full Name</th>
                        <td><?= htmlspecialchars($user[0]['name']) ?></td>
                    </tr>

                    

                    <tr>
                        <th>Email</th>
                        <td><?= htmlspecialchars($user[0]['email']) ?></td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td><?= htmlspecialchars($user[0]['contact_no']) ?></td>
                    </tr>

                    <tr>
                        <th>Role</th>
                        <td><?= htmlspecialchars($user[0]['role']) ?></td>
                    </tr>

                    <tr>
                        <th>Company</th>
                        <td><?= htmlspecialchars($user[0]['company_name']) ?></td>
                    </tr>

                    <tr>
                        <th>Joined On</th>
                        <td><?= date('d M Y', strtotime($user[0]['created_at'])) ?></td>
                    </tr>

                </table>

            </div>

            <div class="card-footer text-end">

                <a
                    href="edit-profile.php"
                    class="btn btn-primary">

                    <i class="bi bi-pencil-square"></i>
                    Edit Profile

                </a>

            </div>

        </div>

    </div>

    <!-- Change Password -->
    <div class="col-md-4">

        <div class="card card-warning mt-3">

            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-key-fill"></i>
                    Change Password
                </h3>
            </div>

            <form method="post" action="">

                <div class="card-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Current Password
                        </label>

                        <input
                            type="password"
                            name="currentPassword"
                            value="<?= htmlspecialchars($currentPassword)?>"
                            class="form-control <?= isset($errors['currentPassword']) ? 'is-invalid' : ''?>"
                            >
                        <?php if(isset($errors['currentPassword'])):?>
                            <div class="invalid-feedback">
                                <?= htmlspecialchars($errors['currentPassword'])?>

                            </div>
                        <?php endif;?>
                        

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            New Password
                        </label>

                        <input
                            type="password"
                            name="newPassword"
                            value="<?= htmlspecialchars($newPassword)?>"
                            class="form-control <?= isset($errors['newPassword']) ? 'is-invalid' : ''?>"
                            >
                        <?php if(isset($errors['newPassword'])):?>
                            <div class="invalid-feedback">
                                <?= htmlspecialchars($errors['newPassword'])?>

                            </div>
                        <?php endif;?>
                            

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Confirm Password
                        </label>

                        <input
                            type="password"
                            name="confirmPassword"
                          value="<?= htmlspecialchars($confirmPassword)?>"
                            class="form-control <?= isset($errors['confirmPassword']) ? 'is-invalid' : ''?>"
                            >
                        <?php if(isset($errors['confirmPassword'])):?>
                            <div class="invalid-feedback">
                                <?= htmlspecialchars($errors['confirmPassword'])?>

                            </div>
                        <?php endif;?>
                            

                    </div>

                </div>

                <div class="card-footer text-end">

                    <button
                        name = "change-password"
                        type="submit"
                        class="btn btn-warning">

                        <i class="bi bi-key"></i>
                        Change Password

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
        
        

    




<?php require "includes/footer.php"?>