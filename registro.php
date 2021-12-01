<?php
    include_once('functionality/queries.php');
    include_once('functionality/customs.php');
    $error = FALSE;
    $errorMsg = "";

    # Recibiendo el método post, colocandolo en una variable de sesión, redireccionando. Para no poder volver a enviar los datos por error al refrescar.
    if($_SERVER['REQUEST_METHOD']==='POST'){
        $_SESSION['postdata'] = $_POST;
        header('Location: '.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
        exit;
    }

    # Si se recibe una variable de sesión con los datos de post.
    if(isset($_SESSION['postdata'])){
        $_POST = $_SESSION['postdata'];
        if(is_set_empty(['email','password','nombre','apellido'],$_POST)){
            $email = $_POST['email'];
            $query = make_query("SELECT * FROM usuario WHERE correo='$email'");
            if(mysqli_num_rows($query)<=0){
                $password = md5($_POST['password']);
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $query = make_query("INSERT INTO persona (cedula,nombre,apellido) VALUES (NULL,'$nombre','$apellido')");
                $id_persona = $con->insert_id;
                $query = make_query("INSERT INTO usuario (id_persona,correo,password) VALUES ('$id_persona','$email','$password')");
                # Conectarse
                header("Location: menu.php");
            }
            else set_error("Este correo electrónico ya existe.");
        }
        else set_error("Debe de ingresar todos los datos.");

        unset($_SESSION['postdata']);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>CRBE - Clínica Rafael Belloso Chacín</title>
</head>
<body>
<div class="container-fluid">
        <div class="row vh-100 justify-content-center align-items-center">
            <div class="col-3">
                <h2 class='text-center'>Registro</h2>
                <form method="post">
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" aria-describedby="email" placeholder="Ingrese su correo" required>
                        <small id="emailHelp" class="form-text text-muted">No compartiremos tu correo.</small>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" name="password" id="password" minlength="8" placeholder="Ingrese su contraseña" required>
                        <small id="passHelp" class="form-text text-muted">Mínimo 8 caracteres</small>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control " name="nombre" id="nombre"  placeholder="Nombre" required>                   
                        </div>
                        <div class="form-group col-6">
                            <label for="apellido">Apellido</label>
                            <input type="text" class="form-control " name="apellido" id="apellido"  placeholder="Apellido" required>                   
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-5 w-100">
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                        <a href='./index.php' class="btn btn-danger mx-3">Volver</a>
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