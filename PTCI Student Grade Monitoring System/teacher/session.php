<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<body>
    <?php
        $_SESSION["teacherid"] = $_POST["teacherid"];
    ?>
    <script>
        window.location.href = "dashboard.php";
    </script>
</body>
</html>