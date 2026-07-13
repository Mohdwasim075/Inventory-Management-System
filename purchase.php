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

$purchase_orders = Purchase::getPurchaseOrder($conn, $companyId);


$suppliers = Supplier::getSuppliers($conn, $companyId);




// var_dump($purchase_orders);
?>

<?php require "includes/header.php"?>
<div class="card  mt-2">
        <div class="card card-primary">

        <div class="card-header">
            <h3 class="card-title"> Purchase Order</h3>
        </div>
        <div class="mt-3">
        <a style="color:white" href="add-purchase.php">
            <button   class="btn btn-primary add-button" ><i class="bi bi-plus-circle"></i>Add Purchase</button>
        </a>
       </div>



        <!-- Purchase Orders -->

        <div class="d-flex justify-content-between align-items-center mb-3 mt-3">

           
            

        </div>

        <?php if(empty($purchase_orders)): ?>

            <div class="alert alert-info">

                No Purchase Orders found.

            </div>

        <?php else: ?>

            <div class="table-responsive">

                <table class="table table-striped table-hover">

                    <thead>

                        <tr>

                            <th>S.No</th>
                            <th>Purchase Date</th>
                            <th>Order Number</th>
                            <th>Status</th>
                            <th>Total Amount</th>
                            <th></th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php foreach($purchase_orders as $index => $purchase_order): ?>

                        <tr>

                            <td><?= $index + 1 ?></td>

                            <td><?= htmlspecialchars($purchase_order['created_at']); ?></td>

                            <td><?= htmlspecialchars($purchase_order['po_number']); ?></td>

                            <td>

                                <span class="badge bg-success">

                                    <?= htmlspecialchars($purchase_order['status']); ?>

                                </span>

                            </td>

                            <td>

                                ₹<?= number_format($purchase_order['total_amount'],2); ?>

                            </td>
                            <td>

                                <a
                                    href="purchase-order-view.php?id=<?= $purchase_order['id']; ?>"
                                    class="btn btn-sm btn-primary">

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

    </div>
</div>
                    


 <script>
document
    .getElementById('show-purchase-button')
    .addEventListener('click', function () {

        document
            .getElementById('purchase-form')
            .style.display = 'block';

});
</script>

<?php require "includes/footer.php"?>