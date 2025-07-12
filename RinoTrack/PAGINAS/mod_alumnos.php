<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizacion datos de Estudiantes</title>
    <link rel="stylesheet" href="CSS/styles_reg.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Actualizacion datos de Estudiantes</h1>
        </div>
        <?php
        include "../CONEXION/conexion.php";
        include "../CONTROLADORES/modificar_alumno.php";
        if (!empty($_GET["numero_control"])) {
            $nctrl=intval($_GET["numero_control"]);
            
            $est=$conexion->query("SELECT * FROM alumno WHERE numero_control ='$nctrl'");
            $dat=$est->fetch_object();
        }
        ?>
        <div class="form-container">
            <form action=""0 method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="numero_control">Número de Control</label>
                        <input type="text" id="numero_control" name="numero_control" value="<?=$nctrl?>">
                    </div>

                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre(s)</label>
                        <input type="text" id="nombre" name="nombre" value="<?=$dat->nombre?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="apellido">Apellido(s)</label>
                        <input type="text" id="apellido" name="apellidos" value="<?=$dat->apellido?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="carrera">Carrera</label>
                        <select id="carrera" name="carrera" required>
                            <option value="">Seleccione una carrera</option>
                            <option value="Ingeniería en Sistemas Computacionales">Ingeniería en Sistemas Computacionales</option>
                            <option value="Ingeniería en Tecnologías de la Información y Comunicaciones">Ingeniería en Tecnologías de la Información y Comunicaciones</option>
                            <option value="Contador Público">Contador Público</option>
                            <option value="Ingeniería en Logística">Ingeniería en Logística</option>
                            <option value="Ingeniería Mecatrónica">Ingeniería Mecatrónica</option>
                            <option value="Ingeniería en Gestión Empresarial">Ingeniería en Gestión Empresarial</option>
                            <option value="Ingeniería Industrial">Ingeniería Industrial</option>
                            <option value="Ingeniería en Administración">Ingeniería en Administración</option>
                            <option value="Ingeniería Química">Ingeniería Química</option>
                            <option value="Ingeniería en Semiconductores">Ingeniería en Semiconductores</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="semestre">Semestre</label>
                        <input type="number" id="semestre" name="semestre" value="<?=$dat->semestre?>" required>
                    </div>
                </div>
                
                <div class="btn-container">
                    <button type="reset" class="btn btn-secondary" onclick="window.location.href='panel_admin.php';">Atras</button>
                    <button type="submit" class="btn btn-primary" value="ok" name="btnactualizar">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>