<?php 
require "includes/init.php";

$addCategory = "";
$errors = [];
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $conn = require "includes/db.php";
    $addCategory = $_POST["addCategory"] ?? "";
    $sessionCompany = Auth::companyId();

    if($addCategory == ""){
        $errors['addCategory'] = 'Category cannot be empty';
    }

    if(empty($errors)){
        $result = Category::setCategory($conn, $sessionCompany, $addCategory);
         if($result ===  true){
            Url::redirect('/categories.php');

    }else{
        $errors['addCategory'] = $result;
    }

    }
   
}
?>
<?php require "includes/header.php" ?>
    
<div class="container mt-3">

    <div class="card card-primary ">

        <div class="card-header">
            <h3 class="card-title">Add Category</h3>
        </div>

        <div class="card-body">

            <form method="post">

                <div class="row">

                    <div class="col-md-6">

                        <label for="add-category" class="form-label">
                            Category Name
                        </label>

                        <input
                            type="text"
                           
                            name="addCategory"
                            class="form-control <?= isset($errors['addCategory']) ? 'is-invalid' : ''; ?>"
                            value="<?= htmlspecialchars($addCategory); ?>">

                        <?php if (isset($errors['addCategory'])): ?>
                            <div class="invalid-feedback">
                                <?= $errors['addCategory']; ?>
                            </div>
                        <?php endif; ?>

                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-md-6">

                        <button
                            type="submit"
                            class="btn btn-primary">

                            Save Category

                        </button>

                        <a
                            href="categories.php"
                            class="btn btn-secondary ms-2">

                            Cancel

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>
                


<?php require "includes/footer.php" ?>
