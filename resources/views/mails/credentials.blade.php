<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de sufragio SUDUNT - Registro de usuario</title>
</head>
<body>
    <h1>SISTEMA DE SUFRAGIO - SUDUNT</h1>
    <p>Saludos {{$names}}, usted ha sido registrado(a) en el sistema de sufragio SUDUNT a continuación se muestran sus credenciales de acceso.</p>
    <p style="color: tomato;"> Estos datos son privados, en caso de no poder acceder, porfavor comunicarse con el área técnica de SUDUNT</p>
    <ul>
        <li>Sistema de sufragio:   <a href="www.google.com">www.sudunt.com</a></li>
        <li>Email: {{$email}}</li>
        <li>Contraseña: {{$pass}}</li>
    </ul>
</body>
</html>