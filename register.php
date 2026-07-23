<?php

require "includes/init.php";
require "includes/timeout.php";

$businessName = '';
$fullName= "";
$email = "";
$phoneNumber = "";
$password = "";
$confirmPassword = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $conn = require 'includes/db.php';

    $businessName = trim($_POST['businessName']);
    $fullName = trim($_POST['fullName'] ?? '');
    $phoneNumber = trim($_POST["phoneNumber"]);
    $email = trim($_POST["email"] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST["confirmPassword"]?? '';


    // Validation
    if ($businessName === '') {
        $errors['businessName'] = 'Business Name is required.';
    }


    if ($fullName === '') {
        $errors['fullName'] = 'FullName is required.';
    }
    if ($phoneNumber === '') {

    $errors['phoneNumber'] = "Phone number is required.";

    } elseif (!ctype_digit($phoneNumber)) {

        $errors['phoneNumber'] =
            "Phone number should contain only digits.";

    } elseif (strlen($phoneNumber) !== 10) {

        $errors['phoneNumber'] =
            "Phone number must contain exactly 10 digits.";

    }

    if ($password === '') {
        $errors['password'] = 'Password is required.';
    }
     if ($email === "") {

        $errors['email'] = "Email is required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $errors['email'] = "Invalid email format.";

    }

    if($confirmPassword == ''){
        $errors['confirmPassword'] = "The above password should be retyped";
    }elseif($password !== $confirmPassword){
        $errors['confirmPassword'] = 'Passwords do not match';
    }

    if (empty($errors)) {

        $companyId = Company::createCompany($conn, $businessName);

        if(User::addNewUser($conn, $companyId, $fullName, $email,$phoneNumber,  $password)){
            $_SESSION['user-account'] = [

            'type' => 'success',

            'message' =>
                'Account has been  created. Login with your credentials.'

        ];

        Url::redirect('/login.php');
        exit;

        }else{
            $errors['general'] = "Unable to register your Account";
        }
            

        
    }
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
<body class="login-page" id="register-body">

<div class="register-box">

    <div class="card card-outline card-primary">

        <div class="card-body login-card-body">

            <div class="login-logo mb-3">
                <a href="#"><b>IMS</b></a>
            </div>

            <p class="login-box-msg">
                Register to create a new account
            </p>

            <?php if (!empty($errors['general'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($errors['general']) ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="row g-3">

                    <!-- Business Name -->
                    <div class="col-md-6">

                        <label class="form-label">
                            Business Name
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-shop"></i>
                            </span>

                            <input
                                type="text"
                                name="businessName"
                                class="form-control <?= isset($errors['businessName']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($businessName ?? '') ?>"
                                autocomplete="organization"
                                >

                        </div>

                        <?php if(isset($errors['businessName'])): ?>

                            <div class="invalid-feedback d-block">
                                <?= htmlspecialchars($errors['businessName']) ?>
                            </div>

                        <?php endif; ?>

                    </div>

                    <!-- Full Name -->
                    <div class="col-md-6">

                        <label class="form-label">
                            Full Name
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-person-fill"></i>
                            </span>

                            <input
                                type="text"
                                name="fullName"
                                class="form-control <?= isset($errors['fullName']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($fullName ?? '') ?>"
                                autocomplete="name"
                                >

                        </div>

                        <?php if(isset($errors['fullName'])): ?>

                            <div class="invalid-feedback d-block">
                                <?= htmlspecialchars($errors['fullName']) ?>
                            </div>

                        <?php endif; ?>

                    </div>

                    <!-- Email -->
                    <div class="col-md-6">

                        <label class="form-label">
                            Email Address
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-envelope-fill"></i>
                            </span>

                            <input
                                type="email"
                                name="email"
                                class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($email ?? '') ?>"
                                autocomplete="email"
                                >

                        </div>

                        <?php if(isset($errors['email'])): ?>

                            <div class="invalid-feedback d-block">
                                <?= htmlspecialchars($errors['email']) ?>
                            </div>

                        <?php endif; ?>

                    </div>

                           <!-- Phone-no -->
                    <div class="col-md-6">

                        <label class="form-label">
                            Phone Number
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-telephone-fill"></i>
                            </span>

                            <input
                                type="text"
                                name="phoneNumber"
                                class="form-control <?= isset($errors['phoneNumber']) ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($phoneNumber ?? '') ?>"
                                "
                                >

                        </div>

                        <?php if(isset($errors['phoneNumber'])): ?>

                            <div class="invalid-feedback d-block">
                                <?= htmlspecialchars($errors['phoneNumber']) ?>
                            </div>

                        <?php endif; ?>

                    </div>

                    <!-- Password -->
                    <div class="col-md-6">

                        <label class="form-label">
                            Password
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-lock-fill"></i>
                            </span>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                               
                                >

                          

                        </div>

                        <?php if(isset($errors['password'])): ?>

                            <div class="invalid-feedback d-block">
                                <?= htmlspecialchars($errors['password']) ?>
                            </div>

                        <?php endif; ?>

                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-6">

                        <label class="form-label">
                            Confirm Password
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-shield-lock-fill"></i>
                            </span>

                            <input
                                type="password"
                                id="confirmPassword"
                                name="confirmPassword"
                                class="form-control <?= isset($errors['confirmPassword']) ? 'is-invalid' : '' ?>"
                               
                                >

                        
                        </div>

                        <?php if(isset($errors['confirmPassword'])): ?>

                            <div class="invalid-feedback d-block">
                                <?= htmlspecialchars($errors['confirmPassword']) ?>
                            </div>

                        <?php endif; ?>

                    </div>

                </div>

                <div class="d-grid mt-4">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Create Account

                    </button>

                </div>

                <div class="text-center mt-3">

                    <a href="login.php">
                        Already have an account? Login
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>


    

<!-- Before </body> -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script src="/Js/script.js"></script>
</body>
</html>