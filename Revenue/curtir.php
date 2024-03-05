<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $receita_id = $_POST["receita_id"];
    
    if (isset($_SESSION["usuario_id"])) {
        // Se o ID do usuário estiver definido na sessão, você pode acessá-lo.
        $usuario_id = $_SESSION["usuario_id"];
        //conexão 
        $mysqli = new mysqli("localhost", "root", "", "tcc");

        if ($mysqli->connect_error) {
            die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
        }

        $sql = "INSERT INTO Curtidas (receita_id, usuario_id) VALUES (?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ii", $receita_id, $usuario_id);

        if ($stmt->execute()) {
            // Redirecione para o menu de receitas se der certo
            header("Location: menu_receitas.php");
            exit;
        } else {
            echo "Erro ao curtir a receita: " . $mysqli->error;
        }
         
        $stmt->close();
        $mysqli->close();
    } else {
        // se nao tiver logado, vai ser mandado para a tela de login
        echo "Usuário não autenticado. Redirecionando para a página de login...";
        header("Location: login.html");
        exit;
    }
}
?>
