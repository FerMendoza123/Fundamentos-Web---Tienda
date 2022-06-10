<?php
    session_start();
    include("funciones.php");

    $conn = conectarBD();

    if(isset($_GET["idPD"]) && isset($_GET["porcentaje"])
        && $_GET["idPD"]!="" && $_GET["porcentaje"]!="")
    {
        $qry = "insert into promociones (idProducto, descuento,slider)
                values ('".$_GET["idPD"]."','".$_GET["porcentaje"]."',false)";
        mysqli_query($conn,$qry);
        header("Location:".$_SERVER["HTTP_REFERER"]);
    }

    if(isset($_POST["idPE"]))
    {
        mysqli_query($conn,"set foreign_key_checks=0");
        $qry = "delete from compradeproducto where idProducto='".$_POST["idPE"]."'";
        mysqli_query($conn,$qry);
        $qry = "delete from productos where idProducto='".$_POST["idPE"]."'";
        mysqli_query($conn,$qry);
        echo mysqli_errno($conn);
        echo $_POST["idPE"];
        $qry = "delete from carrito where idProducto='".$_POST["idPE"]."'";
        mysqli_query($conn,$qry);
        $qry = "delete from comentarios where idProducto='".$_POST["idPE"]."'";
        mysqli_query($conn,$qry);
        $qry = "delete from etiquetaproducto where idProducto='".$_POST["idPE"]."'";
        mysqli_query($conn,$qry);
        $qry = "delete from ingredientes where idProducto='".$_POST["idPE"]."'";
        mysqli_query($conn,$qry);
        $qry = "delete from promociones where idProducto='".$_POST["idPE"]."'";
        mysqli_query($conn,$qry);
        $qry = "delete from tipoproducto where idProducto='".$_POST["idPE"]."'";
        mysqli_query($conn,$qry);
    }


    if(!isset($_REQUEST["idP"]) || $_REQUEST["idP"]=="")
        sacaUsuario();
    
    if(isset($_GET["idCE"]))
    {
        $qry = "update comentarios set contenido='".$_GET["contenido"]."' where idComentario='".$_GET["idCE"]."'";
        mysqli_query($conn,$qry);
        header("Location:".$_SERVER["HTTP_REFERER"]);
    }
    
    if(isset($_POST["comentario"]))
    {
        $tiempo = date("Y-m-d H:i:s");
        $qry = "insert into comentarios (idUsuario, idProducto, fecha, contenido) 
                values('".$_SESSION["idUsuario"]."','".$_REQUEST["idP"]."','$tiempo','".$_POST["comentario"]."')";
        mysqli_query($conn,$qry);    
        unset($_POST["comentario"]);
    }

    if(isset($_GET["buscar"]))
        $buscar=$_GET["buscar"];
    else
        $buscar="";
    
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
    ?>
    <style>
        .formC
        {
            display: none;
        }
        .formC .textoC
        {
            height: 20px;
            width: 30%;
        }
        .formC .div
        {
            width: fit-content;
        }
        .opciones .liga
        {
            margin-right: 3px;
        }

    </style>
    <script>
        function valida()
        {
            if(nombreDiv=="confirmacionEnvio")
                return true;
            else{
                var texto=document.getElementById(nombreForm).value;
                if(texto!="")
                    return true;
            }
        }

        function editar()
        {
            var form = document.getElementById("botones");
            form.action='editarProducto.php';
            form.submit();
        }
        function muestraForm(idForm)
        {
            nombreForm = nombreDiv = idForm;
            var form = document.getElementById(nombreForm);
            form.style.display = "block";
        }

    </script>
</head>

<body>
    <?php
    encabezado();
    ?>
    <section class="contenidoPagina">
    <?php

    $qry = "select * from productos where idProducto='".$_REQUEST["idP"]."'";
    $res = mysqli_query($conn,$qry);
    if(!mysqli_num_rows($res)>0)
        header("Location:http://localhost/tienda/principal.php");

    if(isset($_REQUEST["buscar"]))
        echo "<a class='liga' href='busqueda.php?buscar=".$_REQUEST["buscar"]."'>Regresar</a>";
    else
        echo "<a class='liga' href='principal.php'>Pagina principal</a>";

    $reg = mysqli_fetch_array($res);
    $id=$reg["idProducto"];
    echo "
    <div id='producto'>
        <div id='imagen'>
            <img src='imagenProducto.php?idIp=".$reg["idProducto"]."' alt='Imagen'>
        </div>
        <div id='datosI'>
            <h4>".$reg["nombre"]."</h4>" ;
        
            $qryPromo = "select descuento from promociones where idProducto=".$reg["idProducto"];
            $promo=mysqli_query($conn,$qryPromo);
            if(mysqli_num_rows($promo)>0)
            {
                $descuento = mysqli_fetch_array($promo);
                $pFinal = $reg["precio"]*(100-$descuento["descuento"])/100;
                echo"<p style='text-decoration:line-through;' id='precio'>$".$reg["precio"]."</p>
                    <p style='color:red;' id='promoDes'>-".$descuento["descuento"]."%</p>
                    <p>$$pFinal</p>";
            }
            else
                echo"<p id='precio'>$".$reg["precio"]."</p>" ;


        echo"<p id='descripcion'> ".$reg["descripcion"]."</p>


    <form id='botones' method='get' action=''>";
            
        $qry="select ingrediente as i from ingredientes where idProducto='".$reg["idProducto"]."'";
        $res=mysqli_query($conn,$qry);
        if(mysqli_num_rows($res)>0)
        {
            echo"<select id='ingrediente' name='ingrediente'>";
            while($reg = mysqli_fetch_array($res))
            {
                echo "<option value='".$reg["i"]."'>".$reg["i"]."</option>";
            }
            echo"</select>";
        }
        if(isset($_SESSION["usuario"])){
            echo"<input  id='cantidad' type='number' name='cantidad' value='1' min='1'>";
            echo"<div class='links'>
                    <input  class='enviar' type='submit' value='Comprar' onclick=\"this.form.action='compra.php';this.form.submit();\">
                    <input  class='enviar' type='submit' value='Agregar al carrito' onclick=\"this.form.action='agregarCarrito.php';this.form.submit();\">
                </div>

            <input class='oculto' type='hidden' name='idP' value='".$id."'>";
            //<input class='oculto' type='hidden' name='buscar' value='".$buscar."'>";
        if($_SESSION["tipo"]=="Administrador")
        {
            echo"<div class='links'>
                    <a class='liga' href='modifica.php?idP=$id' >Modificar</a>
                    <a href=\"javascript:confirmacion('formProd')\" class='liga'>Eliminar</a>
                    <a href=\"javascript:muestraForm('formDes')\" class='liga'>Asignar descuento</a>
                </div>";

        }

        }
        else{
            echo"<a class='liga' href='login.php?idP=".$id."$buscar'>Inicia sesión</a> o <a class='liga' href='registro.php'>registrate</a> para comprar este producto";

        }
    echo"</form>

            <form id='formDes' class='formC' action='producto.php' method='get' >
                <label>Porcentaje: </label><input type='number' min='1' class='textoC' name='porcentaje'>
                <input name='idPD' type='hidden' value='$id'>
                <div>
                    <input style='float:right' class='enviar' type='button' onclick='confSi()' value='Guardar'>
                    <input style='float:right' class='enviar' type='button' onclick='confNo()' value='Cancelar'>
                </div>
            </form>
        </div>
    </div>";
        
    ?>

        <section id="comentarios">
            <h4>Comentarios:</h4>
        <?php
        if(isset($_SESSION["usuario"]))
        {
        ?>
            <form id="comentar" method="post" action="producto.php">
                <textarea class="textoC"  name="comentario" placeholder="Escribe un comentario"></textarea>
                <input type="hidden" value="<?php echo $_GET["idP"];?>" name="idP">
                <input type="hidden" value="<?php echo $_GET["buscar"];?>" name="buscar">
                <input class="enviar" type="submit" value="Comentar">
            </form>
        <?php
        }
            $qry = "select c.idUsuario, c.idComentario, c.fecha, c.contenido, u.usuario 
                    from comentarios as c inner join usuarios as u on c.idUsuario = u.idUsuario where idProducto='".$_REQUEST["idP"]."'";
            $res = mysqli_query($conn,$qry);

            if(mysqli_num_rows($res)>0)
            {
                while($reg = mysqli_fetch_array($res))
                {
                    echo "
                    <div id='com".$reg["idComentario"]."' class='comentario'>
                        <h4>".$reg["usuario"]."</h4>
                        <p>".$reg["contenido"]."</p>
                        <p>".$reg["fecha"]."</p>";
                    echo"<div class='opciones'>";
                    if(isset($_SESSION["usuario"]) && ($reg["idUsuario"]==$_SESSION["idUsuario"] || $_SESSION["tipo"]=="Administrador"))
                    {
                        echo"<a class='liga' href='eliminarComentario.php?buscar=".$_REQUEST["buscar"]."&idP=".$_REQUEST["idP"]."&idC=".$reg["idComentario"]."'>Eliminar</a>";
                        if($reg["idUsuario"]==$_SESSION["idUsuario"])
                            echo"<a class='liga' href=\"javascript:muestraForm('form".$reg["idComentario"]."')\">Editar</a>";
                    }
                    echo"</div>
                        <form id='form".$reg["idComentario"]."' class='formC' action='producto.php' method='get' >
                            <textarea class='textoC' name='contenido'></textarea>
                            <input name='idCE' type='hidden' value='".$reg["idComentario"]."'>
                            <input name='idP' type='hidden' value='".$reg["idComentario"]."'>
                            <input style='float:right' class='enviar' type='button' onclick='confSi()' value='Guardar'>
                            <input style='float:right' class='enviar' type='button' onclick='confNo()' value='Cancelar'>
                        </form>
                    </div><hr>";
                }
            }
            else
            {
                echo "<h4 id='subtitulo'>Aun no hay comentarios</h4>";
            }
        ?>
        <?php
            confirmacion();
        ?>
        <form id="formProd" action="producto.php" method="POST">
            <input name="idPE" type="hidden" value="<?php echo $_REQUEST["idP"]; ?>">
        </form>
        </section>
    </section>
    <?php
            footer();
        ?>
</body>
</html>