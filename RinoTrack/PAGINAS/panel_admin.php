<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (empty($_SESSION['nombre_u']) and empty($_SESSION['apellido_u'])) {
    header('Location: ../LOGIN/login.php');
}
include "../CONEXION/conexion.php";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador | Sistema de Registro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/styles_admin.css">
</head>

<body>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="page-title">Panel de Administrador</div>
            <div class="user-info">
                <div class="user-details">
                    <div class="user-name"><?= $_SESSION["nombre_u"] . " " . $_SESSION["apellido_u"] ?></div>
                    <div class="user-role">Administrador</div>
                </div>
                <div class="user-avatar">
                    <?= substr($_SESSION["nombre_u"], 0, 1) . substr($_SESSION["apellido_u"], 0, 1) ?>
                </div>
                <button class="logout-btn" onclick="window.location.href='../CONTROLADORES/cerrar_sesion.php';">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Dashboard Cards -->
            <div class="card-container">
                <div class="card">
                    <div class="card-icon total">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-info">
                        <?php
                        $cons = $conexion->query("SELECT COUNT(*) AS total_alumnos FROM alumno;");
                        $al = $cons->fetch_object();
                        ?>
                        <h3><?= $al->total_alumnos ?></h3>
                        <p>Alumnos Registrados</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="btn btn-primary" id="add-student-btn" onclick="window.location.href='registro_alumnos.php';">
                    <i class="fas fa-plus"></i> Agregar Alumno
                </button>
            </div>

            <!-- Students Table -->
            <div class="table-container">
                <div class="table-header">
                    <div class="table-title">Lista de Alumnos</div>
                </div>
                <?php
                include "../CONTROLADORES/eliminar_alumno.php";
                $sql = $conexion->query("SELECT * FROM alumno");
                ?>
                <table>
                    <thead>
                        <tr>
                            <th>No. ctrl</th>
                            <th>Nombre</th>
                            <th>Carrera</th>
                            <th>Semestre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include "../CONEXION/conexion.php";
                        include "../CONTROLADORES/modificar_alumno.php";
                        while ($datos = $sql->fetch_object()) {

                        ?>
                            <tr>
                                <td><?= $datos->numero_control ?></td>
                                <td><?= $datos->nombre . " " . $datos->apellido ?></td>
                                <td><?= $datos->carrera ?></td>
                                <td><?= $datos->semestre ?></td>
                                <td class="actions">
                                    <a href="mod_alumnos.php?numero_control=<?= $datos->numero_control ?>" class="action-btn edit-btn" id="abrirMod"><i class="fas fa-edit"></i></a>
                                    <a href="panel_admin.php?numero_control=<?= $datos->numero_control ?>" onclick="return advertencia(event)" class="action-btn delete-btn"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>


</body>
<script>
    function advertencia() {
        var not = confirm("Deseas eliminar el registro??");
        return not;
    }
</script>

</html>