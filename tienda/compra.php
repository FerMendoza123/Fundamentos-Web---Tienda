<?php
    session_start();
    include("funciones.php");
    sacaUsuarioN_A();
    if(!isset($_GET["idP"]) && !isset($_GET["idC"]))
        sacaUsuario();
    $conn = conectarBD();
    if(isset($_GET["idD"]))
    {
        $qry = "insert into compra (idUsuario, idDireccion, precioTotal, aprobado)
                values ('".$_SESSION["idUsuario"]."','".$_GET["idD"]."','".$_GET["total"]."','-1')";
        mysqli_query($conn,$qry);
        $qry = "select last_insert_id() as idC";
        $res = mysqli_query($conn,$qry);
        $id = mysqli_fetch_array($res);
        $idC = $id["idC"];
        if(isset($_GET["idC"]))
        {
            $qryC = "select c.idProducto, c.ingrediente, c.cantidad, p.precio from carrito as c inner join productos as p where c.idProducto=p.idProducto and c.idUsuario='".$_SESSION["idUsuario"]."'";
            $carrito = mysqli_query($conn,$qryC);
            if(mysqli_num_rows($carrito)>0)
            {
                echo "asdasdsad";
                $total=0;
                while($articulo = mysqli_fetch_array($carrito))
                {
                    $qryA = "select descuento from promociones where idProducto='".$articulo["idProducto"]."'";
                    $descuento = mysqli_query($conn, $qryA);
                    if(mysqli_num_rows($descuento)>0)
                    {
                        $descuento = mysqli_fetch_array($descuento);
                        $des=$descuento["descuento"];
                    }
                    else
                    {
                        $des="0";
                    }
                
                    $qry = "insert into compradeproducto (idProducto, numCompra, cantidad, descuento, precio, ingrediente)
                            values ('".$articulo["idProducto"]."','$idC','".$articulo["cantidad"]."','$des','".$articulo["precio"]."','".$articulo["ingrediente"]."')";
                    mysqli_query($conn,$qry);
                }
                $qry = "delete from carrito where idUsuario = '".$_SESSION["idUsuario"]."'";
                mysqli_query($conn,$qry);
            }
        }
        else
        {
            $qryC = "select precio from productos where idProducto='".$_GET["idP"]."'";
            $producto = mysqli_query($conn,$qryC);
            if(mysqli_num_rows($producto)>0)
            {
                $articulo = mysqli_fetch_array($producto);
                $qryA = "select descuento from promociones where idProducto='".$_GET["idP"]."'";
                $descuento = mysqli_query($conn, $qryA);
                if(mysqli_num_rows($descuento)>0)
                {
                    $descuento = mysqli_fetch_array($descuento);
                    $des=$descuento["descuento"];
                }
                else
                {
                    $des="0";
                }
                $qry = "insert into compradeproducto (idProducto, numCompra, cantidad, descuento, precio, ingrediente)
                            values ('".$_GET["idP"]."','$idC','".$_GET["cantidad"]."','$des','".$articulo["precio"]."','".$_GET["ingrediente"]."')";
                mysqli_query($conn,$qry);
            }
        }
        header("Location:http://localhost/tienda/principal.php");
    }
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
            justify-content:space-around;
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
    </style>

    <script>
        function cambiaDireccion(idD)
        {
            if(document.getElementById("idD").value==idD)
                document.getElementById("idD").value="";
            else
            {
                //Se asigna el valor al input que se enviará en el form
                document.getElementById("idD").value=idD;
                //Se deselecciona los demas cheks
                var checks = document.getElementsByClassName("check");
                for(i=0;i<checks.length;i++)
                {
                    if(checks[i].id!="dir"+idD)
                    {
                        checks[i].checked=false;
                    }
                }
            }
        }

        function compra()
        {
            nombreDiv = "aviso";
            var div= document.getElementById("aviso");
            if(document.getElementById("idD").value=="")
            {
                div.style.display = "flex";
                div.style.top = "50%";
            }
            else
            {
                document.getElementById("formCompra").submit();
            }
        }
    </script>
</head>
<body>
    <?php
        encabezado();
    ?>
    <div class="contenidoPagina">
        <section class='seccion'>
        <?php
        if(isset($_GET["idP"]))
        {
            $qryC = "select nombre, precio from productos where idProducto='".$_GET["idP"]."'";
            $producto = mysqli_query($conn,$qryC);
            if(mysqli_num_rows($producto)>0)
            {
                $articulo = mysqli_fetch_array($producto);
                $qryA = "select descuento from promociones where idProducto='".$_GET["idP"]."'";
                $descuento = mysqli_query($conn, $qryA);
                echo"
                <div class='datosLiga'>
                    <img src='imagenProducto.php?idIp=".$_GET["idP"]."'>
                    <div class='datos'>
                        <h3>".$articulo["nombre"]."</h3>
                        <h4>Ingretiente: </h4>".$_GET["ingrediente"]."
                        <h4>Precio: </h4>$".$articulo["precio"];
                    if(mysqli_num_rows($descuento)>0)
                    {
                        $descuento = mysqli_fetch_array($descuento);
                        $precio = $articulo["precio"]*(100-$descuento["descuento"])/100;
                        echo"<h4>Descuento: </h4>".$descuento["descuento"]."%
                        <h4>Precio con descuento: </h4>$".$precio;
                        $total=$precio*$_GET["cantidad"];
                    }
                    else
                    {
                        $total=$articulo["precio"]*$_GET["cantidad"];
                    }
                    echo"
                        <h4>Cantidad: </h4>".$_GET["cantidad"]."
                        <h4>Precio final: $</h4>$total
                    </div>

                </div>
                <hr>";
            }
        }
        else
        {
            $qryC = "select c.idArticulo, c.idProducto, c.ingrediente, c.cantidad,
            p.nombre, p.precio from carrito as c inner join productos as p where c.idProducto=p.idProducto and c.idUsuario='".$_SESSION["idUsuario"]."'";
            $carrito = mysqli_query($conn,$qryC);
            if(mysqli_num_rows($carrito)>0)
            {
                $total=0;
                while($articulo = mysqli_fetch_array($carrito))
                {
                    $qryA = "select descuento from promociones where idProducto='".$articulo["idProducto"]."'";
                    $descuento = mysqli_query($conn, $qryA);
                    $totalP=0;
                    echo"
                    <div class='datosLiga'>
                        <img src='imagenProducto.php?idIp=".$articulo["idProducto"]."'>
                        <div class='datos'>
                            <h3>".$articulo["nombre"]."</h3>
                            <h4>Ingretiente: </h4>".$articulo["ingrediente"]." 
                            <h4>Precio: </h4>$".$articulo["precio"];
                        if(mysqli_num_rows($descuento)>0)
                        {
                            $descuento = mysqli_fetch_array($descuento);
                            $precio = $articulo["precio"]*(100-$descuento["descuento"])/100;
                            echo"<h4>Descuento: </h4>".$descuento["descuento"]."%
                            <h4>Precio con descuento: </h4>$".$precio;
                            $totalP=$precio*$articulo["cantidad"];
                        }
                        else
                        {
                            $totalP=$articulo["precio"]*$articulo["cantidad"];
                        }
                        $total += $totalP;
                        echo"
                            <h4>Cantidad: </h4>".$articulo["cantidad"]."
                            <h4>Precio final:</h4> $$totalP
                        </div>
        
                    </div>
                    <hr>";
                }
                
            }
        }
        echo"
                <div id='total'>
                    <h4>Total: </h4>
                    <h4>$$total</h4>
                </div>";
        ?>
        </section>

        <section class='seccion'>
            <h4>Selecciona una dirección</h4>
            <?php
                $qry = "select idDireccion, nombre, numero, calle, codigoPostal, colonia, telefono from direcciones where idUsuario = '".$_SESSION["idUsuario"]."'";
                $rs = mysqli_query($conn,$qry);
                if(mysqli_num_rows($rs)>0)
                {
                    while($reg = mysqli_fetch_array($rs))
                    {
                        echo "<div class='datosLiga'>
                                <div class='datos'>
                                    <h4>".$reg["nombre"]."</h4> 
                                    <p>Colonia: ".$reg["colonia"]."</p>
                                    <p>CP: ".$reg["codigoPostal"]."</p>
                                    <p>Calle: ".$reg["calle"]."</p>
                                    <p>Numero: ".$reg["numero"]."</p>
                                    <p>Tel: ".$reg["telefono"]."</p>
                                </div>
                                <input class='check' type='checkbox' id='dir".$reg["idDireccion"]."' onclick=\"cambiaDireccion('".$reg["idDireccion"]."')\">
                            </div><hr>";
                    }
                    echo"<a id='comprar' class='enviar' href='javascript:compra()'>Comprar</a> ";
                }
                else
                {
                    echo"<p>No tienes direcciones, para poder realizar la compra <a class='liga' href='agregarDireccion.php'>agrega una</a> </p>";
                }
            ?>
        </section>
        
        <form id='formCompra' action='compra.php' method='get'>
            <input id='idD' type='hidden' name='idD' value=''>
            <input id='total' type='hidden' name='total' value=<?php echo"'$total'";?>>
        <?php
        if(isset($_GET["idP"]))
        {
            echo"
            <input type='hidden' name='idP' value='".$_GET["idP"]."'>
            <input type='hidden' name='ingrediente' value='".$_GET["ingrediente"]."'>
            <input type='hidden' name='cantidad' value='".$_GET["cantidad"]."'>";
        }
        else
            echo"
            <input type='hidden' name='idC' value='".$_GET["idC"]."'>";
        ?>
        </form>
        <a class='liga' href="principal.php">Pagina principal</a>
    </div>
    
    <div id="aviso" class="confirmacion">
        <p>Necesitas seleccionar una dirección para continuar</p>
        <div>
            <input class="enviar" type="button" value="Aceptar" onclick="confNo()">
        </div>
    </div>

    <?php
        footer();
    ?>
</body>
</html>