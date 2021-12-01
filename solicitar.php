<?php
    include_once("functionality/queries.php");
    include_once("functionality/check_user.php");
    include_once("functionality/customs.php");


    if($data['puesto'] == 'usuario' && $data['autoridad'] == 1) header("Location: menu.php");
    if ($data['puesto'] != 'enfermero' && $data['autoridad']!=3) header("Location: menu.php");

    if($_SERVER['REQUEST_METHOD']==='GET'){
        if(is_set_empty(['ID'],$_GET)){
            $id = $_GET['ID'];
        }
        else header('Location: ver_pacientes.php');
    }
    $cedulas_doctor = get_cedulas_doctor();

    # Recibiendo el método post, colocandolo en una variable de sesión, redireccionando. Para no poder volver a enviar los datos por error al refrescar.
    if($_SERVER['REQUEST_METHOD']==='POST'){
        $_SESSION['postdata'] = $_POST;
        header('Location: '.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
        exit;
    }

    # Si se recibe una variable de sesión con los datos de post.
    if(isset($_SESSION['postdata'])){
        $_POST = $_SESSION['postdata'];
        if(is_set_empty(['id','id_enfermero','cedula_doctor','tipo'],$_POST)){
            $date= date("Y-m-d H:i:s");
            $tipo = $tipos_examen[intval($_POST['tipo'])-1];
            $id_paciente = $_POST['id'];
            $id_enfermero = $_POST['id_enfermero'];
            $cedula_doctor = $_POST['cedula_doctor'];
            $query = make_query("SELECT id_persona FROM persona WHERE cedula=$cedula_doctor");
            if(mysqli_num_rows($query)>0){
                $id_doctor = mysqli_fetch_array($query)['id_persona'];
                $query = make_query("INSERT INTO examen (id_paciente,id_enfermero,id_doctor,tipo,fecha) VALUES ('$id_paciente','$id_enfermero','$id_doctor','$tipo','$date')");
                if(!$query) set_error("No se ha podido registrar");
            }
            else set_error("No se ha encontrado dicho doctor.");
        }
        else set_error('Debe ingresar todos los datos');
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
            <h2>Solicitar Examen</h2>
                <form method="post">

                    <div class="form-group">
                        <label for="id">ID Paciente</label>
                        <input type="number" min=1 class="form-control " name="id" id="id" value='<?php echo $id ?>' required readonly>
                        <label for="id_enfermero">ID Enfermero/a</label>
                        <input type="number" class="form-control " name="id_enfermero" id="id_enfermero" value='<?php echo $data['id_persona'] ?>'  required readonly>                   
                        <label for="cedula_doctor">Cédula Doctor/a</label>
                        <select class="form-control" id="cedula_doctor" name='cedula_doctor'>
                                <?php for($i=0;$i<count($cedulas_doctor);$i++): 
                                    $doctor = $cedulas_doctor[$i]['nombre'];
                                    $cedula_doctor = $cedulas_doctor[$i]['cedula'];
                                ?>
                                    <option value='<?php echo $cedula_doctor; ?>'><?php echo $cedula_doctor.' - '.$doctor?></option>
                                <?php endfor; ?>
                        </select>       
                        <label for="tipo">Tipo de examen</label>
                        <select class="form-control" id="tipo" name='tipo'>
                                <?php foreach($tipos_examen as $i=>$tipo): ?> 
                                    <option value='<?php echo $i+1; ?>'><?php echo $tipo?></option>
                                <?php endforeach; ?>
                        </select>           
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