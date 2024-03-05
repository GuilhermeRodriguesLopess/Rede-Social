<?php
session_start();

if (!isset($_SESSION["usuario_id"]) || !isset($_POST["receita_id"])) {
    header('location: login.html');
    exit();
}

$usuarioId = $_SESSION["usuario_id"];
$receitaId = $_POST["receita_id"];

// Conexão com o banco de dados
$mysqli = new mysqli("localhost", "root", "", "tcc");
if ($mysqli->connect_error) {
    die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
}

// Verificar se a receita já está nos favoritos do usuário
$verificarFavoritoSql = "SELECT 1 FROM Favoritos WHERE usuario_id = ? AND receita_id = ?";
$verificarFavoritoStmt = $mysqli->prepare($verificarFavoritoSql);
$verificarFavoritoStmt->bind_param("ii", $usuarioId, $receitaId);
$verificarFavoritoStmt->execute();
$verificarFavoritoResult = $verificarFavoritoStmt->get_result();

if ($verificarFavoritoResult !== false) {
    if ($verificarFavoritoResult->num_rows == 0) {
        // A receita ainda não está nos favoritos do usuário, pode ser favoritada

        $inserirFavoritoSql = "INSERT INTO Favoritos (usuario_id, receita_id) VALUES (?, ?)";
        $inserirFavoritoStmt = $mysqli->prepare($inserirFavoritoSql);
        $inserirFavoritoStmt->bind_param("ii", $usuarioId, $receitaId);

        if ($inserirFavoritoStmt->execute()) {
            echo 'Receita adicionada aos favoritos com sucesso!';
        } else {
            echo 'Erro ao adicionar a receita aos favoritos: ' . $inserirFavoritoStmt->error;
        }

        $inserirFavoritoStmt->close();
    } else {
        echo 'Erro: Esta receita já está nos seus favoritos.';
    }
} else {
    echo 'Erro na verificação de favoritos: ' . $verificarFavoritoStmt->error;
}

$verificarFavoritoStmt->close();
$mysqli->close();
?>
