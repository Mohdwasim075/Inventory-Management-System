<?php
require "includes/init.php";
require "includes/timeout.php";


//Authenticate valid user to access the page
Auth::requireLogin();
Auth::requireRole('USER');

$conn = require 'includes/db.php';

$products = Product::getAll($conn,Auth::companyId() ,Auth::id());




?>

<?php require "includes/header.php"?>

<div class="card card-primary mt-3">
     <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-boxes mr-1"></i>
            Products
        </h3>
    </div>
    <div class="card-body">
        <?php if (isset($_SESSION['product-alert'])): ?>

            <div class="alert alert-<?= $_SESSION['product-alert']['type']; ?> alert-dismissible fade show">

                <?= htmlspecialchars($_SESSION['product-alert']['message']); ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">

                </button>

            </div>

            <?php unset($_SESSION['product-alert']); ?>

        <?php endif; ?>
    
    <?php if(empty($products)) :?>
    <p>No products found</p>
<?php else: ?>
        <div >
             <a  href= "add-product.php"
                      class=" add-button btn btn-primary"> 
                       <i class="bi bi-plus-circle"></i> Add Product
                    </a>
        
       </div>
      
    
        <table class="table   table-striped ">
        <thead>
            <tr>
                <th>S.no</th>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Cost Price</th>
                <th>Selling Price</th>
                <th></th>
                <th></th>

            </tr>
        </thead>
        <tbody>
            <?php foreach($products as $id => $product): ?>
            <tr class = "table-row">
                <td><?= $id + 1 ;?></td>
                <td><?= $product['product_code'] ?></td>
                <td><?= $product['product_name'] ?></td>
                <td><?= $product['category_name'] ?></td>
                <td><?= $product['total_quantity'] ?></td>
                <td><?= $product['cost_price'] ?></td>
                <td><?= $product['selling_price']?></td>
                <td>
                    <a href="edit-product.php?id=<?= $product['id'] ?>"
                    class="btn btn-success">
                     <i class="bi bi-pencil-square"></i> Edit
                    </a>
                </td>
                <td>
                    <a  href= "delete-product.php?id=<?= $product['id']?>"
                      class=" delete btn btn-danger"> 
                       <i class="bi bi-trash"></i> Delete
                    </a>
                </td>
                
              
            </tr>
            <?php endforeach;?>
        </tbody>    
        </table>
    
    </div>
    
</div>

<?php endif; ?>





<?php require "includes/footer.php"?>