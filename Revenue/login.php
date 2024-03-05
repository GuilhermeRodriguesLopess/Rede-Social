<?php
// Inicie uma sessão
session_start();

// Verifique se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Conecte-se ao banco de dados (substitua com suas credenciais)
    $mysqli = new mysqli("localhost", "root", "", "tcc");

    if ($mysqli->connect_error) {
        die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
    }

    // Consulta SQL para verificar as credenciais
    $sql = "SELECT usuario_id, nome_usuario FROM usuarios WHERE nome_usuario = ? AND senha = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Login bem-sucedido
        $row = $result->fetch_assoc();
            $_SESSION["username"] = $username;
            $_SESSION["usuario_id"] = $row["usuario_id"]; // Utilize a chave "user_id" na sessão
        header("Location: menu_receitas.php");
        exit();
    } else {
        echo "Credenciais inválidas. Tente novamente.";
    }

    $stmt->close();
    $mysqli->close();
}
?>