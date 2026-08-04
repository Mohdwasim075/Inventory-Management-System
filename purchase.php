<?php


require "includes/init.php";
require "includes/timeout.php";

Auth::requireLogin();
Auth::requireRole('USER');




$conn = Database::getConn();

$orderSupplier = "";
$orderNumber = "";
$orderStatus = "";
$purchaseAmount = "";

$errors = [];


$companyId = (int)$_SESSION['user']['company_id'];
$paginator = new Paginator($_GET["page"] ?? 1 , 3 , Purchase::getTotalPurchase($conn, $companyId) );
//$purchase_orders = Purchase::getPurchaseOrder($conn, $companyId);
$purchase_orders = Purchase::getPurchaseList($conn, $companyId, $paginator->limit, $paginator->offset );

$suppliers = Supplier::getSuppliers($conn, $companyId);




// var_dump($purchase_orders);
// var_dump($purchase_orders[0]);
?>

<?php require "includes/header.php"; ?>

<div class="card card-primary mt-2">

    <div class="card-header">

        <h3 class="card-title">
            Purchase Orders
        </h3>

    </div>

    <div class="card-body">

        <div class="d-flex justify-content-end mb-3">

            <a
                href="purchase-add.php"
                class="btn btn-primary">

                <i class="bi bi-plus-circle me-1"></i>
                Add Purchase

            </a>

        </div>

        <?php if (empty($purchase_orders)): ?>

            <div class="alert alert-info">

                No Purchase Orders found.

            </div>

        <?php else: ?>

            <div class="table-responsive">

                <table class="table table-striped table-hover align-middle">

                    <thead>

                        <tr>

                            <th>S.no</th>
                            <th>Purchase Date</th>
                            <th>Order Number</th>
                            <th>Status</th>
                            <th>Total Amount</th>
                            <th class="text-center">Action</th>

                        </tr>

                    </thead>

                    <tbody>
                    <?php $no = $paginator->offset;?>

                    <?php foreach ($purchase_orders as $index => $purchase_order): ?>
                        <?php $no++; ?>

                        <?php

                        $statusClass = match ($purchase_order['status']) {
                            'RECEIVED' => 'bg-success',
                            'ORDERED'  => 'bg-warning',
                            'DRAFT'    => 'bg-secondary',
                            'CANCELLED'=> 'bg-danger',
                            default    => 'bg-primary'
                        };

                        ?>

                        <tr>

                            <td><?= $no; ?></td>

                            <td><?= htmlspecialchars($purchase_order['created_at']) ?></td>

                            <td><?= htmlspecialchars($purchase_order['po_number']) ?></td>

                            <td>

                                <span class="badge <?= $statusClass ?>">

                                    <?= htmlspecialchars($purchase_order['status']) ?>

                                </span>

                            </td>

                            <td>

                                ₹<?= number_format($purchase_order['total_amount'], 2) ?>

                            </td>

                            <td class="text-center">

                                <a
                                    href="purchase-order-view.php?id=<?= $purchase_order['id'] ?>"
                                    class="btn btn-sm btn-outline-primary">

                                    <i class="bi bi-eye"></i>
                                    View

                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </div>
   <?php require "./includes/pagination.php";?>
</div>

<?php require "includes/footer.php"; ?>