<?php
require "includes/init.php";

Auth::requireLogin();
Auth::requireRole('USER');

$productCode = trim($_POST['productCode'] ?? '');
$productName = trim($_POST['productName'] ?? '');

$costPrice = (float)($_POST['costPrice'] ?? 0);
$sellingPrice = (float)($_POST['sellingPrice'] ?? 0);
$errors= [];
$conn = Database::getConn();
$companyId = Auth::companyId();

    if (isset($_GET['id'])) {
            $productId = intval($_GET['id']);
            
            $categories = Category::getCategories($conn, $companyId);
            // var_dump($productId);
    
            $product = Product::getById($conn, $productId, $companyId);

            if(! $product){
                    die("Product not found");
            }

          
            //var_dump($categories);
            $productCode= $product[0]['product_code'];
            $productName = $product[0]['product_name'];
            $categoryName = $product[0]['category_name'];
            $costPrice= $product[0]['cost_price'];
            $sellingPrice= $product[0]['selling_price'];

    }else{
        die('Product Id not supplied product not found');
    }
    if($_SERVER["REQUEST_METHOD"] =="POST"){
    
            $productCode= $_POST['productCode'];
            $productName =$_POST['productName'];
            $productCategory = $_POST['productCategory'];
            $costPrice= floatval($_POST['costPrice']);
            $sellingPrice= floatval($_POST['sellingPrice']);

           

            if(Product::updateProducts($conn,
                                    $productId, 
                                    $companyId,
                                     $productCode,
                                     $productName,
                                     $productCategory,
                                     $costPrice,
                                     $sellingPrice)){
                Url::redirect("/product.php");
            } else {

        $errors[] = "Unable to update product.";
    }
}
    

    



    

?>
<?php require "includes/header.php";?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Update Product</h3>
    </div>

    <div class="card-body">
        <form action="" method="POST">

            <div class="row g-4">

                <!-- Product Code -->
                <div class="col-md-6">
                    <label for="product-code" class="form-label">
                        Product Code
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="product-code"
                        name="productCode"
                        value="<?= htmlspecialchars($productCode) ?>"
                        >
                </div>

                <!-- Product Name -->
                <div class="col-md-6">
                    <label for="product-name" class="form-label">
                        Product Name
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="product-name"
                        name="productName"
                        value="<?= htmlspecialchars($productName) ?>"
                        required>
                </div>
                

                <!-- category  -->
                  <div class="col-md-6">
                     <label for="product-category" 
                    class="form-label"
                    >
                        Product Category
                    </label>
                    <select
                        class="form-select <?= isset($errors['productCategory']) ? 'is-invalid' : '' ?>"
                        id="product-category"
                        name="productCategory">

                        <option value= "<?= htmlspecialchars($product[0]['category_id'])?>"><?= $categoryName ?></option>

                        <?php foreach ($categories as $category): ?>

                         <option
                            value="<?= $category['id']; ?>">

                            <?= htmlspecialchars($category['category_name']); ?>

                        </option>

                        <?php endforeach; ?>

                    </select>
                </div>
                <!-- Cost Price -->
                <div class="col-md-6">
                    <label for="cost-price" class="form-label">
                        Cost Price
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        class="form-control"
                        id="cost-price"
                        name="costPrice"
                        value="<?= htmlspecialchars($costPrice) ?>"
                        required>
                </div>

                <!-- Selling Price -->
                <div class="col-md-6">
                    <label for="selling-price" class="form-label">
                        Selling Price
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        class="form-control"
                        id="selling-price"
                        name="sellingPrice"
                        value="<?= htmlspecialchars($sellingPrice) ?>"
                        required>
                </div>

                <!-- Buttons -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-pencil-square"></i>
                        Update Product
                    </button>

                    <a href="product.php" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>

            </div>

        </form>
    </div>
</div>

<?php require "includes/footer.php";?>