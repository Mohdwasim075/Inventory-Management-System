<?php 
require "includes/init.php";



Auth::requireLogin();
Auth::requireRole('USER');

$conn = require "includes/db.php";

$companyId = Auth::companyId();

$products = Product::getAvaliableProducts($conn, $companyId);


// var_dump($products);
$customers = Customer::getCustomers($conn, $companyId);





$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Customer Validation
    $customerId = isset($_POST['customerId']) ? (int)$_POST['customerId'] : 0;

    if ($customerId <= 0) {
        $errors[] = "Please select a customer.";
    }

    // Sales Items Validation
    if (!isset($_POST['items']) || !is_array($_POST['items'])) {

        $errors[] = "Please add at least one product.";

    } else {

        $salesItems = [];

        foreach ($_POST['items'] as $index => $item) {

            $productId = isset($item['product_id']) ? (int)$item['product_id'] : 0;
            $quantity = isset($item['quantity']) ? (float)$item['quantity'] : 0;
            $salePrice = isset($item['sale_price']) ? (float)$item['sale_price'] : 0;

            if ($productId <= 0) {
                $errors[] = "Row " . ($index + 1) . ": Invalid product.";
            }

            if ($quantity <= 0) {
                $errors[] = "Row " . ($index + 1) . ": Quantity must be greater than zero.";
            }elseif($quantity ){

            }

            if ($salePrice <= 0) {
                $errors[] = "Row " . ($index + 1) . ": Sale price must be greater than zero.";
            }

            // Store cleaned values
            $salesItems[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'sale_price' => $salePrice
            ];
        }
    }

    // Save only if validation passed
    if (empty($errors)) {

        sales::addSalesItems(
            $conn,
            $companyId,
            $customerId,
            $salesItems
        );

        // Optional redirect
        // header("Location: sales-orders.php");
        // exit;
    }
}



?>
<?php require "includes/header.php"?>

<div class="card card-primary mt-3">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-shopping-cart mr-1"></i>
            Create Sales Order
        </h3>
    </div>

    <div class="card-body">

        <form method="post">

            <!-- Validation Errors -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible">
                    
                    

                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="row">

                <!-- Customer -->
                <div class="col-md-12 mb-3">
                    <label for="customer-Id">Customer</label>
                    <select name="customerId" id="customer-Id" class="form-control">
                        <option value="">Choose Customer</option>

                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= $customer["id"] ?>">
                                <?= htmlspecialchars($customer["customer_name"]) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <!-- Product -->
                <div class="col-md-5">
                    <label for="product_id">Product</label>

                    <select id="product_id" class="form-control">
                        <option value="">Select Product</option>

                        <?php foreach ($products as $product): ?>
                            <option value="<?= $product['id'] ?>">
                                <?= htmlspecialchars($product['product_name']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <!-- Quantity -->
                <div class="col-md-2">
                    <label for="quantity">Quantity</label>

                    <input
                        type="number"
                        id="quantity"
                        class="form-control"
                        placeholder="Qty"
                        min="1">
                         <div
                            id="stock-info"
                            class="alert alert-info mt-2 py-2"
                            style="display:none;">

                            Available Stock:
                            <strong id="stock-qty">0</strong>

    </div>
                </div>
                

                <!-- Sale Price -->
                <div class="col-md-3">
                    <label for="sale_price">Sale Price</label>

                    <input
                        type="number"
                        id="sale_price"
                        class="form-control"
                        placeholder="0.00"
                        step="0.01"
                        min="0">

                        
                </div>

                <!-- Add Button -->
                <div class="col-md-2 d-flex align-items-end">
                    <button
                        type="button"
                        id="addSaleProduct"
                        class="btn btn-primary btn-block">

                        <i class="fas fa-plus"></i>
                        Add
                    </button>
                </div>

            </div>

            <hr>

            <div class="table-responsive">

                <table class="table table-bordered table-striped table-hover" id="salesItemsTable">

                    <thead class="thead-dark">
                        <tr>
                            <th>Product</th>
                            <th width="120">Quantity</th>
                            <th width="150">Sale Price</th>
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
                    id="save-sales-order"
                    class="btn btn-success">

                    <i class="fas fa-save"></i>
                    Save Sales Order
                </button>

            </div>

        </form>

    </div>
</div>

<?php require "includes/footer.php"?>