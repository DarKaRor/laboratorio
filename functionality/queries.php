<?php
session_start();
include_once(__DIR__.'/../cnx/conexion.php');
date_default_timezone_set('America/Caracas'); # Convirtiendo la zona horaria a Caracas
$fmt_d = 'Y-m-d'; # Formato de fecha
$fmt_h = 'H:i:s'; # Formato de hora

$tipos_examen=['Hemograma','Urinálisis','Heces','Perfil Renal','Perfil lípdico','Perfil hepático','Perfil triode','Panel Básico metabólico','Covid','Sangre'];

# Realiza una petición a la base de datos.
function make_query($query){
    global $con;
    $rsQUERY = mysqli_query($con,$query) or die('Error: '.mysqli_error($con));
    return $rsQUERY;
}

# Revisa si existe un elemento con el valor introducido en la tabla y columna introducidas, puede o no ser insensible a mayusculas y minúsculas.
function check_existence($table,$column,$value,$sensitive=FALSE){
    global $con;
    $condition = $sensitive ? "LOWER($column)='".strtolower($value)."'" : "$column='$value'";
    $query = make_query("SELECT * FROM $table WHERE ".$condition);
    if(!$query) return FALSE;
    $countQuery = mysqli_num_rows($query);
    if ($countQuery<=0) return FALSE;
    return $query;
}

function load_user($id,$session=FALSE){
    $query = make_query("SELECT * FROM persona WHERE id_persona='$id'");
    if (mysqli_num_rows($query)<=0){
        session_unset();
        header("Location: index.php");
        return;
    }
    $userQuery = make_query("SELECT * FROM usuario WHERE id_persona='$id'");
    $user = mysqli_fetch_array($userQuery);
    $user['data'] = mysqli_fetch_array($query);
    if($session){
        $_SESSION['user'] = $user;
        return;
    }
    $arr = array();
    $arr['user'] = $user;
    return $arr;
}

function get_paciente($id){
    $query = make_query("SELECT * FROM paciente WHERE id_paciente=$id");
    if(mysqli_num_rows($query)<=0) return NULL;
    $arr = mysqli_fetch_array($query);
    $queryPersona = make_query("SELECT * FROM persona WHERE id_persona='{$arr['id_persona']}'");
    $arr['persona'] = mysqli_fetch_array($queryPersona);
    return $arr;
}

function get_pacientes(){
    $query = make_query("SELECT * FROM paciente");
    if (mysqli_num_rows($query)<=0) return [];
    $pacientes = [];
    while($arr = mysqli_fetch_array($query)){
        $queryPersona = make_query("SELECT * FROM persona WHERE id_persona='{$arr['id_persona']}'");
        $arr['persona'] = mysqli_fetch_array($queryPersona);
        array_push($pacientes,$arr);
    }
    return $pacientes;
}

function get_cedulas_doctor(){
    $query = make_query("SELECT cedula,nombre FROM persona WHERE puesto='doctor'");
    if (mysqli_num_rows($query)<=0) return [];
    $doctores = [];
    while($arr = mysqli_fetch_array($query)) array_push($doctores,array("cedula" => $arr['cedula'], 'nombre' => $arr['nombre']));
    return $doctores;
}

function get_patients_by_doctor($id_doctor){
    $query = make_query("SELECT id_paciente FROM examen WHERE id_doctor='$id_doctor' AND concluído!=1 GROUP BY id_paciente");
    if(mysqli_num_rows($query)<0) return [];
    $patients = [];
    while($arr = mysqli_fetch_array($query)) array_push($patients,$arr['id_paciente']);
    return $patients;
}

function get_examen_doctor_patient($id_doctor){
    $patients_id = get_patients_by_doctor($id_doctor);
    if(!$patients_id) return [];
    $patients = [];
    foreach($patients_id as $id){
        $query = make_query("SELECT * FROM examen WHERE id_doctor='$id_doctor' AND concluído!=1 AND id_paciente='$id'");
        $examenes = [];
        while($arr = mysqli_fetch_array($query)) array_push($examenes,$arr);
        $patient = get_paciente($id);
        $patient['examenes'] = $examenes;
        array_push($patients,$patient);
    }
    return $patients;
}


?>  