<?php
session_start();

$mysqli = new mysqli("localhost", "root", "", "tcc");

if ($mysqli->connect_error) {
    die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
}

// Verifique se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupere os dados do formulário
    $nome_usuario = $_POST["nome_usuario"];
    $email = $_POST["email"];
    $descricao_perfil = $_POST["descricao_perfil"];

    // Atualize as informações do perfil no banco de dados
    $sql_update = "UPDATE Usuarios SET nome_usuario = ?, email = ?, descricao_perfil = ? WHERE usuario_id = ?";
    $stmt_update = $mysqli->prepare($sql_update);
    $stmt_update->bind_param("sssi", $nome_usuario, $email, $descricao_perfil, $_SESSION['usuario_id']);

    // Execute a atualização
    $stmt_update->execute();

    // Redirecione para o perfil após a edição
    header("location: perfil.php");
    exit;
}

// Feche a conexão ao final do script
$mysqli->close();
?>
