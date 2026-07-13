<?php

require "includes/init.php";

if(isset($_GET['id'])){
    $conn = Database::getConn();

    $companyId = Auth::companyId(); 
    $supplierId = intval($_GET['id']);
    $supplier = Supplier::getSupplierById($conn, $companyId, $supplierId);

    $supplierName = $supplier[0]['supplier_name'];


}
$errors = [];
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $supplierName = $_POST['supplierName'];
    if($supplierName == ""){
        $errors['supplierName'] = 'Supplier Name cannot be empty';
    }

    if(empty($errors)){
        $result = Supplier::updateSupplier($conn, $companyId, $supplierId, $supplierName);
        if($result === true){
            Url::redirect('/supplier.php');
        }else{
        $errors['supplierName'] = $result;
    }
}
}

?>

<?php require "includes/header.php"?>
<div class="container mt-3">

    <div class="card card-primary ">

        <div class="card-header">
            <h3 class="card-title">Edit Supplier</h3>
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
                           
                            name="supplierName"
                            class="form-control <?= isset($errors['supplierName']) ? 'is-invalid' : ''; ?>"
                            value="<?= htmlspecialchars($supplierName); ?>">

                        <?php if (isset($errors['supplierName'])): ?>
                            <div class="invalid-feedback">
                                <?= $errors['supplierName']; ?>
                            </div>
                        <?php endif; ?>

                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-md-6">

                        <button
                            type="submit"
                            class="btn btn-primary">

                            Update supplier

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



<?php require "includes/footer.php";?>