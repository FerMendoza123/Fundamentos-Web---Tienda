<?php
    session_start();
    include("funciones.php");
    sacaUsuarioN_A();

    if(isset($_GET["idC"]) && $_GET["idC"]!="" && isset($_GET["idP"]))
    {
        $conn = conectarBD();
        if($_SESSION["tipo"]=="Normal")
            $qry = "delete from comentarios where idComentario = '".$_GET["idC"]."' and idUsuario = '".$_SESSION["idUsuario"]."'";
        else
            $qry = "delete from comentarios where idComentario = '".$_GET["idC"]."'";
        mysqli_query($conn,$qry);
        if(isset($_GET["buscar"]))
            header("Location:http://localhost/tienda/producto.php?buscar=".$_GET["buscar"]."&idP=".$_GET["idP"]);
        else
            header("Location:http://localhost/tienda/producto.php?idP=".$_GET["idP"]);

    }
    else
    {
        header("Location:http://localhost/tienda/principal.php");
    }
?>