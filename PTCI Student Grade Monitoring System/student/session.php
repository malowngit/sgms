<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<body>
    <?php
        $_SESSION["studentid"] = $_POST["studentid"];
    ?>
    <script>
        window.location.href = "dashboard.php";
    </script>
</body>
</html>