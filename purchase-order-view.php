<?php

require "includes/init.php";
require "includes/timeout.php";

Auth::requireLogin();
Auth::requireRole('USER');

$conn = Database::getConn();
if(isset($_GET['id'])){
    $purchaseId = intval($_GET['id']);
    $companyId = Auth::companyId();
    $order = Purchase::findPurchaseOrder($conn, $companyId, $purchaseId);
    $items = Purchase::getPurchaseOrderItems($conn, $purchaseId);
    $purchaseAmount = Purchase::totalAmount($conn, $purchaseId);
    var_dump($order);
    if(! $order){
        die("Purchase order can't be found");
    }
    

}else{
    die("Purchase order Id not supplied");
}


?>

<?php require "includes/header.php"?>
<div class="card card-primary mt-2">
    <div class="card-header">

        <h3 class="card-title">Purchase Order No: <?= htmlspecialchars($order[0]['po_number']) ?></h3>

       

    </div>
    <div class="card-body">
        <p>Supplier :
        <?= htmlspecialchars($order[0]['supplier_name']) ?>
        </p>

        <p>Status :
        <?= htmlspecialchars($order[0]['status']) ?>
        </p>

        <p>Date :
        <?= date('d M Y', strtotime($order[0]['created_at'])) ?>
        </p>

                <table class="table table-bordered">

                <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Unit Cost</th>
                    <th class="text-end">Total</th>
                </tr>
                </thead>

                <tbody>

                <?php foreach($items as $item): ?>

                <tr>

                <td>
                <?= htmlspecialchars($item['product_name']) ?>
                </td>

                <td class="text-end">
                <?= $item['quantity'] ?>
                </td>

                <td class="text-end">
                <?= number_format($item['unit_cost'],2) ?>
                </td>

                <td class="text-end">
                <?= number_format($item['line_total'],2) ?>
                </td>

                </tr>

                <?php endforeach; ?>

                </tbody>

                <tfoot>

                <tr>

                <th colspan="3" class="text-end">
                Grand Total
                </th>

                <th class="text-end">
                <?= number_format($purchaseAmount,2) ?>
                </th>

                </tr>

                </tfoot>

                </table>
</div>
</div>

<?php require "includes/footer.php"?>
