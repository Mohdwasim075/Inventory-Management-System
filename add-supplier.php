<?php 
require "includes/init.php";


$errors = [];
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $conn = require "includes/db.php";
    $supplierNew = $_POST["supplierNew"] ?? "";
    $sessionCompany = Auth::companyId();

    if($supplierNew == ""){
        $errors['supplierNew'] = 'Supplier name cannot be empty';
    }

    if(empty($errors)){
        $result = Supplier::createSupplier($conn, $sessionCompany, $supplierNew);
         if($result ===  true){
            Url::redirect('/supplier.php');

    }else{
        $errors['supplierNew'] = $result;
    }

    }
   
}
?>
<?php require "includes/header.php" ?>
    
<div class="container mt-3">

    <div class="card card-primary ">

        <div class="card-header">
            <h3 class="card-title">Add Supplier</h3>
        </div>

        <div class="card-body">

            <form method="post">

                <div class="row">

                    <div class="col-md-6">

                        <label for="add-category" class="form-label">
                            Supplier Name
                        </label>

                        <input
                            type="text"
                           
                            name="supplierNew"
                            class="form-control <?= isset($errors['supplierNew']) ? 'is-invalid' : ''; ?>"
                            >

                        <?php if (isset($errors['supplierNew'])): ?>
                            <div class="invalid-feedback">
                                <?= $errors['supplierNew']; ?>
                            </div>
                        <?php endif; ?>

                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-md-6">

                        <button
                            type="submit"
                            class="btn btn-primary">

                            Add Supplier

                        </button>

                        <a
                            href="supplier.php"
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
