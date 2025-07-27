<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Sistema | Registro de Entradas y Salidas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles_login.css">
</head>

<body>

    <div class="login-container">
        <form action="" method="POST">
            <div class="login-header">
                <div class="logo">
                    <div class="logo-icon"></div>
                    <div>
                        <h1>Acceso al Sistema</h1>
                        <div class="subtitle">Ingrese sus credenciales para continuar</div>
                    </div>

                </div>
            </div>

            <div class="login-body">
                <?php
                include "../CONEXION/conexion.php";
                include "../CONTROLADORES/login.php";
                ?>
                <!--usuario-->
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" placeholder="Usuario o Número de Control" name="usuario">
                </div>
                <!--usuario-->
                <!--contraseña-->
                <div class="input-group password-group">
                    <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                    <input type="password" id="password" placeholder="Contraseña" name="password">
                    
                </div>
                <!--contraseña-->
                <!--boton de ingreso-->
                <button class="login-btn" name="btn_ingresar" type="submit" value="INICIAR SESION">Iniciar Sesión</button>
                <!--boton de ingreso-->
                <div class="separator"></div>

                <div class="back-to-main">
                    <a href="../index.php"><i class="fas fa-arrow-left"></i> Volver a la página principal</a>
                </div>
            </div>

            <div class="login-footer">
                <p>Sistema de Registro de Entradas y Salidas &copy; 2025</p>
            </div>
        </form>
    </div>
    <script>
        // Mostrar/ocultar contraseña
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>
