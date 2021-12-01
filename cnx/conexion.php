<?php
$con = mysqli_connect('localhost','root','admin','laboratorio');
$error = mysqli_connect_errno();
if ($error) echo 'Fallo al conectarse a MySQL '.$error;
