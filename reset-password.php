<?php
require "includes/init.php";

$showResetForm = true;
$errors = [];
if(isset($_GET['token'])){

    $conn = Database::getConn();
    $resetToken = $_GET['token']?? '';

    $tokenRow = PasswordReset::findByToken($conn, $resetToken);
    //var_dump($tokenRow);

    $now = new DateTime();

    $expiresAt = new DateTime($tokenRow['expires_at']);

    if ($expiresAt < $now) {

        die('The reset  link has expired');

    }


}else{
    die('Invalid reset token ');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- In <head> -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/css/adminlte.min.css" />
  <link href="styles/styles.css" rel='stylesheet'></link>
  <title>Log </title>
</head>
<body class="login-body">
<div class="login-box">
  <div class="login-logo">
        <a href="#"><b>IMS</b></a>
    </div>

    <div class="card">

        <div class="card-body login-card-body">
    <form method="post">

<?php if ($showResetForm): ?>

    
    <div class="mb-3">

        <label>New Password</label>

        <div class="input-group">

            <input
                type="password"
                name="password"
                class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>">

            <span class="input-group-text">
                <i class="bi bi-lock-fill"></i>
            </span>

        </div>

        <?php if(isset($errors['password'])): ?>
            <div class="invalid-feedback d-block">
                <?= $errors['password']; ?>
            </div>
        <?php endif; ?>

    </div>

    <div class="mb-3">

        <label>Confirm Password</label>

        <div class="input-group">

            <input
                type="password"
                name="confirm_password"
                class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>">

            <span class="input-group-text">
                <i class="bi bi-lock-fill"></i>
            </span>

        </div>

        <?php if(isset($errors['confirm_password'])): ?>
            <div class="invalid-feedback d-block">
                <?= $errors['confirm_password']; ?>
            </div>
        <?php endif; ?>

    </div>

    <button
        type="submit"
        name="reset_password"
        class="btn btn-success w-100">

        Reset Password

    </button>
    <div class="mt-3 text-center">
    <a href="login.php" class="btn btn-outline-secondary w-100">
        <i class="bi bi-arrow-left"></i>
        Back to Login
    </a>
</div>
<?php endif; ?>
</form>
</div>
        </div>
        </div>

  </body>
    
    
  </form>
<!-- Before </body> -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script src="/Js/script.js"></script>
</body>
</html>

