<?php
if (!empty($_POST["btnguardar"])) {
    if (
        !empty($_POST["nombre"]) and !empty($_POST["apellidos"]) and !empty($_POST["numero_control"]) and
        !empty($_POST["carrera"]) and !empty($_POST["semestre"])
    ) {
        $nombre = $_POST["nombre"];
        $apellidos = $_POST["apellidos"];
        $no_ctrl = $_POST["numero_control"];
        $carrera = $_POST["carrera"];
        $semestre = $_POST["semestre"];
        $sql = $conexion->query(" SELECT COUNT(*) as 'total' from alumno where numero_control ='$no_ctrl';");
        if ($sql->fetch_object()->total > 0) {
?>
            <script>
                alert("El usuario ya existe");
            </script>
        <?php
        } else {
            $reg=$conexion->query("INSERT INTO `alumno` 
(`numero_control`, `nombre`, `apellido`, `carrera`, `semestre`)
VALUES 
('$no_ctrl', '$nombre', '$apellidos','$carrera', $semestre);");
        if ($reg==true) {
            ?>
            <script>
                alert("Alumno registrado correctamente");
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
    } else { ?>
        <script>
            alert("Ocurrio un error");
        </script>
    <?php
    } ?>
    <script>
        setTimeout(() => {
            window.history.replaceState(null, null, window.location.pathname)
        }, 0);
    </script>
<?php
}
