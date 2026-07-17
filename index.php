<?php 

require "includes/init.php";
require "includes/timeout.php";
$conn = require "includes/db.php";


Auth::requireRole('USER');
Auth::requireLogin();


$companyId = Auth::companyId();

$todaySales =  Dashboard::todaySales($conn, $companyId);
$todyProfit =  Dashboard::todayProfit($conn, $companyId);
$todayOrders =  Dashboard::todayOrders($conn, $companyId);
$todayItemsSold =  Dashboard::todayItemsSold($conn, $companyId);
$products = Dashboard::lowStockProducts($conn, $companyId);
$topSellingProducts = Dashboard::getTopSellingProducts($conn, $companyId);
$recentSales = Dashboard::getRecentsales($conn, $companyId);

?>
<?php require "includes/header.php"?>


<div class="container mt-3">

    <div class="card shadow-sm">

        <div class="card-header">
            <h2>Dashboard</h2>
        </div>

        <div class="card-body">
    <section class="content pt-3">
        <div class="container-fluid">

            <!-- Dashboard Cards -->
            <div class="row">

                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-primary">
                        <div class="inner">
                            <h3><?= '₹' . $todaySales?></h3>
                            <p>Today's Sales</p>
                        </div>
                        <div class="small-box-icon">
                            <i class="bi bi-currency-rupee"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-success">
                        <div class="inner">
                            <h3><?= '₹' . $todyProfit ?></h3>
                            <p>Today's Profit</p>
                        </div>
                        <div class="small-box-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3><?= $todayOrders ?></h3>
                            <p>Orders Today</p>
                        </div>
                        <div class="small-box-icon">
                            <i class="bi bi-bag-check"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-danger">
                        <div class="inner">
                            <h3><?= $todayItemsSold ?></h3>
                            <p>Items Sold</p>
                        </div>
                        <div class="small-box-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Row -->
            <div class="row">

                <!-- Top Selling Products -->
                <div class="col-md-6">

                    <div class="card">

                        <div class="card-header bg-warning">
                            <h3 class="card-title">
                                Top Selling Products
                            </h3>
                        </div>

                        <div class="card-body p-0">

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-end">Sold</th>
                                    </tr>
                                </thead>

                                <tbody>

                                <?php if (empty($topSellingProducts)): ?>

                                    <tr>
                                        <td colspan="2" class="text-center text-muted">
                                            No sales found.
                                        </td>
                                    </tr>

                                <?php else: ?>

                                    <?php foreach ($topSellingProducts as $product): ?>

                                    <tr>
                                        <td><?= htmlspecialchars($product['product_name']) ?></td>

                                        <td class="text-end">
                                            <span class="badge text-bg-warning">
                                                <?= $product['total_sold'] ?>
                                            </span>
                                        </td>
                                    </tr>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                                </tbody>
                            </table>

                        </div>

                    </div>

                </div>

                <!-- Low Stock -->
                <div class="col-md-6 mt-3">

                    <div class="card">

                        <div class="card-header bg-danger">
                            <h3 class="card-title">
                                Low Stock Alerts
                            </h3>
                        </div>

                        <div class="card-body p-0">

                            <table class="table table-striped">

                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-end">Available</th>
                                    </tr>
                                </thead>

                               <tbody>

                                        <?php if (empty($products)): ?>

                                        <tr>
                                            <td colspan="2" class="text-center text-muted">
                                                No low-stock products.
                                            </td>
                                        </tr>

                                        <?php else: ?>

                                        <?php foreach ($products as $product): ?>

                                        <tr>
                                            <td><?= htmlspecialchars($product['product_name']) ?></td>

                                            <td class="text-end">
                                                <span class="badge text-bg-danger">
                                                    <?= $product['quantity_available'] ?>
                                                </span>
                                            </td>
                                        </tr>

                                        <?php endforeach; ?>

                                        <?php endif; ?>

                                        </tbody>
                            </table>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Recent Sales -->

            <div class="row">

                <div class="col-md-12 mt-3">

                    <div class="card">

                        <div class="card-header bg-success">
                            <h3 class="card-title">
                                Recent Sales
                            </h3>
                        </div>

                        <div class="card-body p-0">

                            <table class="table table-hover">

                                <thead>

                                    <tr>
                                        <th>Invoice</th>
                                        <th class="text-end">Amount</th>
                                    </tr>

                                </thead>
                              
                                <tbody>
                                  <?php if (empty($recentSales)): ?>

                                        <tr>
                                            <td colspan="2" class="text-center text-muted">
                                                No recent  sales.
                                            </td>
                                        </tr>

                                        <?php else: ?>

                                        <?php foreach ($recentSales as $sale): ?>


                                    <tr>
                                            <td><?= htmlspecialchars($sale['invoice_number']) ?></td>

                                            <td class="text-end">
                                                <span class="badge text-bg-primary">
                                                    <?= $sale['invoice_total'] ?>
                                                </span>
                                            </td>
                                        </tr>

                                        <?php endforeach; ?>

                                        <?php endif; ?>

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
    
<?php require "includes/footer.php"?>