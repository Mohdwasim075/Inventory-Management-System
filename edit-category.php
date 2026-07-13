<?php

require "includes/init.php";

$CategoryName = '';
if(isset($_GET['id'])){
    $conn = Database::getConn();

    $companyId = Auth::companyId(); 
    $categoryId = intval($_GET['id']);
    $category = Category::getCategoryById($conn, $companyId, $categoryId);
    if(! $category){
        die("category not found");

    }
    $categoryName = $category[0]['category_name'];


}else{
    die('Category Id not Supplied');
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $newCategory = $_POST['newCategory'];
    if($newCategory == ""){
        $errors['newCategory'] = 'Category cannot be empty';
    }

    if(empty($errors)){
        $result = Category::updateCategory($conn, $companyId, $categoryId, $newCategory);
        if($result === true){
            Url::redirect('/categories.php');
        }else{
        $errors['newCategory'] = $result;
    }
}
}

?>

<?php require "includes/header.php"?>
<div class="container mt-3">

    <div class="card card-primary ">

        <div class="card-header">
            <h3 class="card-title">Edit Category</h3>
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
                           
                            name="newCategory"
                            class="form-control <?= isset($errors['newCategory']) ? 'is-invalid' : ''; ?>"
                            value="<?= htmlspecialchars($categoryName); ?>">

                        <?php if (isset($errors['newCategory'])): ?>
                            <div class="invalid-feedback">
                                <?= $errors['newCategory']; ?>
                            </div>
                        <?php endif; ?>

                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-md-6">

                        <button
                            type="submit"
                            class="btn btn-primary">

                            Update Category

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



<?php require "includes/footer.php";?>