<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/styles.css">
    <title>Sistema de Registro | Entradas y Salidas</title>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <div class="logo-icon">R</div>
                <div>
                    <h1>Registro de Asistencia</h1>
                    <div class="subtitle">Sistema de Entradas y Salidas</div>
                    <?php
                    include "CONEXION/conexion.php";
                    include "CONTROLADORES/registro_entrada_salida.php";
                    ?>
                </div>
            </div>
        </header>
        
        <main>
            <form id="registro-form" method="POST">
                <div class="form-group">
                    <label for="hora-fecha" id="fecha"></label>
                    <label for="numero-control">Número de Control:</label>
                    <input type="text" id="numero-control" name="noctrol" placeholder="Ingrese su número de control" required>
                </div>
                
                <div class="buttons">
                    <button class="btn btn-entrada" name="btn_entr" value="ok">Registrar Entrada</button>
                    <button class="btn btn-salida" name="btn_sal" value="ok">Registrar Salida</button>
                </div>
                
                <!-- notificacion -->
                <div class="status" id="status-message">
                    <p>Registro exitoso: Entrada registrada a las </p>
                </div>
            </form>
            
            <div class="admin-login">
                <a href="LOGIN/login.php">Acceso</a>
            </div>
        </main>
        
        <footer>
            <p>Sistema de Registro de Entradas y Salidas de Servicio Social &copy; 2025</p>
        </footer>
    </div>

<script>
    setInterval(()=>{
        let fecha= new Date();
        let fechahora=fecha.toLocaleString();
        document.getElementById("fecha").textContent=fechahora;
    },1000);
</script>
</body>
</html>