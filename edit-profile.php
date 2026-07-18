<?php
require "includes/init.php";
require "includes/timeout.php";


//Authenticate valid user to access the page
Auth::requireLogin();
Auth::requireRole('USER');

$conn = require 'includes/db.php';

$user = User::getUserProfile($conn, Auth::id());

var_dump($user);

// fetch the user data from db to display the user details 

$name = "";
$email = "";
$contactNumber = "";

$errors = [];
if(isset($_POST["save-profile"])){
    $name = trim($_POST["name"]?? "");
    $email = trim($_POST["email"] ?? "");
    $contactNumber = $_POST["contact_number"] ?? "" ;

    if($name === ""){
        $errors['name'] = "Name should not be empty";

    }elseif(preg_match('/\d/', $name)){
        $errors['name'] = "Name should not contain numeric values";
    }
     if($email === ""){
        $errors['email'] = "Email is required";

    }elseif(! filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors['email'] = "Enter Valid email";

    }
    if ($contactNumber === '') {

    $errors['contact_number'] = "Contact number is required.";

    } elseif (!ctype_digit($contactNumber)) {

        $errors['contact_number'] =
            "Contact number should contain only digits.";

    } elseif (strlen($contactNumber) !== 10) {

        $errors['contact_number'] =
            "Contact number must contain exactly 10 digits.";

    }
    if(empty($errors)){
        $data = [
            'name' => $name,
            'email' => $email,
            'contactNumber' => $contactNumber,
            'userId' => Auth::id()
        ];
        User::updateProfileChanges($conn, $data);


        Url::redirect("/profile.php");

    }

    
}

?>

<?php require "includes/header.php"?>


  <div class="card card-primary mt-2">

    <div class="card-header">

        <h3 class="card-title">

            Edit Profile

        </h3>

    </div>

    <form method="post">

        <div class="card-body">

            <div class="mb-3">

                <label
                    for="name"
                    class="form-label">

                    Name

                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control <?= isset($errors['name']) ? 'is-invalid' : ''?>"
                    value="<?= htmlspecialchars($name === "" ? $user[0]['name'] : $name )?>">
                
                    <?php if (isset($errors['name'])): ?>

                            <div class="invalid-feedback d-block">

                                <?= $errors['name']; ?>

                            </div>

                        <?php endif; ?>


            </div>

            <div class="mb-3">

                <label
                    for="email"
                    class="form-label">

                    Email

                </label>

                <input
                    type="text"
                    id="email"
                    name="email"
                    class="form-control <?= isset($errors['email']) ? 'is-invalid' : ''?>"
                    value="<?= htmlspecialchars($email === "" ? $user[0]['email'] : $email) ?>">

                     <?php if (isset($errors['email'])): ?>

                            <div class="invalid-feedback d-block">

                                <?= $errors['email']; ?>

                            </div>

                        <?php endif; ?>

            </div>

            <div class="mb-3">

                <label
                    for="contact_number"
                    class="form-label">

                    Contact No

                </label>

                <input
                    type="text"
                    id="contact_number"
                    name="contact_number"
                    class="form-control"
                    value="<?= htmlspecialchars($contactNumber === "" ? $user[0]['contact_no'] : $contactNumber) ?>">
                    
                     <?php if (isset($errors['contact_number'])): ?>

                            <div class="invalid-feedback d-block">

                                <?= $errors['contact_number']; ?>

                            </div>

                        <?php endif; ?>

            </div>

        </div>

        <div class="card-footer text-end">

            <button
                type="submit"
                name="save-profile"
                class="btn btn-primary">

                <i class="bi bi-check-circle"></i>
                Save Changes

            </button>

            <a
                href="profile.php"
                class="btn btn-secondary">

                Cancel

            </a>

        </div>

    </form>

</div>

        

    




<?php require "includes/footer.php"?>