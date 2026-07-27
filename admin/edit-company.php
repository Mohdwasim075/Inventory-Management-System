<?php

require_once __DIR__ . "../../includes/init.php";
require_once __DIR__ .  "../../includes/timeout.php";

Auth::requireRole('SUPERADMIN');

$conn = Database::getConn();


// var_dump($users);

if(isset($_GET['id'])){
  $companyId = $_GET['id'];
   $company = User::getCompanyId($conn, $companyId);
//   var_dump($company);

 
  
}else{
    $companyId = null;
    die("Company ID not supplied, Company cannot be found");
}
$companyName = "";
$subscriptionStatus = "";

$errors = [];
$conn = Database::getConn();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $companyName = trim($_POST["company_name"]);
    $subscriptionStatus = $_POST["subscription_status"];

    if ($companyName == "") {
        $errors[] = "Company name is required.";
    }


    if (empty($errors)) {
       $result =  Company::updateCompany($conn, $companyName, $subscriptionStatus, $companyId);
       Url::redirect("/admin/list-company.php");

       }
    }


require "./includes/header.php";
?>

<div class="card card-primary">

    <div class="card-header">
        <h3 class="card-title">Update Company</h3>
    </div>

    <div class="card-body">

        <?php if (!empty($errors)): ?>

            <div class="alert alert-danger">

                <ul class="mb-0">

                    <?php foreach ($errors as $error): ?>

                        <li><?= htmlspecialchars($error) ?></li>

                    <?php endforeach; ?>

                </ul>

            </div>

        <?php endif; ?>

        <form method="post">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Company Name
                    </label>

                    <input
                        type="text"
                        name="company_name"
                        class="form-control"
                        value="<?= htmlspecialchars($company[0]['company_name']) ?>">

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Subscription Status
                    </label>

                    <select
    name="subscription_status"
    class="form-select">

    <option
        value="ACTIVE"
        <?= $company[0]['subscription_status'] == 'Active' ? 'selected' : '' ?>>
        ACTIVE
    </option>

    <option
        value="SUSPENDED"
        <?= $company[0]['subscription_status'] == 'Suspended' ? 'selected' : '' ?>>
        SUSPENDED
    </option>

</select>

                </div>

            </div>

            <button
                type="submit"
                class="btn btn-primary">

                Update Company

            </button>

            <a
                href="list-company.php"
                class="btn btn-secondary">

                Cancel

            </a>

        </form>

    </div>

</div>

<?php require "./includes/footer.php"; ?>