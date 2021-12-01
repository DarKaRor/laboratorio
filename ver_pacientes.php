<?php
    include_once("functionality/queries.php");
    include_once("functionality/check_user.php");
    include_once("functionality/customs.php");
    
    if($data['puesto'] == 'usuario' && $data['autoridad'] == 1) header("Location: menu.php");

    if(is_set_empty(['ID','borrar'],$_GET)){
        $id = $_GET['ID'];
        $query = make_query("SELECT * FROM paciente WHERE id_persona=$id");
        if(mysqli_num_rows($query)<=0) header('Location: ver_pacientes.php');
        make_query("DELETE FROM paciente WHERE id_persona=$id");
        make_query("DELETE FROM persona WHERE id_persona=$id");
    }

    $pacientes = get_pacientes();
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
<div class='container'>
        <div class='row justify-content-center  mt-5'>
            <div class='col-6'>
                <a href="./pacientes.php" class='btn btn-danger mb-5'>Volver</a>
                <?php if(count($pacientes)<=0):?>
                    <div class="alert alert-danger col-6 alert-dismissible fade show text-center" role="alert">
                        <strong>Error!</strong> No hay ningun paciente ingresado
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <div class="accordion" id="accordionPacientes">
                <?php for($i = 0; $i<count($pacientes);$i++): 
                    $paciente = $pacientes[$i];
                    $num = $paciente['id_paciente'];
                    $persona = $paciente['persona'];
                ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?php echo $num;?>">
                        <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $num;?>" aria-expanded="true" aria-controls="collapseOne">
                            <?php echo "Paciente #$num - ".$persona['nombre'].' '.$persona['apellido'];?>
                        </button>
                        </h2>
                        <div id="collapse<?php echo $num?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $num;?>" data-bs-parent="#accordionPozos">
                            <div class="accordion-body p-0">
                            <a href="./modificar_paciente.php?ID=<?php echo $num?>" class='btn btn-success w-100 rounded-0 mb-1'>Editar</a>
                            <?php if($data['puesto'] == 'enfermero'): ?>
                                <a href="./solicitar.php?ID=<?php echo $num?>" class='btn btn-success w-100 rounded-0 mb-1'>Solicitar Examen</a>
                            <?php endif; ?>
                            <a href="#" onClick='eliminar(<?php echo $paciente['id_persona'];?>)' class='btn btn-danger w-100 rounded-0 mb-1'>Eliminar</a>
                            
                            <ul class="list-group p-2">
                                <li class="list-group-item">Cédula: <?php echo $persona['cedula']; ?></li>
                                <li class="list-group-item">ID Persona: <?php echo $persona['id_persona']; ?></li>
                                <li class="list-group-item">Peso: <?php echo get_int(floatval($paciente['peso'])).' Kg'; ?></li>
                                <li class="list-group-item">Edad: <?php echo $paciente['edad']; ?></li>
                            </ul>

                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
                </div>
            </div>
        </div>    
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
        function eliminar(id){
            var id = id;
            confirmar = confirm('Deseas borrar el registro?');
            if (confirmar){
                url = './ver_pacientes.php?ID='+id+'&borrar=si';
                location.href=url;
                alert('Eliminado!, El registro se eliminó completamente!');
            }
            else alert('Cancelado. No se ha eliminado nada');
            return confirmar;
            windows.refresh();
        }
    </script>
</body>
</html>