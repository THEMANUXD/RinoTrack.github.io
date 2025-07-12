<?php
if (!empty($_POST["btn_entr"])) {
    if (!empty($_POST["noctrol"])) {
        $noctrol = $_POST["noctrol"];
        $consult = $conexion->query("SELECT COUNT(*) as 'total'from alumno where numero_control= '$noctrol'");
        if ($consult->fetch_object()->total > 0) {
            $fecha = date("Y-m-d h:i:s");

            $consfech = $conexion->query("SELECT entrada from registro_horas where numero_control='$noctrol' order by id_registro desc limit 1");
            $fechabd = $consfech->fetch_object()->entrada;
            if (substr($fecha, 0, 10) == substr($fechabd, 0, 10)) {
                echo "Ya se registro una entrada";
            } else {
                $sql = $conexion->query("INSERT INTO `registro_horas` (`numero_control`, `entrada`, `salida`) VALUES ('$noctrol', '$fecha', '')");
                if ($sql == true) {
                    echo "BIENVENIDO";
                } else {
                    echo "ERROR";
                }
            }
        } else {
?>
            <script>
                alert("Numero de control no encontrado");
            </script>
    <?php
        }
    }
    ?>
    <script>
        setTimeout(() => {
            window.history.replaceState(null, null, window.location.pathname)
        }, 0);
    </script>
<?php

}

?>
<!--salida-->
<?php
if (!empty($_POST["btn_sal"])) {
    if (!empty($_POST["noctrol"])) {
        $noctrol = $_POST["noctrol"];
        $consult = $conexion->query("SELECT COUNT(*) as 'total'from alumno where numero_control= '$noctrol'");
        if ($consult->fetch_object()->total > 0) {
            $fecha = date("Y-m-d h:i:s");
            $cns = $conexion->query("SELECT id_registro from registro_horas where numero_control='$noctrol' order by id_registro desc limit 1");
            $id_reg = $cns->fetch_object()->id_registro;
            $sql = $conexion->query("UPDATE registro_horas set salida='$fecha' where id_registro='$id_reg'");
            if ($sql == true) {
                echo "Salida registrada correctamente";
            } else {
                echo "ERROR";
            }
        } else {
?>
            <script>
                alert("Numero de control no encontrado");
            </script>
    <?php
        }
    }
    ?>
    <script>
        setTimeout(() => {
            window.history.replaceState(null, null, window.location.pathname)
        }, 0);
    </script>
<?php

}

?>