<?php
require "includes/init.php";
require "includes/timeout.php";

Auth::requireLogin();
Auth::requireRole('USER');


$conn = require "includes/db.php";

$categories = Category::getCategories($conn, $_SESSION['user']['company_id']);




?>
<?php require "includes/header.php" ?>


<div class="card card-primary mt-3">

    <div class="card-header">

        <div class="d-flex justify-content-between align-items-center">

            <h3 class="card-title mb-0">
                <i class="fas fa-tags me-2"></i>
                Categories
            </h3>

           
        </div>
       
         
        
    </div>
     <div class="mt-3">
           
             <a  href= "add-category.php"
                      class=" add-button btn btn-primary"> 
                       <i class="bi bi-plus-circle"></i> Add Category
                    </a>

        </div>
   
    <div class="card-body">
        


         

         <?php if (isset($_SESSION['category-action'])): ?>

            <div class="alert alert-<?= $_SESSION['category-action']['type']; ?> alert-dismissible fade show">

                <?= htmlspecialchars($_SESSION['category-action']['message']); ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">

                </button>

            </div>

            <?php unset($_SESSION['category-action']); ?>

        <?php endif; ?>
    

        <?php if (empty($categories)): ?>

            <div class="alert alert-info mb-0">

                No categories found.

            </div>

        <?php else: ?>

            <div class="table-responsive">

                <table class="table table-striped table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th width="70">S.No</th>
                            <th>Category Name</th>
                            <th width="100">Edit</th>
                            <th width="100">Delete</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($categories as $index => $category): ?>

                            <tr>

                                <td><?= $index + 1; ?></td>

                                <td>

                                    <?= htmlspecialchars($category['category_name']); ?>

                                </td>

                                <td>

                                    <a
                                        href="edit-category.php?id=<?= $category['id']; ?>"
                                        class="btn btn-success btn-sm">

                                        <i class="bi bi-pencil-square"></i>

                                        Edit

                                    </a>

                                </td>

                                <td>

                                    <a
                                        href="delete-category.php?id=<?= $category['id']; ?>"
                                        class="btn btn-danger btn-sm delete">

                                        <i class="bi bi-trash"></i>

                                        Delete

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

<?php require "includes/footer.php" ?>