<?php
include("funciones.php");

//Verificar que existe el idI
if(isset($_GET['idIp']) && $_GET['idIp']!="")
{
    $conn = conectarBD();
    $qry = "select tipo, imagen from productos where idProducto=" . $_GET['idIp'];
    $rs = mysqli_query($conn,$qry);
    $imagen = mysqli_fetch_array($rs);
    header("Content-type:" . $imagen["tipo"]);
    echo $imagen["imagen"];
}
?>