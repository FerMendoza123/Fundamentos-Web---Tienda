<?php
    session_start();
    include("funciones.php");
    sacaUsuarioN_A();
    $conn = conectarBD();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
        relacionesEstiloBarra();
        relacionesFormulario();
        relacionesProducto();
        relacionesSeccionDL();
    ?>
</head>
<body>
<?php
    encabezado();
?>
    <div class="contenidoPagina">
        
        <section class='seccion'>
        <form style="display: flex; flex-flow:row; justify-content:center; height:40px; " method="get" action="verCompras.php">
            <p>Mostrar:</p>
            <select style="height:fit-content; margin:15px 10px" name="mostrar" id="mostrar">
                <option value="Todos">Todos</option>
                <option value="Pendientes">Pendientes</option>
                <option value="Aprobados">Aprobados</option>
                <option value="Cancelados">Cancelados</option>
            </select>
            <input style="margin: 0;" class="enviar" type="submit" value="Aplicar">
        </form>
        <?php
            $condicion="";
            if(isset($_GET["mostrar"]))
            {
                if($_GET["mostrar"]=="Aprobados")
                    $condicion = "and aprobado = 1";
                if($_GET["mostrar"]=="Cancelados")
                    $condicion = "and aprobado = 0";
                if($_GET["mostrar"]=="Pendientes")
                    $condicion = "and aprobado = -1";
            }
            if($_SESSION["tipo"]=="Administrador")
                $qry = "select c.numCompra, c.precioTotal, c.aprobado, u.usuario 
                        from compra as c inner join usuarios as u where c.idUsuario=u.idUsuario $condicion order by c.numCompra DESC";
            else
                $qry = "select numCompra, precioTotal, aprobado from compra where idUsuario=".$_SESSION["idUsuario"]." $condicion order by numCompra DESC";

            $compras = mysqli_query($conn,$qry);
            if(mysqli_num_rows($compras)>0)
            {
                while($compra = mysqli_fetch_array($compras))
                {
                    echo"
                    <div class='datosLiga'>
                        <div class='datos'>
                            <h4>No. Compra: </h4>".$compra["numCompra"];
                        if($_SESSION["tipo"]=="Administrador")
                            echo"
                            <h4>Usuario: </h4>".$compra["usuario"];
                            echo"
                            <h4>Total: </h4>$".$compra["precioTotal"];
                        if($compra["aprobado"]==-1)
                            echo"<h4>Estado: </h4> Pendiente";
                        else
                            if($compra["aprobado"]==0)
                                echo"<h4>Estado: </h4> Cancelado";
                            else
                                echo"<h4>Estado: </h4> Aprobado";
                        echo"
                        </div>
                        <div class='ligas'>";
                            echo" <a class='liga' href='detalleCompra.php?idC=".$compra["numCompra"]."'>Ver detalles</a>";
                        echo"
                        </div>
                    </div>
                    <hr>";
                }
            }
            else
            echo "<p>Aun no hay compras</p>";
        ?>
        </section>
        <a class='liga' href="principal.php">Pagina principal</a>
    </div>

    <?php
        footer();
    ?>
</body>
</html>