<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/css/adminlte.min.css" />
    <link href="../../styles/styles.css" rel='stylesheet'/>
</head>
<body class=".sidebar-expand-* .sidebar-mini .sidebar-collapse">
    

<div class="app-wrapper ">
      <aside class="app-sidebar  bg-body-secondary shadow" data-bs-theme="dark">
      <div class="sidebar-brand">
        <a href="/admin/index.php" class="brand-link">
          <span class="brand-text fw-light">Admin Board</span>
        </a>
      
      </div>
      <div class="sidebar-wrapper">
        <nav class="mt-2">
          <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
            <li class="nav-item">
                <a href="#" class="nav-link">
                     <i class="bi bi-buildings"></i>
                    <p>
                        Manage Companies
                        <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">

                    <li class="nav-item">
                        <a href="list-company.php" class="nav-link">
                            <i class="nav-icon bi-building-fill-up"></i>
                            <p>List Companies</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="add-company.php" class="nav-link">
                            <i class="nav-icon bi-building-add "></i>
                            <p>Add Company</p>
                        </a>
                    </li>


                </ul>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon bi bi-people"></i>
                    <p>
                        Manage Users
                        <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview">

                    <li class="nav-item">
                        <a href="./users.php" class="nav-link">
                            <i class="nav-icon bi-person-lines-fill "></i>
                            <p>List Users</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="add-user.php" class="nav-link">
                            <i class="nav-icon bi-person-add "></i>
                            <p>Add Users</p>
                        </a>
                    </li>


                </ul>
            
                <li class="nav-item">
                        <a href="../../logout.php" class="nav-link">
                            <i class="nav-icon bi bi-box-arrow-right"></i>
                            <p>Logout</p>
                        </a>
                    </li>
            </li>

            </ul>
        </nav>
        </div>
        </aside>
<main class="app-main">
    <div class="app-content-header">