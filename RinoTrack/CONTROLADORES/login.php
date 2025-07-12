<?php
session_start();

if (!empty($_POST["btn_ingresar"])) {
    if (!empty($_POST["usuario"]) and !empty($_POST["password"])) {
        $usuario=$_POST["usuario"];
        $password=md5($_POST["password"]);
        $sql=$conexion->query("SELECT * FROM usuario where usuario= '$usuario' and password='$password'");
        if ($datos = $sql->fetch_object()) {
            $_SESSION['id_usuario']=$datos->id;
            $_SESSION['usuario']=$datos->usuario;
            $_SESSION['nombre_u']=$datos->nombre_u;
            $_SESSION['apellido_u']=$datos->apellido_u;
            $_SESSION['rol']=$datos->rol;
            $_SESSION['numero_control']=$datos->numero_control;
            switch ($datos->rol) {
                case 'admin':
                    header("location: ../PAGINAS/panel_admin.php");
                    break;
                case 'alumno':
                    
                    header("location: ../PAGINAS/panel_alumno.php");
                    break;
                    
            }
        } else {
            echo "<p id='msj'>El usuario no existe</p>";
        }
        
    } else {
        echo "<p id='msj'>Campos vacios</p>";
    }
    
}
?>
