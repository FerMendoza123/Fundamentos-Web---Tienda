<?php
    session_start();
    session_destroy();
    unset($_SESSION["usuario"]);
    header("Location:http://localhost/tienda/principal.php");
?>