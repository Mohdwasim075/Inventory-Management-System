<?php

require "includes/init.php";
require "includes/timeout.php";

$username = '';
$password = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conn = require 'includes/db.php';

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validation
    if ($username === '') {
        $errors['username'] = 'Username is required.';
    }

    if ($password === '') {
        $errors['password'] = 'Password is required.';
    }

    if (empty($errors)) {

        if (!User::authenticate($conn, $username, $password)) {
            $errors['general'] = 'Invalid username or password.';
        } else {

            $user = Auth::user();

            if (!User::authenticate($conn, $username, $password)) {

    $errors['general'] = 'Invalid username or password.';

        } else {

            $user = Auth::user();

            switch ($user['role']) {

                case 'SUPERADMIN':
                    Url::redirect("/admin/index.php");
                    break;

                case 'ADMIN':

                    $companyStatus = Company::getCompanyStatus(
                        $conn,
                        Auth::companyId()
                    );

                    if ($companyStatus !== "ACTIVE") {

                        $errors['general'] =
                            'Your company account has been suspended. Please contact the administrator.';
                        break;
                    }

                    Url::redirect("/index.php");
                    break;

                case 'USER':

                    $companyStatus = Company::getCompanyStatus(
                        $conn,
                        Auth::companyId()
                    );

                    if ($companyStatus !== "ACTIVE") {

                        $errors['general'] =
                            'Your company account has been suspended. Please contact the administrator.';
                        break;
                    }

                    if ($user['status'] !== "ACTIVE") {

                        $errors['general'] =
                            'Your user account has been suspended. Please contact the administrator.';
                        break;
                    }

                    Url::redirect("/index.php");
                    break;

                default:
                    $errors['general'] = "Invalid user role";
            }
        }
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
<body class="login-page">

<div class="login-box">

    <div class="card card-outline card-primary">

        <div class="card-body login-card-body">

            <!-- Logo -->
            <div class="login-logo">
                <a href="#"><b>IMS</b></a>
            </div>

            <p class="login-box-msg">
                Sign in to start your session
            </p>

            <!-- General Error -->
            <?php if (!empty($errors['general'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($errors['general']) ?>
                </div>
            <?php endif; ?>

            <!-- Flash Messages -->
            <?php
            $alerts = ['reset-alert', 'mail-alert', 'user-account'];

            foreach ($alerts as $alert):
                if (isset($_SESSION[$alert])):
            ?>

                <div class="alert alert-<?= $_SESSION[$alert]['type']; ?> alert-dismissible fade show">

                    <?= htmlspecialchars($_SESSION[$alert]['message']); ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            <?php
                    unset($_SESSION[$alert]);
                endif;
            endforeach;
            ?>

            <form method="POST">

                <!-- Username -->
                <div class="mb-3">

                    <label
                        for="username"
                        class="form-label">

                        Username

                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-person-fill"></i>
                        </span>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
                            value="<?= htmlspecialchars($username ?? '') ?>"
                            autocomplete="username"
                            >
                      

                    </div>
                      <div id="usercheck">

                        </div>

                    <?php if (isset($errors['username'])): ?>

                        <div class="invalid-feedback d-block">

                            <?= htmlspecialchars($errors['username']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <!-- Password -->
                <div class="mb-4">

                    <label
                        for="password"
                        class="form-label">

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
                            autocomplete="current-password"
                            >

                    </div>
                    <div id="passcheck">

                        </div>

                    <?php if (isset($errors['password'])): ?>

                        <div class="invalid-feedback d-block">

                            <?= htmlspecialchars($errors['password']) ?>

                        </div>

                    <?php endif; ?>

                </div>

                <!-- Links -->
                <div class="row mb-3">

                    <div class="col-6">

                        <a href="forgot-password.php">
                            Forgot Password?
                        </a>

                    </div>

                    <div class="col-6 text-end">

                        <a href="register.php">
                            Create Account
                        </a>

                    </div>

                </div>

                <!-- Login Button -->
                <div class="d-grid">

                    <button
                        id="login-button"
                        type="submit"
                        class="btn btn-primary">

                        <i class="bi bi-box-arrow-in-right me-2"></i>

                        Login

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</body>

<!-- Before </body> -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script src="/Js/script.js"></script>
</body>
</html>