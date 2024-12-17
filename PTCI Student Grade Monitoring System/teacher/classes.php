<?php
session_start();
    if($_SESSION['teacherid']){
        echo '';
    } else {
        header("location:teacherlogin.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php  include 'includes/header.php'; ?>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <?php  include 'includes/topnavbar.php'; ?>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <?php  include 'includes/sidenavbar.php'; ?>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Classes</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Classes</li>
                        </ol>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                <?php  include 'includes/footer.php'; ?>
                </footer>
            </div>
        </div>
        <?php  include 'includes/script.php'; ?>
    </body>
</html>
