<?php
require_once __DIR__ . "../../includes/init.php";
require_once __DIR__ .  "../../includes/timeout.php";

Auth::requireRole('SUPERADMIN');


$conn = Database::getConn();
// var_dump($users);

if(isset($_GET['id'])){
  $userId = $_GET['id'];
  // var_dump($userId);
  $userInfo = User::getUserData($conn, $userId);
  $companies = Company::getCompanies($conn);
  
}else{
    $userId = null;
    die("User ID not supplied, Profile cannot be found");
}

$name ="";
$email = "";
$role = "";
$companyId = "";

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $companyId = $_POST["companyId"];
    $role = $_POST['role'];
    $status = $_POST['status'];

    if ($name === '') {
        $errors['name'] = 'Name is required.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email.';
    }

    if (empty($errors)) {

        User::updateUserProfile(
            $conn,
            $name,
            $email,
            $role,
            $companyId,
            $userId
        );

        header("Location: users.php");
        exit;
    }
}
?>


<?php require "./includes/header.php"?>


<div class="card">
    <div class="card-header">
        <h3 class="card-title">User Profile</h3>
    </div>

    <div class="card-body">

        <?php if (empty($userInfo)): ?>

            <div class="alert alert-warning mb-0">
                No user profile found.
            </div>

        <?php else: ?>

        <form method="post" class="row g-3">

            <div class="col-md-6">
                <label for="name" class="form-label">
                    Name
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="name"
                    name="name"
                    value="<?= htmlspecialchars($userInfo[0]['name']) ?>">
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">
                    Email
                </label>

                <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($userInfo[0]['email']) ?>">
            </div>
            <div class="col-md-6">
              <label for="company" class="form-label">Company</label>

              <select
                  class="form-select"
                  id="company"
                  name="companyId"
                  required>

                  <option value="">Select Company</option>

                  <?php foreach ($companies as $company): ?>

                      <option
                          value="<?= $company['id']; ?>"
                          <?= ($company['id'] == $userInfo[0]['company_id']) ? 'selected' : ''; ?>>

                          <?= htmlspecialchars($company['company_name']); ?>

                      </option>

                  <?php endforeach; ?>

              </select>
          </div>

            <div class="col-md-6">
                <label for="role" class="form-label">
                    Role
                </label>

                <select
                    class="form-select"
                    id="role"
                    name="role">

                    <option value="ADMIN"
                        <?= $userInfo[0]['role'] === 'ADMIN' ? 'selected' : '' ?>>
                        Admin
                    </option>

                    <option value="USER"
                        <?= $userInfo[0]['role'] === 'USER' ? 'selected' : '' ?>>
                        User
                    </option>

                </select>
            </div>

            
            <div class="col-12">

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="bi bi-check-circle"></i>
                    Save Changes

                </button>

                <a
                    href="users.php"
                    class="btn btn-outline-secondary">

                    Cancel

                </a>

            </div>

        </form>

        <?php endif; ?>

    </div>
</div>
    

<?php require "./includes/footer.php"?>