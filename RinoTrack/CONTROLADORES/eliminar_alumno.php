<?php
if (!empty($_GET["numero_control"])) {
    $id = $_GET["numero_control"];
    $sql = $conexion->query("DELETE FROM alumno WHERE 
    numero_control = $id");
    
    // Verificar si la consulta se ejecutó correctamente
    if ($sql=true) { ?>
        <script>
            alert ("Registro eliminado correctamente");
        </script>
    <?php } else {?>
        <script>
            alert ("Ocurrio un error");
        </script>
    <?php } ?>
<script>
    setTimeout(()=> {
        window.history.replaceState(null,null,window.location.pathname)
    },0);
</script>
    <?php

} 
?>

