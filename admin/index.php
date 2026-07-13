<?php


require "../includes/init.php";
require "../includes/timeout.php";


Auth::requireRole('SUPERADMIN');



$conn = Database::getConn();
$totalCompanies = Dashboard::getTotalCompanies($conn);
// var_dump($totalCompanies);
$activeCompanies = Dashboard::getActiveCompanies($conn);
$suspendedCompanies = Dashboard::getSuspendedCompanies($conn);
$totalUsers = Dashboard::getTotalUsers($conn);
$newCompaniesThisMonth = Dashboard::getNewCompaniesThisMonth($conn);
$recentCompanies = Dashboard::getRecentCompanies($conn);
$recentUsers = Dashboard::getRecentUsers($conn);

?>
<?php require "./includes/header.php"?>


<div class="app-content-header">
<div class="container mt-4">

    <div class="card shadow-sm">

        <div class="card-header">
            <h2>Dashboard</h2>
        </div>

        <div class="card-body">

            <section class="content pt-3">

                <div class="container-fluid">

                    <!-- KPI Cards -->

                    <div class="row">

                        <div class="col-lg-3 col-6">

                            <div class="small-box text-bg-primary">

                                <div class="inner">

                                    <h3><?= $totalCompanies ?></h3>

                                    <p>Companies</p>

                                </div>

                                <div class="small-box-icon">
                                    <i class="bi bi-buildings"></i>
                                </div>

                            </div>

                        </div>

                        <div class="col-lg-3 col-6">

                            <div class="small-box text-bg-success">

                                <div class="inner">

                                    <h3><?= $activeCompanies ?></h3>

                                    <p>Active Companies</p>

                                </div>

                                <div class="small-box-icon">
                                    <i class="bi bi-check-circle"></i>
                                </div>

                            </div>

                        </div>

                        <div class="col-lg-2 col-6">

                            <div class="small-box text-bg-danger">

                                <div class="inner">

                                    <h3><?= $suspendedCompanies ?></h3>

                                    <p>Suspended</p>

                                </div>

                                <div class="small-box-icon">
                                    <i class="bi bi-x-circle"></i>
                                </div>

                            </div>

                        </div>

                        <div class="col-lg-2 col-6">

                            <div class="small-box text-bg-warning">

                                <div class="inner">

                                    <h3><?= $totalUsers ?></h3>

                                    <p>Users</p>

                                </div>

                                <div class="small-box-icon">
                                    <i class="bi bi-people-fill"></i>
                                </div>

                            </div>

                        </div>

                        <div class="col-lg-2 col-6">

                            <div class="small-box text-bg-info">

                                <div class="inner">

                                    <h3><?= $newCompaniesThisMonth ?></h3>

                                    <p>New</p>

                                </div>

                                <div class="small-box-icon">
                                    <i class="bi bi-building-add"></i>
                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Recently Registered Companies -->

                    <div class="row">

                        <div class="col-md-12">

                            <div class="card">

                                <div class="card-header bg-primary">

                                    <h3 class="card-title">

                                        Recently Registered Companies

                                    </h3>

                                </div>

                                <div class="card-body p-0">

                                    <table class="table table-striped">

                                        <thead>

                                            <tr>

                                                <th>Company</th>
                                                <th>Status</th>
                                                <th>Created On</th>
                                                <th class="text-end">Users</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                        <?php foreach($recentCompanies as $company): ?>

                                            <tr>

                                                <td><?= htmlspecialchars($company['company_name']) ?></td>

                                                <td>

                                                    <?php if($company['subscription_status']=="ACTIVE"): ?>

                                                        <span class="badge text-bg-success">

                                                            ACTIVE

                                                        </span>

                                                    <?php else: ?>

                                                        <span class="badge text-bg-danger">

                                                            SUSPENDED

                                                        </span>

                                                    <?php endif; ?>

                                                </td>

                                                <td>

                                                    <?= date('d-M-Y', strtotime($company['created_at'])) ?>

                                                </td>

                                                <td class="text-end">

                                                    <?= $company['total_users'] ?>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Bottom Row -->

                    <div class="row mt-3">

                        <div class="col-md-6">

                            <div class="card">

                                <div class="card-header bg-success">

                                    <h3 class="card-title">

                                        Subscription Summary

                                    </h3>

                                </div>

                                <div class="card-body">

                                    <table class="table">

                                        <tr>

                                            <td>ACTIVE</td>

                                            <td class="text-end">

                                                <span class="badge text-bg-success">

                                                    <?= $activeCompanies ?>

                                                </span>

                                            </td>

                                        </tr>

                                        <tr>

                                            <td>SUSPENDED</td>

                                            <td class="text-end">

                                                <span class="badge text-bg-danger">

                                                    <?= $suspendedCompanies ?>

                                                </span>

                                            </td>

                                        </tr>

                                    </table>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="card">

                                <div class="card-header bg-info">

                                    <h3 class="card-title">

                                        Recent Users

                                    </h3>

                                </div>

                                <div class="card-body p-0">

                                    <table class="table table-hover">

                                        <thead>

                                            <tr>

                                                <th>User</th>

                                                <th>Company</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                        <?php foreach($recentUsers as $user): ?>

                                            <tr>

                                                <td><?= htmlspecialchars($user['name']) ?></td>

                                                <td><?= htmlspecialchars($user['company_name']) ?></td>

                                            </tr>

                                        <?php endforeach; ?>

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </section>

        </div>

    </div>

</div>

</div>

<?php require "./includes/footer.php"?>
