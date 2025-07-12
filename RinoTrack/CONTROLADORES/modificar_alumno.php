<?php
if (!empty($_POST['btnactualizar'])) {
    if (!empty($_POST["nombre"]) and !empty($_POST["apellidos"]) and !empty($_POST["numero_control"]) and
        !empty($_POST["carrera"]) and !empty($_POST["semestre"])) {
        $nombre = $_POST["nombre"];
        $apellidos = $_POST["apellidos"];
        $no_ctrl = $_POST["numero_control"];
        $carrera = $_POST["carrera"];
        $semestre = $_POST["semestre"];
        $udp=$conexion->query("UPDATE alumno SET 
                nombre = '$nombre', 
                apellido = '$apellidos', 
                carrera = '$carrera', 
                semestre = $semestre 
            WHERE numero_control = '$no_ctrl'");
        if ($udp==true) {
            ?>
            <script>
                alert("Datos registrados correctamente");
            </script>
        <?php
        } else {
            ?>
        <script>
            alert("Ocurrio un error");
        </script>
    <?php
        }
        

    }
    
    ?> <script>
        setTimeout(() => {
            window.history.replaceState(null, null, window.location.pathname)
        }, 0);
    </script> <?php
}
?>
