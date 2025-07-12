<?php
if (!empty($_GET["id_registro"])) {
    $id = intval($_GET["id_registro"]); 
    $sql = $conexion->query("DELETE FROM registro_horas WHERE id_registro = $id");
    
    // Verificar si la consulta se ejecutó correctamente
    if ($sql) { ?>
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

