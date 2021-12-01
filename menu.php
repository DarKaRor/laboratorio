<?php
    include_once("functionality/queries.php");
    include_once("functionality/check_user.php");
    $welcomeMsg = !empty($data['nombre']) ? "Bienvenido, {$data['nombre']}." : "Bienvenido.";
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
        <h2 class="text-center my-5"><?php echo $welcomeMsg ?></h2>
        <?php if($data['autoridad'] == 1 && $data['puesto'] == 'usuario'): ?>
            <h3 class='text-danger'>Tu cuenta no ha sido autorizada por el dueño del sistema.</h3>
        <?php else: ?>
            <a href='./pacientes.php' class="btn btn-primary mb-2 w-25">Gestionar Pacientes</a>
            <?php if($data['autoridad']>1):?>
                <a href='examenes.php' class="btn btn-primary mb-2 w-25">Gestionar Examenes</a>
            <?php endif; ?>
        <?php endif;?>
        <a href='./modificar.php' class="btn btn-primary mb-2 w-25">Modificar Cuenta</a>
        <button href='#' onclick="cerrar_sesion()" class="btn btn-danger mb-2 w-25">Cerrar Sesión</button>
    </div>
    <script>
        function cerrar_sesion(){
            if (confirm('Deseas cerrar sesión?')) {
                url = './process/logout.php';
                location.href=url;
            }
            else alert('Cancelado. No se ha eliminado nada');
            windows.refresh();
        }
    </script>
</body>
</html>