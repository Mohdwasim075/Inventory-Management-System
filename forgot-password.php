<?php 
require "includes/init.php";


$errors = [];
$email = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $conn = Database::getConn();

    $email = trim($_POST["email"] ?? "");

    if ($email === "") {

        $errors['email'] = "Email is required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $errors['email'] = "Invalid email format.";

    } else {

        $user = User::findUserByEmail($conn, $email);

        if ($user) {

            $resetLink = PasswordReset::sendResetLink(
                $conn,
                $user['userId']
            );

            Mail::sendPasswordReset($email, $resetLink);

        }

        $_SESSION['mail-alert'] = [

            'type' => 'success',

            'message' =>
                'If an account with that email address exists, a password reset link has been sent. Please check your inbox and spam folder.'

        ];

        Url::redirect('/login.php');
        exit;
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
<body class= "login-body">


    <div class="login-box">

        <div class="card">

            <div class="card-body login-card-body">

                <div class="login-logo">

                    <a href="#">

                        <b>IMS</b>

                    </a>

                </div>
                   
                

                <form method="post" onsubmit="verify_email.disabled = true; return true">

                    <div class="mb-3">

                        <label class="form-label">

                            Email

                        </label>

                        <div class="input-group">

                            <input
                                type="text"
                                name="email"
                                class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                placeholder="Enter your email"
                                value="<?= htmlspecialchars($email) ?>">

                            <span class="input-group-text">

                                <i class="bi bi-envelope-fill"></i>

                            </span>

                        </div>

                        <?php if (isset($errors['email'])): ?>

                            <div class="invalid-feedback d-block">

                                <?= $errors['email']; ?>

                            </div>

                        <?php endif; ?>

                    </div>

                    <button
                        type="submit"
                        name="verify_email"
                        class="btn btn-primary w-100 mt-3">

                        Send Reset Link

                    </button>

                    <div class="mt-3 text-center">

                        <a
                            href="login.php"
                            class="btn btn-outline-secondary w-100">

                            <i class="bi bi-arrow-left"></i>

                            Back to Login

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