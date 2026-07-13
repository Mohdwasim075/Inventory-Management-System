<?php
//import the class autoloader and timeout script
require_once __DIR__ . "../../includes/init.php";
require_once __DIR__ .  "../../includes/timeout.php";

Auth::requireRole('SUPERADMIN');

$conn = Database::getConn(); 
$companies = Company::getCompanies($conn);




?>


<?php require "./includes/header.php"?>


<div class="card">
   
   <div class="card-header">

        <h3 class="card-title">Companies</h3>

        <a href="add-company.php"
           class="btn btn-primary float-end">
            Add Company
        </a>

    </div>

    <div class="card-body">

        <?php if(empty($companies)): ?>

            <p>No companies found.</p>

        <?php else: ?>

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

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

        <?php endif; ?>

    </div>
<?php require "./includes/footer.php"?>