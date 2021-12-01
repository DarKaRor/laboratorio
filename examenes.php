<?php
    include_once("functionality/queries.php");
    include_once("functionality/check_user.php");
    include_once("functionality/customs.php");
    include_once("env.php");
    if($data['puesto'] == 'usuario' && $data['autoridad'] == 1) header("Location: menu.php");
    if($data['puesto'] == 'enfermero' && $data['autoridad']<3) header("Location: menu.php");
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require_once __DIR__.'/vendor/autoload.php';

    

    function welcome(){
        if(date("H") < 12) return "Buenos dias";
        elseif(date("H") > 11 && date("H") < 18) return "Buenas tardes";
        elseif(date("H") > 17) return "Buenas noches";
    } 

    function sendEmail($pdf,$data){
        global $email_send;
        global $password_send;
        $mail = new PHPMailer(true);
        $patientName = "{$data['patientName']} {$data['patientSecond']}";
        $emailBody = '';
        $emailBody.='<h1> CRBE - Clinica Rafael Belloso Chacin</h1><br/>';
        $emailBody.= welcome().", $patientName<br/>";
        $emailBody.="Estos son los resultados de sus examenes en nuestra clinica por parte del Doctor {$data['doctorName']} {$data['doctorSecond']}";  
        
        try {
            //Server settings
            $mail->SMTPDebug = false;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $email_send;                     //SMTP username
            $mail->Password   = $password_send;                               //SMTP password
            $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('clinica@crbe.com', 'CRBE');
            $mail->addAddress($data['patientMail'], $patientName);     //Add a recipient

            $mail->addStringAttachment($pdf,'resultados.pdf');

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Resultados Clinica';
            $mail->Body    = $emailBody;
            $mail->AltBody = strip_tags($emailBody);

            $mail->send();

        } catch (Exception $e) {
            return [FALSE,"Message could not be sent. Mailer Error: {$mail->ErrorInfo}"];
        }
    }

    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        if(is_set_empty(['ID'],$_GET)){
            $id = $_GET['ID'];
            if(is_set_empty(['borrar'],$_GET)){
                $query = make_query("SELECT * FROM examen WHERE id_examen=$id");
                if(mysqli_num_rows($query)<=0) header('Location: examenes.php');
                make_query("DELETE FROM examen WHERE id_examen=$id");
            }
            if(is_set_empty(['enviar'],$_GET)){
                $mpdf = new \Mpdf\Mpdf();
                $pdf_data = '';
                $pdf_data.= '<h1>CRBE - Clínica Rafael Belloso Chacín</h1><br/>';
                $pdf_data.= "<strong>Doctor {$data['nombre']} {$data['apellido']}</strong><br/><br/>";
                
                $query = make_query("SELECT * FROM paciente WHERE id_paciente=$id");
                if(mysqli_num_rows($query)<=0) header('Location: menu.php');
                $paciente = mysqli_fetch_array($query);
                $query = make_query("SELECT * FROM persona WHERE id_persona={$paciente['id_persona']}");
                $persona = mysqli_fetch_array($query);
                

                $pdf_data.= "<strong>Paciente {$persona['nombre']} {$persona['apellido']}</strong><br/><br/>";
                $pdf_data.= "<strong>Resultados de Examenes</strong><br/><br/>";
                

                $query = make_query("SELECT * FROM examen WHERE concluído=0 AND resultado IS NOT NULL AND id_paciente='$id'");
                if(mysqli_num_rows($query)<=0) header('Location: menu.php');
                while($arr = mysqli_fetch_array($query)){
                $pdf_data.="<strong> {$arr['tipo']}: </strong><br/> {$arr['resultado']}<br/><br/>";
                }
                

                make_query("UPDATE examen SET concluído=1 WHERE resultado IS NOT NULL AND id_paciente='$id'");
                
                $emailData=[
                    'doctorName'=>$data['nombre'],
                    'doctorSecond'=>$data['apellido'],
                    'patientName'=>$persona['nombre'],
                    'patientSecond'=>$persona['apellido'],
                    'patientMail'=>$paciente['correo'],
                ];

                $mpdf->WriteHTML($pdf_data);
                $pdf = $mpdf->Output('','S');
                $email = sendEmail($pdf,$emailData);

                if(!$email[0]) set_error($email[1]);
                else header("Location: examenes.php");
            }
        }
    }   

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(is_set_empty(['resultados'],$_POST)){
            $resultados = $_POST['resultados'];
            foreach($resultados as $id=>$r){
                $query = make_query("SELECT * FROM examen WHERE id_examen='$id'");
                if($query){
                    if(mysqli_num_rows($query)>0){
                        $r = empty($r) ? "NULL" : "'$r'";
                        make_query("UPDATE examen SET resultado=$r WHERE id_examen='$id'");
                    }
                }
            }
        }
    }

    $pacientes = get_examen_doctor_patient($data['id_persona']);
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
        <div class="row justify-content-center align-items-center min-vh-100 mt-5">
            <div class='col-6 text-center'>
                <h2>Examenes</h2>
                <?php if(count($pacientes)<=0): ?>
                    <h3 class='text-success'>No tiene ningún examen por evaluar.</h3>
                <?php endif; ?>
                <form method='post'>
                    <div class="accordion" id="accordionPacientes">
                    <?php foreach($pacientes as $paciente): 
                        $num = $paciente['id_paciente'];
                        $persona = $paciente['persona'];
                        $examenes = $paciente['examenes'];
                    ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?php echo $num;?>">
                            <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $num;?>" aria-expanded="true" aria-controls="collapseOne">
                                <?php echo "Paciente #$num - ".$persona['nombre'].' '.$persona['apellido'].' - '.$persona['cedula'];?>
                            </button>
                            </h2>
                            <div id="collapse<?php echo $num?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $num;?>" data-bs-parent="#accordionPozos">
                                <div class="accordion-body p-0">
                                <a onclick="enviar_pdf(<?php echo $num?>)" class='btn btn-success w-100 rounded-0 mb-1'>Enviar Resultados al Paciente por PDF</a>
                                <?php foreach ($examenes as $i=>$examen): 
                                    $fecha = (strtotime($examen['fecha']));
                                    $fecha = date("F j, Y, g:i a",$fecha);
                                    $query = make_query("SELECT * FROM persona WHERE id_persona='{$examen['id_enfermero']}'");
                                    $autor = mysqli_fetch_array($query);
                                    $autor_nombre = $autor['nombre']." ".$autor['apellido'];
                                ?>
                                    <ul class="list-group p-2">
                                        <li class="list-group-item list-group-item-primary"><?php echo $examen['tipo']; ?></li>
                                        <li class="list-group-item">Fecha: <?php echo $fecha; ?></li>
                                        <li class="list-group-item">Autor de Petición: <?php echo $autor_nombre ?></li>
                                        <li class="list-group-item" >
                                            <label for="resultado<?php echo "$num-$i" ?>">Resultado: </label>
                                            <input type="text" class="form-control " name="resultados[<?php echo $examen['id_examen']?>]" id="resultado<?php echo "$num-$i" ?>" value='<?php echo $examen['resultado'] ?>' placeholder="Resultado">
                                        </li>
                                        
                                        <a onclick="eliminar(<?php echo $examen['id_examen']?>)" class='btn btn-danger w-100'>Eliminar Petición</a>
                                    </ul>
                                <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if(count($pacientes)>0): ?>
                <button type='submit' class='btn btn-success mb-1 w-100 mt-5'>Aplicar Cambios</button>
                <?php endif; ?>
                <a href="./ver_pacientes.php" class='btn btn-primary mb-1 w-100'>Ver Pacientes</a>
                <a href="./menu.php" class='btn btn-danger   w-100'>Volver</a>
            </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
        function eliminar(id){
            var id = id;
            confirmar = confirm('Deseas borrar el registro?');
            if (confirmar){
                url = './examenes.php?ID='+id+'&borrar=si';
                location.href=url;
                alert('Eliminado!, El registro se eliminó completamente!');
            }
            else alert('Cancelado. No se ha eliminado nada');
            return confirmar;
            windows.refresh();
        }

        function enviar_pdf(id_paciente){
            var id = id_paciente;
            confirmar = confirm('Deseas enviar los datos? Una vez enviados, no sé podrá modificar el resultado. Sólo se enviaran los que tengan resultado. Recuerde Aplicar Cambios antes de enviar.');
            if(confirmar){
                url = './examenes.php?ID='+id_paciente+'&enviar=si';
                location.href=url;
            }
            else alert('Cancelado.');
            return confirmar;
            windows.refresh();      
        }
    </script>
</body>
</html>