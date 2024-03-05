<?php
session_start();

$mysqli = new mysqli("localhost", "root", "", "tcc");

if ($mysqli->connect_error) {
    die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['usuario_id'])) {
        $user_id = $_SESSION['usuario_id'];

        $check_user_query = $mysqli->prepare("SELECT usuario_id FROM Usuarios WHERE usuario_id = ?");
        $check_user_query->bind_param("i", $user_id);
        $check_user_query->execute();
        $check_user_query->store_result();

        if ($check_user_query->num_rows > 0) {
            $titulo = $_POST['titulo'];
            $descricao = $_POST['descricao'];
            $ingredientes = $_POST['ingredientes'];
            $passos_preparacao = $_POST['passos_preparacao'];
            $tempo_preparacao = $_POST['tempo_preparacao'];
            $categoria = $_POST['categoria'];
            $dificuldade = $_POST['dificuldade'];
            $num_porcoes = $_POST['num_porcoes'];

            $foto_receita_content = file_get_contents($_FILES['foto_receita']['tmp_name']);

            $stmt = $mysqli->prepare("INSERT INTO Receitas (titulo, descricao, foto_receita, ingredientes, passos_preparacao, tempo_preparacao, categoria, dificuldade, num_porcoes, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssbssisssi", $titulo, $descricao, $null, $ingredientes, $passos_preparacao, $tempo_preparacao, $categoria, $dificuldade, $num_porcoes, $user_id);




            $null = null;
            $stmt->send_long_data(2, $foto_receita_content);

            if ($stmt->execute()) {
                echo "Receita registrada com sucesso!";
                header("location: menu_receitas.php");
            } else {
                echo "Erro ao registrar a receita: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Erro: user_id inválido!";
        }

        $check_user_query->close();
    } else {
        header("Location: login.html");
    }

    $mysqli->close();
}
?>
