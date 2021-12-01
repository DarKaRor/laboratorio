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

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(is_set_empty(['ID'],$_GET)){
            $id = $_GET['ID'];
            $paciente = get_paciente($id);
            if($paciente==NULL) header('Location: ver_pacientes.php');
            $persona = $paciente['persona'];
            $nombre = $persona['nombre'];
            $apellido = $persona['apellido'];
            $cedula = $persona['cedula'];
            $peso = get_int(floatval($paciente['peso']));
            $edad = $paciente['edad'];
            $genero = $paciente['genero'];
            $correo = $paciente['correo'];

            $_SESSION['paciente'] = $paciente;
        }
        else header('Location: ver_pacientes.php');
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        if(is_set_empty(['nombre','apellido','cedula','peso','edad','genero','email'],$_POST)){
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $cedula = $_POST['cedula'];
            $peso = $_POST['peso'];
            $edad = $_POST['edad'];
            $genero = $_POST['genero'];
            $genero = $genero == 1 ? "M" : 'F';
            $paciente = $_SESSION['paciente'];
            $persona = $paciente['persona'];
            $correo = $_POST['email'];
            $query = make_query("UPDATE persona SET cedula='$cedula',nombre='$nombre',apellido='$apellido' WHERE id_persona='{$persona['id_persona']}'");
            if($query) $query = make_query("UPDATE paciente SET peso='$peso',genero='$genero',edad='$edad',correo='$correo' WHERE id_paciente=$id");
            else set_error("Hubo un error al editar.");
            
        }
        else set_error("Debe de ingresar todos los datos");
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
            <h2>Modificar Paciente</h2>
                <form method="post">
                    <div class="row mt-1">
                    <div class="form-group">
                        <label for="id">ID Paciente</label>
                        <input type="number" min=1 class="form-control " name="id" id="id" value='<?php echo $id ?>' readonly>
                    </div>
                        <div class="form-group col-6">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control " name="nombre" id="nombre" value='<?php echo $nombre ?>' placeholder="Nombre" required>                   
                        </div>
                        <div class="form-group col-6">
                            <label for="apellido">Apellido</label>
                            <input type="text" class="form-control " name="apellido" id="apellido" value='<?php echo $apellido ?>'  placeholder="Apellido" required>                   
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" aria-describedby="email" value='<?php echo $correo ?>' placeholder="Ingrese el correo" required>
                    </div>
                    <div class="form-group">
                        <label for="cedula">Cedula</label>
                        <input type="number" min=1 class="form-control " name="cedula" id="cedula" value='<?php echo $cedula ?>'  placeholder="Cédula" required>
                        <label for="peso">Peso (Kg)</label>
                        <input type="number" min=1 step='any' class="form-control " name="peso" id="peso"  value='<?php echo $peso ?>' placeholder="Peso en Kg" required>
                        <label for="edad">Edad</label>
                        <input type="edad" min=1 class="form-control " name="edad" id="edad"  value='<?php echo $edad ?>' placeholder="Edad" required>  
                        <label>Genero</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="genero" id="hombre" value="1" <?php if($genero =='M') echo 'checked';?>>
                            <label class="form-check-label" for="hombre">Hombre</label>
                        </div>    
                        <div class="form-check">       
                            <input class="form-check-input" type="radio" name="genero" id="mujer" value="2"  <?php if($genero =='F') echo 'checked';?>>
                            <label class="form-check-label" for="mujer">Mujer</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-5 w-100">
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                        <a href='./ver_pacientes.php' class="btn btn-danger mx-3">Volver</a>
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