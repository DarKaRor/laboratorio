<?php
    include_once("functionality/queries.php");
    include_once("functionality/customs.php");
    $error = FALSE;
    $errorMsg = "";
    $email = '';

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $query = make_query("SELECT * FROM usuario WHERE correo='{$_POST['email']}' AND password='".md5($_POST['password'])."'");
        $email = $_POST['email'];

        if(mysqli_num_rows($query)>0){
            $arr = mysqli_fetch_array($query);
            $_SESSION['user'] = $arr;
            $query = make_query("SELECT * FROM persona WHERE id_persona='{$arr['id_persona']}'");
            $arr = mysqli_fetch_array($query);
            $_SESSION['user']['data'] = $arr;
            header("Location: menu.php");
        }
        else{
            $error = TRUE;
            $errorMsg = "Correo o contraseña incorrectos.";
        }
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
                <h2 class='text-center'>Inicio de Sesión</h2>
                <form method="post">
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" value="<?php echo $email ?>" name="email" aria-describedby="email" placeholder="Ingrese su correo" required>
                        <small id="emailHelp" class="form-text text-muted">No compartiremos tu correo.</small>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" name="password" id="password" minlength="8" placeholder="Ingrese su contraseña" required>
                        <small id="passHelp" class="form-text text-muted">Mínimo 8 caracteres</small>
                    </div>
                    <div class="d-flex justify-content-center mt-5 w-100">
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                        <a href='./registro.php' class="btn btn-success mx-3">Registrarse</a>
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