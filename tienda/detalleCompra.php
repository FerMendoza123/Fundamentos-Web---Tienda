<?php
    session_start();
    include("funciones.php");
    sacaUsuarioN_A();
    $conn = conectarBD();
    if(isset($_GET["idCA"]))
    {
        $qry = "update compra set aprobado = 1 where numCompra = ".$_GET["idCA"];
        mysqli_query($conn,$qry);
    }
    if(isset($_GET["idCC"]))
    {
        $qry = "update compra set aprobado = 0 where numCompra = ".$_GET["idCC"];
        mysqli_query($conn,$qry);
    }
    if(isset($_GET["idCE"]))
    {
        $qry = "delete from compra where numCompra = ".$_GET["idCE"];
        $qry2 = "delete from compradeproducto where numCompra = ".$_GET["idCE"]; 
        mysqli_query($conn,$qry2);
        mysqli_query($conn,$qry);
    }
    if(!isset($_GET["idC"]))
        header("Location:http://localhost/tienda/verCompras.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
        relacionesEstiloBarra();
        relacionesSeccionDL();
        relacionesFormulario();
    ?>
    <style>
        .formC{
            display: none;
        }
        .datosLiga
        {
            flex-flow: row;
            flex-wrap: wrap;
            width: 100%;
            justify-content:space-between;
        }
        .datosLiga img
        {
            width: 200px;
        }
        .datosLiga .datos{
            width: 200px;
        }

        .datosLiga .liga{
            float: left;
            margin: 6px;
        }

        .ligas{
            display: flex;
            flex-flow: column;
        }
        #total{
            display: flex;
            flex-flow: row;
            justify-content: space-between;
            margin: 20px;
        }

        #comprar{
            text-decoration: none;
            float: right;
            margin: 20px;
        }
        .check{
            margin-right: 20px;
        }
        #enlaces{
            margin: 20px;
            display: flex;
            flex-flow: row;
            justify-content: flex-end;
        }
        #enlaces .enviar{
            margin: 0px;
        }
    </style>
</head>
<body>
    <?php
        encabezado();
    ?>
    <div class="contenidoPagina">
        <a class='liga' href="verCompras.php">Regresar</a>
        <section class='seccion'>
        <?php
            if($_SESSION["tipo"]=="Administrador")
                $qryC = "select u.usuario, u.telefono, c.idDireccion, c.precioTotal, c.aprobado 
                        from compra as c inner join usuarios as u where c.idUsuario=u.idUsuario and numCompra=".$_GET["idC"]."";
            else
                $qryC = "select idDireccion, precioTotal, aprobado from compra where numCompra=".$_GET["idC"]."";
            $res = mysqli_query($conn,$qryC);
            $compra = mysqli_fetch_array($res);
            
    echo"<div class='datosLiga'>
            <div class='datos'>";
            echo"<h4>No. Compra: </h4> ".$_GET["idC"]."";
            if($_SESSION["tipo"]=="Administrador")
            {
                echo "<h4>Usuario: </h4> ".$compra["usuario"];
                echo "<h4>Telefono: </h4> ".$compra["telefono"];
            }
            if($compra["aprobado"]==-1)
                echo"<h4>Estado: </h4> Pendiente";
            else
                if($compra["aprobado"]==0)
                    echo"<h4>Estado: </h4> Cancelado";
                else
                    echo"<h4>Estado: </h4> Aprobado";
        echo"</div>
        </div>
        <hr>";
            $qryDir = "select * from direcciones where idDireccion=".$compra["idDireccion"];
            $dirRes = mysqli_query($conn,$qryDir);
            $dir = mysqli_fetch_array($dirRes);
            echo "<div class='datosLiga'>
                    <div class='datos'>
                        <h3>Dirección</h3>
                        <h4>".$dir["nombre"]."</h4> 
                        <p>Colonia: ".$dir["colonia"]."</p>
                        <p>CP: ".$dir["codigoPostal"]."</p>
                        <p>Calle: ".$dir["calle"]."</p>
                        <p>Numero: ".$dir["numero"]."</p>
                        <p>Tel: ".$dir["telefono"]."</p>
                    </div>
                </div><hr>";
    
            if(mysqli_num_rows($res)>0)
            {
                $qryA = "select p.nombre, c.idProducto, c.cantidad, c.precio, c.descuento, c.ingrediente, p.nombre
                        from productos as p inner join compradeproducto as c where c.idProducto=p.idProducto and numCompra='".$_GET["idC"]."'";
                $articulos = mysqli_query($conn,$qryA);
                while($articulo = mysqli_fetch_array($articulos))
                {
                    $totalP=0;
                    echo"
                    <div class='datosLiga'>
                        <img src='imagenProducto.php?idIp=".$articulo["idProducto"]."'>
                        <div class='datos'>
                            <h3>".$articulo["nombre"]."</h3>
                            <h4>Ingretiente: </h4>".$articulo["ingrediente"]." 
                            <h4>Precio: </h4>$".$articulo["precio"];
                        if($articulo["descuento"]>0)
                        {
                            $precio = $articulo["precio"]*(100-$articulo["descuento"])/100;
                            echo"<h4>Descuento: </h4>".$articulo["descuento"]."%
                            <h4>Precio con descuento: </h4>$".$precio;
                            $totalP=$precio*$articulo["cantidad"];
                        }
                        else
                        {
                            $totalP=$articulo["precio"]*$articulo["cantidad"];
                        }
                        echo"
                            <h4>Cantidad: </h4>".$articulo["cantidad"]."
                            <h4>Precio final:</h4> $$totalP
                        </div>
                    </div>
                    <hr>";
                }
                
            }

        echo"
                <div id='total'>
                    <h4>Total: </h4>
                    <h4>$".$compra["precioTotal"]."</h4>
                </div>";
    ?>
        <div id="enlaces">
        <?php
        if($_SESSION["tipo"]=="Administrador")
        {
        if($compra["aprobado"]==-1 || $compra["aprobado"]==0)
        echo"<a class='enviar' href='detalleCompra.php?idCA=".$_GET['idC']."'>Aprobar</a>";
        if($compra["aprobado"]==-1 || $compra["aprobado"]==1)
        echo"<a class='enviar' href='detalleCompra.php?idCC=".$_GET['idC']."'>Cancelar</a>";
        echo"<a class='enviar' href='detalleCompra.php?idCE=".$_GET['idC']."'>Eliminar</a>";
        }
        ?>
        </div>
        </section>
        
        <a class='liga' href="principal.php">Pagina principal</a>
    </div>
    
    <?php
        footer();
    ?>
</body>
</html>