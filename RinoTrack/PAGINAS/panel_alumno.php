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
    <link rel="stylesheet" href="CSS/styles_alumno.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Estilos generales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary: #9F2241;
            --secondary: #BF2C4D;
            --accent: #D93D5D;
            --danger: #C12C3A;
            --light: #f8f9fa;
            --dark: #343a40;
            --gray: #6c757d;
            --border: #dee2e6;
            --topbar-height: 80px;
            --card-spacing: 25px;
            --element-spacing: 15px;
        }

        body {
            background-color: #f5f7fb;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Topbar */
        .topbar {
            background: white;
            padding: 0 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
            min-height: var(--topbar-height);
            text-align: center;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            cursor: pointer;
        }

        .user-details {
            text-align: center;
        }

        .user-name {
            font-weight: 600;
            color: var(--dark);
        }

        .user-role {
            font-size: 13px;
            color: var(--gray);
        }

        .logout-container {
            display: none;
            position: absolute;
            top: 70px;
            right: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            z-index: 100;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .menu-item:hover {
            background: #f5f5f5;
        }

        .menu-text {
            font-size: 14px;
        }

        /* Main Content */
        .main-content {
            padding-top: calc(var(--topbar-height) + 20px);
        }

        .content {
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Tarjeta de información personal  */
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: var(--card-spacing);
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 25px;
            transition: transform 0.3s;
            border-left: 4px solid var(--primary);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(159, 34, 65, 0.15);
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-right: 15px;
            background: rgba(159, 34, 65, 0.1);
            color: var(--primary);
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark);
        }

        /* Contenido de la tarjeta */
        .student-info {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--element-spacing);
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 0;
        }

        .info-item i {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            background: rgba(159, 34, 65, 0.08);
            color: var(--primary);
        }

        .info-text h4 {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--gray);
            margin-bottom: 4px;
        }

        .info-text p {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
        }

        /* Tabla de registros*/
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border-top: 3px solid var(--primary);
        }

        .table-header {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
        }

        .table-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary);
        }

        .hours-total {
            font-weight: 600;
            font-size: 1rem;
            background: rgba(159, 34, 65, 0.1);
            color: var(--primary);
            padding: 8px 15px;
            border-radius: 20px;
        }

        .report-actions {
            display: flex;
            gap: 15px;
        }

        .report-btn {
            padding: 10px 20px;
            background: var(--primary);
            color: white;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .report-btn:hover {
            background: var(--secondary);
            box-shadow: 0 5px 15px rgba(159, 34, 65, 0.3);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #f8f9fa;
        }

        th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            font-size: 0.95rem;
            border-bottom: 2px solid var(--border);
        }

        td {
            padding: 15px 20px;
            color: var(--dark);
            border-bottom: 1px solid var(--border);
            font-size: 0.95rem;
        }

        .actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            color: white;
        }

        .delete-btn {
            background: rgba(193, 44, 58, 0.15);
            color: var(--danger);
        }

        .delete-btn:hover {
            background: var(--danger);
            color: white;
        }

        /* Responsive*/
        @media (max-width: 768px) {
            .topbar {
                padding: 15px;
            }
            
            .user-info {
                flex-direction: column;
                gap: 10px;
            }
            
            .content {
                padding: 15px;
            }
            
            .card-container {
                grid-template-columns: 1fr;
            }
            
            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .report-actions {
                align-self: stretch;
                justify-content: center;
            }
            
            .hours-total {
                align-self: center;
            }
            
            table, thead, tbody, th, td, tr {
                display: block;
            }
            
            thead {
                display: none;
            }
            
            tr {
                margin-bottom: 15px;
                border: 1px solid var(--border);
                border-radius: 8px;
                padding: 10px;
                background: white;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            }
            
            td {
                padding: 8px 10px;
                border: none;
                position: relative;
                padding-left: 45%;
            }
            
            td:before {
                position: absolute;
                left: 10px;
                width: 40%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: 600;
                content: attr(data-label);
            }
            
            .actions {
                justify-content: center;
                margin-top: 10px;
            }
            
            .report-btn {
                padding: 8px 12px;
                font-size: 13px;
            }
            
            .action-btn {
                width: 28px;
                height: 28px;
            }
        }

        /* Mejoras específicas para escritorio */
        @media (min-width: 992px) {
            .student-info {
                grid-template-columns: 1fr 1fr;
                gap: 25px;
            }
            
            .info-item {
                padding: 15px;
                border-radius: 8px;
                background: rgba(159, 34, 65, 0.03);
                transition: transform 0.2s;
            }
            
            .info-item:hover {
                transform: translateX(5px);
                background: rgba(159, 34, 65, 0.06);
            }
            
            th, td {
                padding: 16px 24px;
            }
            
            .card {
                padding: 30px;
            }
        }

        /* Optimización para pantallas grandes */
        @media (min-width: 1200px) {
            .content {
                padding: 40px 50px;
            }
            
            .card-container {
                grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            }
        }
    </style>
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
