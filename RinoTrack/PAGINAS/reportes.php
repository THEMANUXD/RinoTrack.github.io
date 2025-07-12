<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (empty($_SESSION['nombre_u']) and empty($_SESSION['apellido_u'])) {
    header('Location: ../LOGIN/login.php');
    include '../CONEXION/conexion.php';
}

// Obtener el mes seleccionado (si existe)
$mes_seleccionado = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');
$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Reporte Mensual | Sistema de Registro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary: #1a2a6c;
            --secondary: #2c3e9d;
            --accent: #28a745;
            --light: #f8f9fa;
            --dark: #343a40;
            --gray: #6c757d;
            --border: #dee2e6;
            --success-light: rgba(40, 167, 69, 0.15);
            --danger-light: rgba(220, 53, 69, 0.15);
            --shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        body {
            background: linear-gradient(135deg, #f5f7fb, #e6e9ff);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .report-container {
            width: 100%;
            max-width: 1000px;
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        /* Header */
        .report-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo-icon {
            background: white;
            color: var(--primary);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            margin-right: 15px;
        }

        .header-text h1 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .header-text p {
            opacity: 0.9;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(-3px);
        }

        /* Report Controls */
        .controls-section {
            padding: 25px 30px;
            background: var(--light);
            border-bottom: 1px solid var(--border);
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .period-selector {
            flex: 1;
            min-width: 300px;
        }

        .selector-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark);
        }

        .period-options {
            display: flex;
            gap: 15px;
        }

        .month-select,
        .year-select {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 16px;
            background: white;
            cursor: pointer;
        }

        .generate-btn {
            background: var(--accent);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            height: 48px;
            align-self: flex-end;
        }

        .generate-btn:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        /* Report Preview */
        .preview-section {
            padding: 30px;
        }

        .preview-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 25px;
            color: var(--dark);
            text-align: center;
        }

        .student-info {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .info-card {
            background: var(--light);
            border-radius: 10px;
            padding: 20px;
            min-width: 200px;
            text-align: center;
            box-shadow: var(--shadow);
        }

        .info-card h3 {
            font-size: 1.1rem;
            color: var(--gray);
            margin-bottom: 8px;
        }

        .info-card p {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary);
        }

        .period-info {
            background: linear-gradient(135deg, #1a2a6c, #2c3e9d);
            color: white;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
        }

        .period-info h2 {
            font-size: 1.4rem;
            margin-bottom: 5px;
        }

        .period-info p {
            opacity: 0.9;
        }

        /* Stats Section */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: var(--shadow);
        }

        .stat-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-right: 15px;
        }

        .stat-icon.entradas {
            background: var(--success-light);
            color: var(--accent);
        }

        .stat-icon.horas {
            background: rgba(255, 193, 7, 0.15);
            color: #ffc107;
        }

        .stat-icon.promedio {
            background: rgba(13, 110, 253, 0.15);
            color: #0d6efd;
        }

        .stat-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
        }

        .stat-value {
            font-size: 2.2rem;
            font-weight: 700;
            text-align: center;
            color: var(--primary);
            margin: 20px 0;
        }

        .stat-description {
            text-align: center;
            color: var(--gray);
            font-size: 14px;
        }

        /* Report Table */
        .report-table {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table-header {
            padding: 20px;
            background: var(--light);
            border-bottom: 1px solid var(--border);
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: var(--light);
        }

        th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            font-size: 14px;
        }

        td {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border);
            color: var(--dark);
            font-size: 14px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background: rgba(26, 42, 108, 0.02);
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge.entrada {
            background: var(--success-light);
            color: var(--accent);
        }

        .status-badge.salida {
            background: var(--danger-light);
            color: #dc3545;
        }

        /* Chart Section */
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }

        .chart-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--dark);
            text-align: center;
        }

        .chart-wrapper {
            height: 300px;
            position: relative;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 20px;
            background: var(--light);
            border-top: 1px solid var(--border);
        }

        .action-btn {
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            border: none;
            font-size: 16px;
        }

        .pdf-btn {
            background: #dc3545;
            color: white;
        }

        .pdf-btn:hover {
            background: #c82333;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(220, 53, 69, 0.3);
        }

        .excel-btn {
            background: #28a745;
            color: white;
        }

        .excel-btn:hover {
            background: #218838;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
        }

        .print-btn {
            background: var(--primary);
            color: white;
        }

        .print-btn:hover {
            background: #15205e;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(26, 42, 108, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .report-header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .logo {
                justify-content: center;
            }

            .period-options {
                flex-direction: column;
            }

            .action-buttons {
                flex-direction: column;
            }

            .stat-value {
                font-size: 1.8rem;
            }

            .student-info {
                gap: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="report-container">
        <div class="report-header">
            <div class="logo">
                <div class="logo-icon">R</div>
                <div class="header-text">
                    <h1>Reporte Mensual de Asistencia</h1>
                    <p>Sistema de Registro de Entradas y Salidas</p>
                </div>
            </div>
            <button class="back-btn" onclick="history.back()">
                <i class="fas fa-arrow-left"></i> Volver al Panel
            </button>
        </div>

        <form method="GET" action="reportes.php">
            <div class="controls-section">
                <div class="period-selector">
                    <div class="selector-title">Seleccionar Período:</div>
                    <div class="period-options">
                        <select class="month-select" id="month-select" name="mes">
                            <?php
                            foreach ($meses as $num => $nombre) {
                                $selected = ($num == $mes_seleccionado) ? 'selected' : '';
                                echo "<option value=\"$num\" $selected>$nombre</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" class="generate-btn">
                            <i class="fas fa-filter"></i> Filtrar Registros
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div class="preview-section">
            <h2 class="preview-title">Previsualización del Reporte</h2>

            <div class="student-info">
                <div class="info-card">
                    <h3>Nombre del Alumno</h3>
                    <p><?= $_SESSION["nombre_u"] . " " . $_SESSION["apellido_u"] ?></p>
                </div>

                <div class="info-card">
                    <h3>Número de Control</h3>
                    <p><?= $_SESSION["numero_control"] ?></p>
                </div>

                <div class="info-card">
                    <h3>Carrera</h3>
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

            <div class="period-info">
                <h2>Reporte de Asistencia</h2>
                <?php
                include "../CONEXION/conexion.php";
                $num_ctrl = $_SESSION["numero_control"];
                $sql = $conexion->query("SELECT 
    r.id_registro,
    a.nombre,
    a.apellido,
    DATE(r.entrada) AS fecha,
    TIME(r.entrada) AS hora_entrada,
    TIME(r.salida) AS hora_salida,
    SEC_TO_TIME(TIMESTAMPDIFF(SECOND, r.entrada, r.salida)) AS horas_estancia,
    SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, r.entrada, r.salida)) 
        OVER (PARTITION BY MONTH(r.entrada))) AS total_mes
FROM 
    registro_horas r
JOIN 
    alumno a ON r.numero_control = a.numero_control
WHERE 
    r.numero_control = '$num_ctrl'
    AND MONTH(r.entrada) = $mes_seleccionado
    AND YEAR(r.entrada) = YEAR(CURDATE())
ORDER BY 
    r.entrada DESC;");
                ?>
            </div>

            <div class="report-table">
                <div class="table-header">Registros Diarios - <?= $meses[$mes_seleccionado] . ' ' . date('Y') ?></div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Fecha</th>
                            <th>Hora Entrada</th>
                            <th>Hora Salida</th>
                            <th>Total horas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($sql->num_rows > 0) {
                            while ($datos = $sql->fetch_object()) { ?>
                                <tr>
                                    <td><?= $datos->id_registro ?></td>
                                    <td><?= $datos->nombre . " " . $datos->apellido ?></td>
                                    <td><?= $datos->fecha ?></td>
                                    <td><?= $datos->hora_entrada ?></td>
                                    <td><?= $datos->hora_salida ?></td>
                                    <td><?= $datos->horas_estancia ?></td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 20px;">
                                    No hay registros para el mes de <?= $meses[$mes_seleccionado] ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>

        <div class="action-buttons">
            <button class="action-btn pdf-btn" onclick="window.location.href='../FDPF/ReportePDF.php?mes=<?= $mes_seleccionado ?>';">
                <i class="fas fa-file-pdf"></i> Descargar PDF
            </button>
        </div>
    </div>

    <script>
        // Cambio automático del formulario al seleccionar un mes
        document.getElementById('month-select').addEventListener('change', function() {
            this.form.submit();
        });
    </script>
</body>

</html>