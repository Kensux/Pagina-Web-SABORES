<?php  
// Datos de conexión  
$servidor = "localhost";  
$username = "root";  
$password = "";   
$base_datos = "sabores";  

// Crear conexión  
$conn = mysqli_connect($servidor, $username, $password, $base_datos);  
// Comprobar conexión  
if (!$conn) {  
    die("Conexión fallida: " . mysqli_connect_error());  
}  

// Obtener datos del formulario de forma segura  
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';  
$apellido = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';  
$email = isset($_POST['email']) ? trim($_POST['email']) : '';  
$password = isset($_POST['password']) ? trim($_POST['password']) : '';  

if (!empty($nombre) && !empty($apellido) && !empty($email) && !empty($password)) {  
    // Validar si el correo ya existe en la base de datos  
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");  
    $stmt->bind_param("s", $email);  
    $stmt->execute();  
    $result = $stmt->get_result();  

    if ($result->num_rows > 0) {  
echo "El correo ya está en uso. <a href='Registro.html'>Intenta nuevamente</a>";  
    } 
    else {  
        // Hashear la contraseña antes de guardarla  
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);  
    
        // Insertar nuevo usuario  
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, email, password) VALUES (?, ?, ?, ?)");  
        $stmt->bind_param("ssss", $nombre, $apellido, $email, $password_hashed);  

        if ($stmt->execute()) {   
            header("Location: index.html");  
            exit();   
        } else {  
            echo "Error:" . $stmt->error;  
        }  
    }  

    // Cerrar declaración y conexión  
    $stmt->close();  
} else {  
    echo "Todos los campos son obligatorios. <a href='registro.html'>Intenta nuevamente</a>.";  
}  
$conn->close();  
