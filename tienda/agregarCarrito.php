<?php
    session_start();
    include("funciones.php");
    sacaUsuarioN_A();

    
    if(isset($_GET["idP"]) && $_GET["idP"]!=""
            && isset($_GET["cantidad"]))
    {
        $conn = conectarBD();
        if(!isset($_GET["ingrediente"]))
            $qry = "insert into carrito (idUsuario,idProducto,cantidad)
                values ('".$_SESSION["idUsuario"]."','".$_GET["idP"]."','".$_GET["cantidad"]."')";
        else
            $qry = "insert into carrito (idUsuario,idProducto,cantidad,ingrediente)
                values ('".$_SESSION["idUsuario"]."','".$_GET["idP"]."','".$_GET["cantidad"]."','".$_GET["ingrediente"]."')";
        mysqli_query($conn,$qry);

        header("Location:".$_SERVER["HTTP_REFERER"]);
        /*if(isset($_GET["buscar"]))
            header("Location:http://localhost/tienda/producto.php?buscar=".$_GET["buscar"]."&idP=".$_GET["idP"]);
        else
            header("Location:http://localhost/tienda/producto.php?idP=".$_GET["idP"]);*/

    }
    else
    {
        //header("Location:http://localhost/tienda/principal.php");
    }
?>