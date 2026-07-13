<?php 
require "includes/init.php";
require "includes/timeout.php";

Auth::requireLogin();
Auth::requireRole('USER');


$productCode = "";
$productName = "";
$categoryId = "";
$costPrice = "";
$sellingPrice = "";

$errors = [];

$conn = require "includes/db.php";

$companyId = Auth::companyId();

$categories = Category::getCategories($conn, $companyId);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Read Form Values
    $productCode = trim($_POST["productCode"] ?? "");
    $productName = trim($_POST["productName"] ?? "");
    $categoryId = isset($_POST["productCategory"])
        ? (int)$_POST["productCategory"]
        : 0;

    $costPrice = trim($_POST["costPrice"] ?? "");
    $sellingPrice = trim($_POST["sellingPrice"] ?? "");

    // Product Code Validation
    if ($productCode === "") {

        $errors['productCode'] = "Product Code is required.";

    } elseif (strlen($productCode) > 50) {

        $errors['productCode'] = "Product Code cannot exceed 50 characters.";

    }

    // Product Name Validation
    if ($productName === "") {

        $errors['productName'] = "Product Name is required.";

    }

    // Category Validation
    if ($categoryId <= 0) {

        $errors['productCategory'] = "Please select a category.";

    }

    // Cost Price Validation
    if ($costPrice === "") {

        $errors['costPrice'] = "Cost Price is required.";

    } elseif (!is_numeric($costPrice)) {

        $errors['costPrice'] = "Cost Price must be numeric.";

    } elseif ((float)$costPrice < 0) {

        $errors['costPrice'] = "Cost Price cannot be negative.";

    } else {

        $costPrice = (float)$costPrice;

    }

    // Selling Price Validation
    if ($sellingPrice === "") {

        $errors['sellingPrice'] = "Selling Price is required.";

    } elseif (!is_numeric($sellingPrice)) {

        $errors['sellingPrice'] = "Selling Price must be numeric.";

    } elseif ((float)$sellingPrice <= 0) {

        $errors['sellingPrice'] = "Selling Price must be greater than zero.";

    } else {

        $sellingPrice = (float)$sellingPrice;

    }

    /*
    // Optional
    if (Product::codeExists($conn, $companyId, $productCode)) {
        $errors['productCode'] = "Product Code already exists.";
    }
    */

    if (empty($errors)) {

        $success = Product::addProducts(
            $conn,
            $companyId,
            $categoryId,
            $productCode,
            $productName,
            $costPrice,
            $sellingPrice
        );

        if ($success) {

            Url::redirect("/product.php");
            exit;

        } else {

            $errors['database'] = "Unable to add product.";

        }
    }
}

?>
<?php require "includes/header.php" ?>
<div class="card card-primary mt-3">

    <div class="card-header">
        <h3 class="card-title ">Add Product</h3>
    </div>

    <div class="card-body">
        <?php if (isset($errors['database'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($errors['database']) ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="row">

                <!-- Product Code -->
                <div class="col-md-6 mb-3">
                    <label for="product-code" class="form-label">
                        Product Code
                    </label>

                    <input
                        type="text"
                        class="form-control <?= isset($errors['productCode']) ? 'is-invalid' : '' ?>"
                        id="product-code"
                        name="productCode"
                        value="<?= htmlspecialchars($productCode) ?>">

                        <?php if (isset($errors['productCode'])): ?>
                            <div class="invalid-feedback">
                                <?= $errors['productCode'] ?>
                            </div>
                        <?php endif; ?>
                </div>

                <!-- Product Name -->
                <div class="col-md-6 mb-3">
                    <label for="product-name" class="form-label">
                        Product Name
                    </label>

                    <input
                        type="text"
                          class="form-control <?= isset($errors['productName']) ? 'is-invalid' : '' ?>"
                        id="product-name"
                        name="productName"
                        value="<?= htmlspecialchars($productName) ?>">

                        <?php if (isset($errors['productName'])): ?>
                            <div class="invalid-feedback">
                                <?= $errors['productName'] ?>
                            </div>
                        <?php endif; ?>
                </div>

                <!-- Category -->
                <div class="col-md">
                    <label for="product-category" 
                    class="form-label"
                    >
                        Product Category
                    </label>

                    <select
                        class="form-select <?= isset($errors['productCategory']) ? 'is-invalid' : '' ?>"
                        id="product-category"
                        name="productCategory">

                        <option value="">Choose Category</option>

                        <?php foreach ($categories as $category): ?>

                         <option
                            value="<?= $category['id']; ?>"
                            <?= ($categoryId == $category['id']) ? 'selected' : ''; ?>>

                            <?= htmlspecialchars($category['category_name']); ?>

                        </option>

                        <?php endforeach; ?>

                    </select>
                    <?php if (isset($errors['productCategory'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['productCategory'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Cost Price -->
                <div class="col-md-6 mb-3">
                    <label for="cost-price" class="form-label">
                        Cost Price
                    </label>

                    <input
                        type="number"
                        step="100"
                        class="form-control <?= isset($errors['costPrice']) ? 'is-invalid' : '' ?>"
                        id="cost-price"
                        name="costPrice"
                        value="<?= htmlspecialchars($costPrice) ?>">
                    <?php if (isset($errors['costPrice'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['costPrice'] ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Selling Price -->
                <div class="col-md-6 mb-3">
                    <label for="selling-price" class="form-label">
                        Selling Price
                    </label>

                    <input
                        type="number"
                        step="100"
                        class="form-control  <?= isset($errors['sellingPrice']) ? 'is-invalid' : '' ?>"
                        id="selling-price"
                        name="sellingPrice"
                        value="<?= htmlspecialchars($sellingPrice) ?>">
                        <?php if (isset($errors['sellingPrice'])): ?>
                            <div class="invalid-feedback">
                                <?= $errors['sellingPrice'] ?>
                            </div>
                        <?php endif; ?>
                </div>

            </div>

            <hr>

            <button
                type="submit"
                class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>
                Add Product

            </button>

            <a
                href="product.php"
                class="btn btn-secondary">

                Cancel

            </a>

        </form>

    </div>

</div>

<?php require "includes/footer.php"?>