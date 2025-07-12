<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (empty($_SESSION['nombre_u']) and empty($_SESSION['apellido_u'])) {
    header('Location: ../LOGIN/login.php');
    include '../CONEXION/conexion.php';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Alumno | Sistema de Registro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/styles_alumno.css">
</head>

<body>
    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-content">
                <div class="page-title">Panel de Alumno</div>
                <div class="user-info">
                    <div class="user-details">
                        <div class="user-name"><?= $_SESSION["nombre_u"] . " " . $_SESSION["apellido_u"] ?></div>
                        <div class="user-role">Alumno</div>
                    </div>
                    <div class="user-avatar" onclick="toggleLogoutMenu()">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="logout-container" id="logoutMenu">
                        <div class="menu-item" onclick="window.location.href='../CONTROLADORES/cerrar_sesion.php';">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="menu-text">Cerrar Sesión</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Dashboard Cards -->
            <div class="card-container">
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="card-title">Información Personal</div>
                    </div>
                    <div class="student-info">
                        <div class="info-item">
                            <i class="fas fa-id-card"></i>
                            <div class="info-text">
                                <h4>Número de Control</h4>
                                <p><?= $_SESSION["numero_control"] ?></p>
                            </div>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-graduation-cap"></i>
                            <div class="info-text">
                                <h4>Carrera</h4>
                                <?php
                                include '../CONEXION/conexion.php';

                                $nctrl = $_SESSION["numero_control"];
                                $con = $conexion->query("SELECT carrera FROM alumno 
                                WHERE numero_control = '$nctrl';");

                                $carrera = $con->fetch_object();
                                ?>
                                <p><?= $carrera->carrera ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- History Table -->
            <div class="table-container">
                <div class="table-header">
                    <?php
                    include "../CONEXION/conexion.php";
                    include "../CONTROLADORES/eliminar_asistencia.php";
                    $num_ctrl = $_SESSION["numero_control"];
                    $sql_total = $conexion->query("SELECT 
            SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, entrada, salida))) AS total_acumulado
        FROM registro_horas
        WHERE numero_control = '$num_ctrl'");
                    $total_row = $sql_total->fetch_object();
                    $total_acumulado = $total_row->total_acumulado ?: '00:00:00';
                    $sql = $conexion->query("SELECT 
            r.id_registro,
            a.nombre,
            a.apellido,
            DATE(r.entrada) AS fecha,
            TIME(r.entrada) AS hora_entrada,
            TIME(r.salida) AS hora_salida,
            IFNULL(SEC_TO_TIME(TIMESTAMPDIFF(SECOND, r.entrada, r.salida)), '00:00:00') AS horas_estancia
        FROM registro_horas r
        JOIN alumno a ON r.numero_control = a.numero_control
        WHERE r.numero_control = '$num_ctrl'
        ORDER BY r.entrada DESC");
                    ?>
                    <div class="table-title">Historial de Registros</div>
                    <div class="hours-total">Horas acumuladas: <?= $total_acumulado ?></div>
                    <div class="report-actions">
                        <button class="report-btn" onclick="window.location.href='reportes.php'">
                            <i class="fas fa-file-pdf"></i> Generar PDF
                        </button>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Fecha</th>
                            <th>Hora Entrada</th>
                            <th>Hora Salida</th>
                            <th>Total horas</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($datos = $sql->fetch_object()) { ?>
                            <tr>
                                <td data-label="ID"><?= $datos->id_registro ?></td>
                                <td data-label="Nombre"><?= $datos->nombre . " " . $datos->apellido ?></td>
                                <td data-label="Fecha"><?= $datos->fecha ?></td>
                                <td data-label="Hora Entrada"><?= $datos->hora_entrada ?></td>
                                <td data-label="Hora Salida"><?= $datos->hora_salida ?></td>
                                <td data-label="Total horas"><?= $datos->horas_estancia ?></td>
                                <td class="actions">
                                    <a href="panel_alumno.php?id_registro=<?= $datos->id_registro ?>"
                                        onclick="return advertencia()"
                                        class="action-btn delete-btn">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        function advertencia() {
            var not = confirm("¿Deseas eliminar el registro?");
            return not;
        }
        
        function toggleLogoutMenu() {
            const menu = document.getElementById('logoutMenu');
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        }
        
        // Cerrar el menú al hacer clic fuera de él
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('logoutMenu');
            const avatar = document.querySelector('.user-avatar');
            const isClickInsideMenu = menu.contains(event.target);
            const isClickInsideAvatar = avatar.contains(event.target);
            
            if (!isClickInsideMenu && !isClickInsideAvatar) {
                menu.style.display = 'none';
            }
        });
    </script>
</body>

</html>