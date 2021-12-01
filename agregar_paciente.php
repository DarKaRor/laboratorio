<?php
    include_once("functionality/queries.php");
    include_once("functionality/check_user.php");
    include_once("functionality/customs.php");

    if($data['puesto'] == 'usuario' && $data['autoridad'] == 1) header("Location: menu.php");

    if($_SERVER['REQUEST_METHOD']==='POST'){
        $_SESSION['postdata'] = $_POST;
        header('Location: '.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
        exit;
    }

    if(isset($_SESSION['postdata'])){
        $_POST = $_SESSION['postdata'];
        if(is_set_empty(['nombre','apellido','cedula','peso','edad','genero'],$_POST)){
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido']; 
            $cedula = $_POST['cedula'];
            $peso = $_POST['peso'];
            $edad = $_POST['edad'];
            $genero = $_POST['genero'];

            $query = make_query("INSERT INTO persona (cedula,nombre,apellido,puesto) VALUES ('$cedula','$nombre','$apellido','paciente')");
            $id_persona = $con->insert_id;
            $genero = $genero == 1 ? 'M' : 'F';
            $query = make_query("INSERT INTO paciente (peso,edad,genero,id_persona) VALUES ('$peso','$edad','$genero','$id_persona')");

        }
        else set_error('Debe de ingresar todos los datos');
        unset($_SESSION['postdata']);
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
                <h2>Agregar Paciente</h2>
                <form method="post">
                    <div class="row mt-1">
                        <div class="form-group col-6">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control " name="nombre" id="nombre"  placeholder="Nombre" required>                   
                        </div>
                        <div class="form-group col-6">
                            <label for="apellido">Apellido</label>
                            <input type="text" class="form-control " name="apellido" id="apellido"  placeholder="Apellido" required>                   
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" aria-describedby="email" placeholder="Ingrese el correo" required>
                    </div>
                    <div class="form-group">
                        <label for="cedula">Cedula</label>
                        <input type="number" min=1 class="form-control " name="cedula" id="cedula"  placeholder="Cédula" required>
                        <label for="peso">Peso</label>
                        <input type="number" min=1 step='any' class="form-control " name="peso" id="peso"  placeholder="Peso en Kg" required>
                        <label for="edad">Edad</label>
                        <input type="edad" min=1 class="form-control " name="edad" id="edad"  placeholder="Edad" required>  
                        <label>Genero</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="genero" id="hombre" value="1" checked>
                            <label class="form-check-label" for="hombre">Hombre</label>
                        </div>    
                        <div class="form-check">       
                            <input class="form-check-input" type="radio" name="genero" id="mujer" value="2" checked>
                            <label class="form-check-label" for="mujer">Mujer</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-5 w-100">
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                        <a href='./pacientes.php' class="btn btn-danger mx-3">Volver</a>
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