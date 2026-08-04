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

$products = Product::getAll($conn,Auth::companyId() );

if(isset($_POST['items'])){
    var_dump($_POST['items']);

}
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
    
    // Purchase Items Validation
    if (!isset($_POST['items']) || !is_array($_POST['items'])) {
       

        $errors[] = "Please add at least one product.";

    } else {
    
        $orderItems = [];
        var_dump($orderItems);
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
    // Save Purchase Order

    if (empty($errors)) {

        $orderId = Purchase::addPurchaseOrder(

            $conn,
            Auth::companyId(),
            $orderNumber,
            (int)$orderSupplier,
            $orderStatus,
            (float)$purchaseAmount

        );
       $success = Purchase::addPurchaseItems(
            $conn,
            $_SESSION['user']['company_id'],
            $orderId,
            $orderItems
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
            <h3 class="card-title">Add Purchase Order</h3>
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
           
              

            </div>


        </form>

    </div>

</div>

</div>

<?php require "includes/footer.php";?>
