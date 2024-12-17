<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>
    <?php
        session_unset();
        session_destroy();
    ?>
    <script>
        window.location.href= "index.php"
    </script>
</body>
</html>