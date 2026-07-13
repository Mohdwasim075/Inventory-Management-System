
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
   
    <!-- AdminLTE 4 + Bootstrap 5.3 (CSS) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/css/adminlte.min.css" />
    <link href="../styles/styles.css" rel='stylesheet'/>
    <title>Document</title>
</head>
<body class="sidebar-mini sidebar-expand-lg">

<div class="app-wrapper">

    <!-- Sidebar -->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">

        <!-- Brand -->
        <div class="sidebar-brand">
            <a href="/index.php" class="brand-link">
                <span class="brand-text fw-light">
                    <strong><?= "Hi, " . Auth::name() ?></strong>
                </span>
            </a>
        </div>

        <!-- Sidebar -->
        <div class="sidebar-wrapper">
            <nav class="mt-2">

                <ul class="nav sidebar-menu flex-column"
                    data-lte-toggle="treeview"
                    data-accordion="false"
                    role="menu">

                    <!-- Home -->
                    <li class="nav-item">
                        <a href="/index.php" class="nav-link">
                            <i class="nav-icon bi bi-house-door-fill"></i>
                            <p>Home</p>
                        </a>
                    </li>

                    <!-- Products -->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-box"></i>
                            <p>
                                Products
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="product.php" class="nav-link">
                                    <i class="nav-icon bi bi-list-nested"></i>
                                    <p>List Products</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="categories.php" class="nav-link">
                                    <i class="nav-icon bi bi-grid"></i>
                                    <p>Categories</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Purchase -->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-cart-check"></i>
                            <p>
                                Purchase
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="supplier.php" class="nav-link">
                                    <i class="nav-icon bi bi-person-lines-fill"></i>
                                    <p>Suppliers</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="purchase.php" class="nav-link">
                                    <i class="nav-icon bi bi-file-earmark-text"></i>
                                    <p>Purchase Orders</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="purchase-item.php" class="nav-link">
                                    <i class="nav-icon bi bi-box-seam"></i>
                                    <p>Purchase Items</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <!-- POS -->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-shop"></i>
                            <p>
                                POS
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="customers.php" class="nav-link">
                                    <i class="nav-icon bi bi-people-fill"></i>
                                    <p>Customers</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="sales-order.php" class="nav-link">
                                    <i class="nav-icon bi bi-receipt"></i>
                                    <p>Sales Orders</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="sales-item.php" class="nav-link">
                                    <i class="nav-icon bi bi-bag-fill"></i>
                                    <p>Sales Items</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <!-- Logout -->
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link">
                            <i class="nav-icon bi bi-box-arrow-right"></i>
                            <p>Logout</p>
                        </a>
                    </li>

                </ul>

            </nav>
        </div>

    </aside>
    <!-- Header -->
<nav class="app-header navbar navbar-expand bg-body" data-bs-theme="dark" >

    <!-- Left navbar -->
    <div class="container-fluid">

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>

            <li class="nav-item d-none d-md-block">
                <span class="nav-link fw-semibold">
                    Inventory Management System
                </span>
            </li>
        </ul>

        <!-- Right navbar -->
        <ul class="navbar-nav ms-auto">

            <!-- User Dropdown -->
            <li class="nav-item dropdown user-menu">

                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">

                    <i class="bi bi-person-circle fs-5"></i>

                    <span class="d-none d-md-inline ms-2">
                        <?= Auth::name(); ?>
                    </span>

                </a>

                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">

                    <!-- User Header -->
                    <li class="dropdown-header text-center">

                        <i class="bi bi-person-circle display-4"></i>

                        <h6 class="mt-2 mb-0">
                            <?= Auth::name(); ?>
                        </h6>

                        <small>Administrator</small>

                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <!-- Profile -->
                    <li>
                        <a href="profile.php" class="dropdown-item">
                            <i class="bi bi-person me-2"></i>
                            My Profile
                        </a>
                    </li>

                    

                    <!-- Change Password -->
                    <li>
                        <a href="change-password.php" class="dropdown-item">
                            <i class="bi bi-key me-2"></i>
                            Change Password
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <!-- Logout -->
                    <li>
                        <a href="logout.php" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Logout
                        </a>
                    </li>

                </ul>

            </li>

        </ul>

    </div>

</nav>

    <!-- Main Content -->
    <main class="app-main">

        <div class="app-content">
    
    
    