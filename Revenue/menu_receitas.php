<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    
    <title>Feed de Receitas</title>
    <link rel="stylesheet" href="css/menu.css">
    <?Php
    session_start();
    ?>
    
</head>

<body>
    <header>
            <img src="img/logo.png" alt="Logo">
        <div class="btn-container">
            <a href="perfil.php" class="btn">Perfil</a>
            <a href="receitas.html" class="btn">Postar Receitas</a>
        </div>
    </header>
    <div class="container">
        <h1>Feed de Receitas</h1>
        <?php


        $mysqli = new mysqli("localhost", "root", "", "tcc");

        if ($mysqli->connect_error) {
            die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
        }

        $sql = "SELECT Receitas.*, Usuarios.nome_usuario AS nome_usuario, COUNT(Curtidas.receita_id) AS num_curtidas
                FROM Receitas 
                JOIN Usuarios ON Receitas.user_id = Usuarios.usuario_id
                LEFT JOIN Curtidas ON Receitas.receita_id = Curtidas.receita_id
                GROUP BY Receitas.receita_id";
        $result = $mysqli->query($sql);

        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="receita">';
                    echo '<a class="detalhe" href="detalhes.php?receita_id=' . $row["receita_id"] . '"><h2 class="titulo-receita">' . $row["titulo"] . '</h2></a>';
                    echo '<p>Postado por: <a class="detalhe" href="perfil_usuario.php? user_id=' . $row["user_id"] . '">' . $row["nome_usuario"] . '</a></p>';
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row["foto_receita"]) . '" alt="' . $row["titulo"] . '">';

                    // Verificar se o usuário já curtiu a receita
                    $receitaId = $row["receita_id"];

                    if (isset($_SESSION["usuario_id"])) {
                        $usuarioId = $_SESSION["usuario_id"];
                        $verificarCurtidaSql = "SELECT * FROM Curtidas WHERE receita_id = $receitaId AND usuario_id = $usuarioId";
                        $verificarCurtidaResult = $mysqli->query($verificarCurtidaSql);

                        if ($verificarCurtidaResult) {
                            if ($verificarCurtidaResult->num_rows == 0) {
                                // Exibir botão de curtir e número de curtidas

                                // Botão de Curtir
                                echo '<form method="post" action="curtir.php" class="receita-form">';
                                echo '<input type="hidden" name="receita_id" value="' . $row["receita_id"] . '">';
                                echo '<button type="submit" class="btn-curtir">Curtir</button>';
                                echo '<span>' . $row["num_curtidas"] . ' Curtidas</span>';
                                echo '</form>';
                            } else {
                                // Usuário já curtiu, exibir mensagem ou outro indicador
                                echo '<p>Você já curtiu esta receita</p>';
                                echo '<span>' . $row["num_curtidas"] . ' Curtidas</span>';
                            }
                        } else {
                            // Tratar o erro na consulta SQL 
                            echo 'Erro na consulta SQL: ' . $mysqli->error;
                        }
                    } else {
                        // Usuário não está autenticado, redirecionar para a tela de login
                        echo '<form action="login.html" method="get">';
                        echo '<button type="submit" class="btn-curtir">Curtir</button>';
                        echo '<span>' . $row["num_curtidas"] . ' Curtidas</span>';
                        echo '</form>';
                    }


                    // Botão de Denúncia
                  
                    // Botão de Denúncia
                    echo '<form method="post" action="denunciar.php" class="receita-form">';
                    echo '<input type="hidden" name="receita_id" value="' . $row["receita_id"] . '">';
                    echo '<input type="submit" value="Denunciar" class="btn-denunciar">';
                    echo '</form>';
                    
                    // Botão de Favorito
                    echo '<form method="post" action="favoritar.php" class="receita-form">';
                    echo '<input type="hidden" name="receita_id" value="' . $row["receita_id"] . '">';
                    echo '<button type="submit" class="btn-favorito">Favorito</button>';
                    echo '</form>';
                    
                    echo '</div>';
                    

                }
            } else {
                echo "Nenhuma receita encontrada.";
            }
        } else {
            // Tratar erro na consulta principal
            echo 'Erro na consulta principal: ' . $mysqli->error;
        }

        $mysqli->close();
        ?>
    </div>
</body>

</html>