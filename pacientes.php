<?php 
    session_start();
    include_once("functionality/check_user.php");
    if($data['puesto'] == 'usuario' && $data['autoridad'] == 1) header("Location: menu.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>CRBE - Clínica Rafael Belloso Chacín</title>
</head>
<body>
<div class="container d-flex align-items-center flex-column">
        <h2 class="text-center my-5">Gestionar Pacientes</h2>
            <a href='./agregar_paciente.php' class="btn btn-primary mb-2 w-25">Agregar Paciente</a>
            <a href='./ver_pacientes.php' class="btn btn-primary mb-2 w-25">Ver Pacientes</a>
        <a href='./menu.php' class="btn btn-danger mb-2 w-25">Volver</a>
    </div>
</body>
</html>