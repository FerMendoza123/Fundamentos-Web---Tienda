<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /*
        .recuadro
        {
            border
        }*/
    </style>
</head>


<body>
    <div class="recuadro">
        <h2>Bienvenido <?php echo $_SESSION["usuario"]?></h2>  
        <a href="principal.php"> Ir a la pagina principal </a>  
    </div>
</body>
</html>