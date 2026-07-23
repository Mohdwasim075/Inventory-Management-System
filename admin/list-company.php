<?php
//import the class autoloader and timeout script
require_once __DIR__ . "../../includes/init.php";
require_once __DIR__ .  "../../includes/timeout.php";

Auth::requireRole('SUPERADMIN');

$conn = Database::getConn(); 
$companies = Company::getCompanies($conn);




?>


<?php require "./includes/header.php"?>


<div class="card card-primary">
   
   <div class="card-header">

        <h3 class="card-title">Companies</h3>

       

    </div>
   

    <div class="card-body">
          <a href="add-company.php"
           class="btn btn-primary float-end">
            Add Company
        </a>

        <?php if(empty($companies)): ?>

            <p>No companies found.</p>

        <?php else: ?>
        <div class="card-body">
        <?php if (isset($_SESSION['company-action'])): ?>

            <div class="alert alert-<?= $_SESSION['company-action']['type']; ?> alert-dismissible fade show">

                <?= htmlspecialchars($_SESSION['company-action']['message']); ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">

                </button>

            </div>

            <?php unset($_SESSION['company-action']); ?>

        <?php endif; ?>
        <table class="table table-striped table-hover">

            <thead>

                <tr>

                    <th>S.no</th>
                    <th>Company Name</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th width="90"></th>

                </tr>

            </thead>

            <tbody>

            <?php foreach($companies as $index => $company): ?>

                <tr>

                    <td><?= $index + 1 ?></td>

                    <td><?= htmlspecialchars($company['company_name']) ?></td>

                    <td>

                        <?php if($company['subscription_status']=="ACTIVE"): ?>

                            <span class="badge bg-success">
                                ACTIVE
                            </span>

                        <?php else: ?>

                            <span class="badge bg-danger">
                                SUSPENDED
                            </span>

                        <?php endif; ?>

                    </td>

                    
                    <td><?= date('d M Y', strtotime($company['created_at'])) ?></td>

                    <td>

                        <a
                            href="edit-company.php?id=<?= $company['id'] ?>"
                            class="btn btn-success btn-sm">

                            Edit

                        </a>

                    </td>

                    <td>
                    <a href="delete-company.php?id=<?= $company['id'] ?>"
                    class="btn btn-danger"> Delete
                    </a>
                </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

        <?php endif; ?>

    </div>
<?php require "./includes/footer.php"?>