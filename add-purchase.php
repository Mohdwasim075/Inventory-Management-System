<?php 
require "includes/init.php";

Auth::requireLogin();
Auth::requireRole('USER');


$orderSupplier = "";
$orderNumber = "";
$orderStatus = "";
$purchaseAmount = "";
$errors = [];
$conn = Database::getConn();
$suppliers = Supplier::getSuppliers($conn, Auth::companyId());
if ($_SERVER["REQUEST_METHOD"] === "POST") {
   

    $orderSupplier = trim($_POST["orderSupplier"] ?? "");
    $orderNumber = trim($_POST["orderNumber"] ?? "");
    $orderStatus = trim($_POST["orderStatus"] ?? "");
    $purchaseAmount = trim($_POST["purchaseAmount"] ?? "");

    // Validation

    if ($orderSupplier === "") {
        $errors['orderSupplier'] = "Please select a supplier.";
    }

    if ($orderNumber === "") {
        $errors['orderNumber'] = "Purchase Order Number is required.";
    }

    if ($orderStatus === "") {
        $errors['orderStatus'] = "Please select an order status.";
    }

    if ($purchaseAmount === "") {

        $errors['purchaseAmount'] = "Purchase Amount is required.";

    } elseif (!is_numeric($purchaseAmount)) {

        $errors['purchaseAmount'] = "Purchase Amount must be a valid number.";

    } elseif ((float)$purchaseAmount <= 0) {

        $errors['purchaseAmount'] = "Purchase Amount must be greater than zero.";
    }

    // Save Purchase Order

    if (empty($errors)) {

        $success = Purchase::addPurchaseOrder(

            $conn,
            $companyId,
            $orderNumber,
            (int)$orderSupplier,
            $orderStatus,
            (float)$purchaseAmount

        );

        Purchase::updatePurchaseOrderTotal(
                                $conn,
                                $orderID
);

        if ($success) {

            Url::redirect("/purchase.php");
            exit;

        } else {

            $errors['database'] = "Unable to add Purchase Order.";

        }

    }

}

?>
<?php require "includes/header.php" ?>

<div class="card mt-3">
    <div
    id="purchase-form"
    >

    <div class="card card-primary">

        <div class="card-header">
            <h3 class="card-title">Create Purchase Order</h3>
        </div>

        <form method="post">

            <div class="card-body">

                <div class="row">

                    <!-- Supplier -->

                    <div class="col-md-6 mb-3">

                        <label for="order-supplier" class="form-label">
                            Supplier
                        </label>

                        <select
                            class="form-select <?= isset($errors['orderSupplier']) ? 'is-invalid' : ''; ?>"
                            name="orderSupplier"
                            id="order-supplier">

                            <option value="">Choose Supplier</option>

                            <?php foreach ($suppliers as $supplier): ?>

                                <option
                                    value="<?= $supplier['id']; ?>"
                                    <?= $orderSupplier == $supplier['id'] ? 'selected' : ''; ?>>

                                    <?= htmlspecialchars($supplier['supplier_name']); ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                        <?php if(isset($errors['orderSupplier'])): ?>

                            <div class="invalid-feedback">
                                <?= $errors['orderSupplier']; ?>
                            </div>

                        <?php endif; ?>

                    </div>

                    <!-- Purchase Order Number -->

                    <div class="col-md-6 mb-3">

                        <label for="order-number" class="form-label">
                            Purchase Order No
                        </label>

                        <input
                            type="text"
                            class="form-control <?= isset($errors['orderNumber']) ? 'is-invalid' : ''; ?>"
                            id="order-number"
                            name="orderNumber"
                            value="<?= htmlspecialchars($orderNumber); ?>">

                        <?php if(isset($errors['orderNumber'])): ?>

                            <div class="invalid-feedback">
                                <?= $errors['orderNumber']; ?>
                            </div>

                        <?php endif; ?>

                    </div>

                </div>

                <div class="row">

                    <!-- Order Status -->

                    <div class="col-md-6 mb-3">

                        <label
                            for="order-status"
                            class="form-label">

                            Order Status

                        </label>

                        <select
                            class="form-select <?= isset($errors['orderStatus']) ? 'is-invalid' : ''; ?>"
                            name="orderStatus"
                            id="order-status">

                            <option value="">Choose Status</option>

                            <option
                                value="RECEIVED"
                                <?= $orderStatus == 'RECEIVED' ? 'selected' : ''; ?>>

                                RECEIVED

                            </option>

                        </select>

                        <?php if(isset($errors['orderStatus'])): ?>

                            <div class="invalid-feedback">
                                <?= $errors['orderStatus']; ?>
                            </div>

                        <?php endif; ?>

                    </div>

                    <!-- Purchase Amount -->

                    <div class="col-md-6 mb-3">

                        <label
                            for="purchase-amount"
                            class="form-label">

                            Purchase Amount

                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            class="form-control <?= isset($errors['purchaseAmount']) ? 'is-invalid' : ''; ?>"
                            id="purchase-amount"
                            name="purchaseAmount"
                            value="<?= htmlspecialchars($purchaseAmount); ?>">

                        <?php if(isset($errors['purchaseAmount'])): ?>

                            <div class="invalid-feedback">
                                <?= $errors['purchaseAmount']; ?>
                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

            <div class="card-footer">

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="bi bi-plus-circle"></i>

                    Add Purchase Order

                </button>

                <a
                    href="purchase.php"
                    class="btn btn-secondary">

                    Cancel

                </a>

            </div>

        </form>

    </div>

</div>

</div>

<?php require "includes/footer.php";?>
