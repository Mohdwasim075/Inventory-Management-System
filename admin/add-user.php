<?php
require_once __DIR__ . "../../includes/init.php";
require_once __DIR__ .  "../../includes/timeout.php";

Auth::requireRole('SUPERADMIN');

$conn = Database::getConn();
// var_dump($users);

$companies = Company::getCompanies($conn);

$name = "";
$email = "";
$role = "";
$companyId = "";
$password = "";
$status = "";
$errors = [];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $companyId = $_POST["companyId"];
    $status = $_POST['status'] ?? '';

    // Validation
    if ($name === '') {
        $errors['name'] = 'Name is required.';
    }

    if ($email === '') {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email address.';
    }

    if ($password === '') {
        $errors['password'] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters.';
    }

    if ($role === '') {
        $errors['role'] = 'Please select a role.';
    }
     if ($companyId === '') {
        $errors['companyId'] = 'Please select a company.';
    }

    if ($status === '') {
        $errors['status'] = 'Please select a status.';
    }

    if (empty($errors)) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if (!User::addNewUser(
            $conn,
            $name,
            $email,
            $hashedPassword,
            $role,
            $status
        )) {
            $errors['general'] = 'Unable to add user.';
        }
    }

    



}
?>


<?php require "./includes/header.php"; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add User</h3>
    </div>

    <form method="post">

        <div class="card-body">

            <?php if (!empty($errors['general'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($errors['general']) ?>
                </div>
            <?php endif; ?>

            <div class="row g-3">

                <!-- Name -->
                <div class="col-md-6">

                    <label for="name" class="form-label">
                        Name
                    </label>

                    <input
                        type="text"
                        class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                        id="name"
                        name="name"
                        value="<?= htmlspecialchars($name) ?>">

                    <?php if (isset($errors['name'])): ?>
                        <div class="invalid-feedback">
                            <?= htmlspecialchars($errors['name']) ?>
                        </div>
                    <?php endif; ?>

                </div>

                <!-- Email -->
                <div class="col-md-6">

                    <label for="email" class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars($email) ?>">

                    <?php if (isset($errors['email'])): ?>
                        <div class="invalid-feedback">
                            <?= htmlspecialchars($errors['email']) ?>
                        </div>
                    <?php endif; ?>

                </div>
               <div class="col-md-6">

                    <label for="company_id" class="form-label">
                        Company
                    </label>

                    <select
                        class="form-select form-control <?= isset($errors['company']) ? 'is-invalid' : '' ?>"
                        id="companyId"
                        name="companyId"
                        required>

                        <option value="">Select Company</option>

                        <?php foreach ($companies as $company): ?>

                            <option
                                value="<?= $company['id']; ?>"
                                <?= ($companyId == $company['id']) ? 'selected' : ''; ?>>

                                <?= htmlspecialchars($company['company_name']); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Role -->
                <div class="col-md-6">

                    <label class="form-label" for="role">
                        Role
                    </label>

                    <select
                        class="form-select <?= isset($errors['role']) ? 'is-invalid' : '' ?>"
                        name="role"
                        id="role">

                        <option value="">Select Role</option>

                        <option value="ADMIN"
                            <?= $role === 'ADMIN' ? 'selected' : '' ?>>
                            Admin
                        </option>

                        <option value="USER"
                            <?= $role === 'USER' ? 'selected' : '' ?>>
                            User
                        </option>

                    </select>

                    <?php if (isset($errors['role'])): ?>
                        <div class="invalid-feedback">
                            <?= htmlspecialchars($errors['role']) ?>
                        </div>
                    <?php endif; ?>

                </div>

                <!-- Password -->
                <div class="col-md-6">

                    <label class="form-label" for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                        id="password"
                        name="password">

                    <?php if (isset($errors['password'])): ?>
                        <div class="invalid-feedback">
                            <?= htmlspecialchars($errors['password']) ?>
                        </div>
                    <?php endif; ?>

                </div>

                <!-- Status -->
                <div class="col-md-6">

                    <label class="form-label" for="status">
                        Status
                    </label>

                    <select
                        class="form-select <?= isset($errors['status']) ? 'is-invalid' : '' ?>"
                        id="status"
                        name="status">

                        <option value="ACTIVE"
                            <?= $status === 'ACTIVE' ? 'selected' : '' ?>>
                            Active
                        </option>

                        <option value="SUSPENDED"
                            <?= $status === 'SUSPENDED' ? 'selected' : '' ?>>
                            Suspended
                        </option>

                    </select>

                    <?php if (isset($errors['status'])): ?>
                        <div class="invalid-feedback">
                            <?= htmlspecialchars($errors['status']) ?>
                        </div>
                    <?php endif; ?>
                    <div class="col-12 mt-3">
                        <button class="btn btn-primary" type="submit">
                        <i class="bi bi-person-plus"></i>
                            Add User
                        </button>

                        <a href="users.php" class="btn btn-secondary">
                            Cancel
                        </a>

                    </div>
                    


                </div>
                 
            </div>

        </div>

       
           



    </form>

</div>

<?php require "./includes/footer.php"; ?>