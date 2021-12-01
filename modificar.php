<?php 
    include_once("functionality/queries.php");
    include_once("functionality/check_user.php");
    include_once("functionality/customs.php");
    $error = FALSE;
    $errorMsg = "";

    if($data['puesto'] == 'usuario' && $data['autoridad'] == 1) header("Location: menu.php");

    function data_if_not_set($key,$method){
        global $data;
        return isset($_POST[$key]) ? $_POST[$key] : $data[$key];
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $password = $_POST['password'];
        if (md5($password) == $user['password']){
            $cedula = data_if_not_set('cedula',$_POST);
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $puesto = data_if_not_set('puesto',$_POST);
            $autoridad = data_if_not_set('autoridad',$_POST);
            $nueva = is_set_empty(['new_password'],$_POST) ? md5($_POST['new_password']) : $password;
            $query = make_query("UPDATE persona SET cedula='$cedula',nombre='$nombre',apellido='$apellido',puesto='$puesto',autoridad='$autoridad' WHERE id_persona='{$data['id_persona']}'");
            if(!$query) set_error("No se ha podido modificar.");
            $query = make_query("UPDATE usuario SET password='$nueva' WHERE id_usuario='{$user['id_usuario']}'");
            load_user($data['id_persona'],TRUE);
        }
        else set_error("Clave incorrecta.");
    }

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
    <div class="container-fluid">
        <div class="row vh-100 justify-content-center align-items-center">
            <div class="col-3">
                <h2>Usuario #<?php echo $user['id_usuario'];?></h2>
                <form method="post">
                    <?php if($data['puesto']!='paciente'): ?>
                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <p type="email" class="form-control" id="email" name="email" aria-describedby="email" >
                            <small id="emailHelp" class="form-text text-muted"><?php echo $user['correo'];?></small>
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña Actual</label>
                            <input type="password" class="form-control" name="password" id="password" minlength="8" placeholder="Ingrese su contraseña" required>
                            <small id="passHelp" class="form-text text-muted">Mínimo 8 caracteres</small>
                        </div>
                        <div class="form-group">
                            <label for="new_password">Nueva Contraseña</label>
                            <input type="password" class="form-control" name="new_password" id="new_password" minlength="8" placeholder="Ingrese la nueva contraseña">
                        </div>
                    <?php endif; ?>
                    <div class="row mt-1">
                        <div class="form-group col-6">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control " name="nombre" id="nombre" value="<?php echo $data['nombre'] ;?>"  placeholder="Nombre" required>                   
                        </div>
                        <div class="form-group col-6">
                            <label for="apellido">Apellido</label>
                            <input type="text" class="form-control " name="apellido" id="apellido" value="<?php echo $data['apellido'] ;?>"  placeholder="Apellido" required>                   
                        </div>
                    </div>
                    <?php if ($data['autoridad'] == 3 || $data['puesto'] == 'paciente'): ?>
                        <div class="form-group">
                            <label for="cedula">Cedula</label>
                            <input type="number" min=1 class="form-control " name="cedula" id="cedula" value="<?php echo $data['cedula'] ;?>"  placeholder="cedula" required>                   
                        </div>
                    <?php endif ;?>
                    <?php if($data['autoridad'] == 3): ?>
                        <div class="form-group">
                            <label for="tipo">Puesto:</label>
                            <select class="form-control" id="puesto" name='puesto'>
                                <?php foreach(['doctor','paciente','enfermero','usuario'] as $tipo): ?>
                                    <option value='<?php echo $tipo; ?>' <?php if($data['puesto'] == $tipo) echo "selected='selected'"; ?>><?php echo $tipo;?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="tipo">Tipo de Usuario:</label>
                            <select class="form-control" id="autoridad" name='autoridad'>
                                <?php for($i=1;$i<=3;$i++): ?>
                                    <option value='<?php echo $i; ?>' <?php if($data['autoridad'] == $i) echo "selected='selected'"; ?>><?php echo $i;?></option>
                                <?php endfor; ?>
                            </select>                
                        </div>
                    <?php endif; ?>
                    <div class="d-flex justify-content-center mt-5 w-100">
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                        <a href='./menu.php' class="btn btn-danger mx-3">Volver</a>
                    </div>
                    <?php if($error): ?>
                        <div class="alert alert-danger w-100 mt-5 alert-dismissible fade show text-center" role="alert">
                            <strong>Error!</strong> <?php echo $errorMsg ?>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>