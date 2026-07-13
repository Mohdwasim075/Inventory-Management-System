<?php

require_once __DIR__ . "../../includes/init.php";
require_once __DIR__ .  "../../includes/timeout.php";

Auth::requireRole('SUPERADMIN');

$conn = Database::getConn();

$companyName = "";
$subscriptionStatus = "ACTIVE";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $companyName = trim($_POST["company_name"]);
    $subscriptionStatus = $_POST["subscription_status"];

    if ($companyName == "") {
        $errors[] = "Company name is required.";
    }


    if (empty($errors)) {
       $result =  Company::createCompany($conn, $companyName, $subscriptionStatus);
        redirect("Location : /list-company.php");

       }
    }


require "./includes/header.php";
?>

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Create Company</h3>
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
                        value="<?= htmlspecialchars($companyName) ?>">

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Subscription Status
                    </label>

                    <select
                        name="subscription_status"
                        class="form-select">

                        <option value="ACTIVE">ACTIVE</option>

                        <option value="SUSPENDED">SUSPENDED</option>

                    </select>

                </div>

            </div>

            <button
                type="submit"
                class="btn btn-primary">

                Create Company

            </button>

            <a
                href="/admin/list-company.php"
                class="btn btn-secondary">

                Cancel

            </a>

        </form>

    </div>

</div>

<?php require "./includes/footer.php"; ?>