<?php
    require "conexion.php";
    session_start();

    $error = ""; // Variable para almacenar los mensajes de error

    if ($_POST) {
        $usuario = $_POST['usuario'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM usuarios WHERE usuario='$usuario'";
        $resultado = $mysqli->query($sql);
        $num = $resultado->num_rows;

        if ($num > 0) {
            $row = $resultado->fetch_assoc();
            $password_bd = $row['password'];

            $pass_c = sha1($password);

            if ($password_bd == $pass_c) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['nombre'] = $row['nombre'];
                $_SESSION['tipo_usuario'] = $row['tipo_usuario'];
                header("Location: principal.php");
            } else {
                $error = "CONTRASEÑA O USUARIO INCORRECTO"; // Mensaje de error si la contraseña no es correcta
            }
        } else {
            $error = "NO EXISTE USUARIO"; // Mensaje de error si no existe el usuario
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Sesión</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">INICIAR SESIÓN</h3>
                                </div>
                                <div class="card-body">
                                    <!-- Mostrar el mensaje de error en rojo si existe -->
                                    <?php if ($error != "") : ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
                                    <?php endif; ?>
                                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputEmailAddress">Usuario</label>
                                            <input class="form-control py-4" id="inputEmailAddress" name="usuario" type="text"
                                                placeholder="Ingrese usuario" />
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputPassword">Contraseña</label>
                                            <div class="input-group">
                                                <input class="form-control py-4" id="inputPassword" name="password" type="password"
                                                    placeholder="Ingrese contraseña" />
                                                <div class="input-group-append">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" id="rememberPasswordCheck" type="checkbox" />
                                                <label class="custom-control-label" for="rememberPasswordCheck">Guardar contraseña</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">INICIO</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>
</html>