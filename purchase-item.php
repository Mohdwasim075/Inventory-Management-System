<?php 
require "includes/init.php";
require "includes/timeout.php";


Auth::requireLogin();
Auth::requireRole('USER');

$orderSupplier= "";
$orderNumber = "";
$orderStatus = "";
$purchaseAmount= "";
$errors= [];

$conn = require "includes/db.php";


$products = Product::getAll($conn,Auth::companyId() );
$purchaseOrders = Purchase::getPurchaseOrder($conn, Auth::companyId());

      
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Purchase Order Validation
    $orderID = isset($_POST['OrderID']) ? (int)$_POST['OrderID'] : 0;

    if ($orderID <= 0) {
        $errors[] = "Please select a purchase order.";
    }

    // Purchase Items Validation
    if (!isset($_POST['items']) || !is_array($_POST['items'])) {

        $errors[] = "Please add at least one product.";

    } else {

        $orderItems = [];

        foreach ($_POST['items'] as $index => $item) {

            $productId = isset($item['product_id']) ? (int)$item['product_id'] : 0;
            $quantity  = isset($item['quantity']) ? (int)$item['quantity'] : 0;
            $unitPrice = isset($item['unit_price']) ? (float)$item['unit_price'] : 0;

            if ($productId <= 0) {
                $errors[] = "Row " . ($index + 1) . ": Invalid product.";
            }

            if ($quantity <= 0) {
                $errors[] = "Row " . ($index + 1) . ": Quantity must be greater than zero.";
            }

            if ($unitPrice <= 0) {
                $errors[] = "Row " . ($index + 1) . ": Unit price must be greater than zero.";
            }

            $orderItems[] = [
                'product_id' => $productId,
                'quantity'   => $quantity,
                'unit_price' => $unitPrice
            ];
        }
    }

    // Save Purchase Items
    if (empty($errors)) {

        Purchase::addPurchaseItems(
            $conn,
            $_SESSION['user']['company_id'],
            $orderID,
            $orderItems
        );

        // Optional
        // header("Location: purchase.php");
        // exit;
    }
}

?>
<?php require "includes/header.php"; ?>

<div class="card card-primary mt-3">

    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-boxes mr-1"></i>
            Add Purchase Items
        </h3>
    </div>

    <div class="card-body">

        <form method="post">

            <?php if (!empty($errors)): ?>

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        <?php foreach ($errors as $error): ?>

                            <li><?= htmlspecialchars($error) ?></li>

                        <?php endforeach; ?>

                    </ul>

                </div>

            <?php endif; ?>

            <div class="row">

                <!-- Purchase Order -->

                <div class="col-md-8 mb-3">

                    <label for="order-ID">
                        Purchase Order
                    </label>

                    <select
                        name="OrderID"
                        id="order-ID"
                        class="form-control">

                        <option value="">
                            Choose Purchase Order
                        </option>

                        <?php foreach ($purchaseOrders as $purchaseOrder): ?>

                            <option
                                value="<?= $purchaseOrder['id']; ?>">

                                <?= htmlspecialchars($purchaseOrder['po_number']); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Product -->

                <div class="col-md-5">

                    <label for="product_id">
                        Product
                    </label>

                    <select
                        id="product_id"
                        class="form-control">

                        <option value="">
                            Select Product
                        </option>

                        <?php foreach ($products as $product): ?>

                            <option value="<?= $product['id']; ?>">

                                <?= htmlspecialchars($product['product_name']); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Quantity -->

                <div class="col-md-2">

                    <label for="quantity">
                        Quantity
                    </label>

                    <input
                        type="number"
                        id="quantity"
                        class="form-control"
                        min="1"
                        placeholder="Qty">

                </div>

                <!-- Unit Price -->

                <div class="col-md-3">

                    <label for="unit_price">
                        Unit Price
                    </label>

                    <input
                        type="number"
                        id="unit_price"
                        class="form-control"
                        step="0.01"
                        min="0"
                        placeholder="0.00">

                </div>

                <!-- Button -->

                <div class="col-md-2 d-flex align-items-end">

                    <button
                        type="button"
                        id="addProduct"
                        class="btn btn-primary btn-block">

                        <i class="fas fa-plus"></i>
                        Add

                    </button>

                </div>

            </div>

            <hr>

            <div class="table-responsive">

                <table
                    class="table table-bordered table-striped table-hover"
                    id="purchaseItemsTable">

                    <thead class="thead-dark">

                        <tr>

                            <th>Product</th>
                            <th width="120">Quantity</th>
                            <th width="150">Unit Price</th>
                            <th width="150">Total</th>
                            <th width="80">Action</th>

                        </tr>

                    </thead>

                    <tbody>
                    </tbody>

                </table>

            </div>

            <div class="text-right">

                <button
                    type="submit"
                    class="btn btn-success">

                    <i class="fas fa-save"></i>
                    Save Purchase Items

                </button>

            </div>

        </form>

    </div>

</div>

<?php require "includes/footer.php"; ?>