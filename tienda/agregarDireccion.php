<?php
    session_start();
    include("funciones.php");
    sacaUsuarioN_A();
    $msg = "";
    if(isset($_POST["nombre"]) && isset($_POST["colonia"]) &&
        isset($_POST["codigo"]) && isset($_POST["calle"]) && 
        isset($_POST["numero"]) && isset($_POST["telefono"]))
    {
        $conn = conectarBD();
        $qry = "insert into direcciones (idUsuario, nombre, numero, calle, colonia, codigoPostal, telefono)
            values ('".$_SESSION["idUsuario"]."','".$_POST["nombre"]."','".$_POST["numero"]."','".$_POST["calle"]."', '".$_POST["colonia"]."','".$_POST["codigo"]."','".$_POST["telefono"]."')";
        if(mysqli_query($conn,$qry))
            $msg="<p class='pCorrecto'>La dirección ha sido registrada exitosamente</p>";
        else
            $msg="<p class='pError'>Hubo un error intente mas tarde</p>";
                
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
        relacionesFormulario();
        relacionesEstiloBarra();
    ?>
    <script>
        function validaCampos()
        {
            var colonia = document.getElementById("colonia").value;
            var codigo = document.getElementById("codigo").value;
            var calle = document.getElementById("calle").value;
            var numero = document.getElementById("numero").value;

            if(colonia=="" || codigo=="" || calle=="" || numero=="")
            {
                alert("Los campos con asterisco son requisito para agregar una dirección");
                return false;
            }
        }
    </script>
</head>
<body onload="verificaReenvio()">
    <?php
        encabezado();
    ?>
    <section class="contenidoPagina">
        <h2>Agregar dirección</h2>
        <hr>
        <form class="formulario" action="agregarDireccion.php" method="post" onsubmit="return validaCampos()">
            <label class='campo'><p>Nombre:</p> <input type="text" id="nombre" name="nombre"></label> 
            <label class='campo'><p>*Colonia:</p> <input type="text" id="colonia" name="colonia"></label> 
            <label class='campo'><p>*CP:</p> <input type="text" id="codigo" name="codigo"></label> 
            <label class='campo'><p>*Calle:</p> <input type="text" id="calle" name="calle"></label> 
            <label class='campo'><p>*Numero:</p> <input type="text" id="numero" name="numero"></label> 
            <label class='campo'><p>Telefono:</p> <input type="text" id="telefono" name="telefono"></label> 
            <?php 
                if($msg!="")
                {
                    echo $msg;
                }
            ?>
            <br>
            <input class='enviar' type='submit' value='Guardar'>
        </form>
        <a class="liga" href="configuraDatos.php">Regresar</a>
        <br>
        <br>
        <a class='liga' href="principal.php">Pagina principal</a>
    </section>
    <?php
        footer();
    ?>
</body>
</html>