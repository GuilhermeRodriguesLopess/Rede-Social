
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/denunciar.css">
    <title>Denunciar Receita</title>
</head>
    <h1>Denunciar Receita</h1>

    <?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $receita_id = $_POST["receita_id"];

        // se estiver logado, mostrar:
        if (isset($_SESSION["usuario_id"])) {
            echo '<form method="post" action="processar_denuncia.php">';
            echo '<input type="hidden" name="receita_id" value="' . $receita_id . '">';
            echo '<label for="tipo">Tipo:</label>';
            echo '<input type="text" name="tipo" id="tipo" required><br>';
            echo '<label for="descricao">Descrição:</label>';
            echo '<textarea name="descricao" id="descricao" required></textarea><br>';
            echo '<input type="submit" value="Denunciar">';
            echo '</form>';
        } else {
            // se nao tiver logado, vai ser mandado para a tela de login
            header("Location: login.html");
            exit;
        }
    } else {
        echo "Erro: Método de requisição inválido.";
    }
    ?>

</body>

</html>
