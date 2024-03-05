<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "tcc");

if ($mysqli->connect_error) {
    die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
}

if (isset($_GET['user_id'])) {
    $perfil_usuario_id = $_GET['user_id'];

    // Consulta informações do usuário
    $sql_usuario = "SELECT * FROM Usuarios WHERE usuario_id = ?";
    $stmt_usuario = $mysqli->prepare($sql_usuario);
    $stmt_usuario->bind_param("i", $perfil_usuario_id);

    try {
        $stmt_usuario->execute();
        $result_usuario = $stmt_usuario->get_result();

        if ($result_usuario) {
            $row_usuario = $result_usuario->fetch_assoc();

            if ($row_usuario) {
?>
                <!DOCTYPE html>
                <html lang="pt-br">

                <head>
                    <meta charset="UTF-8">
                    <title>Perfil de <?php echo htmlspecialchars($row_usuario['nome_usuario']); ?></title><link rel="stylesheet" href="css/perfil.css">
                </head>
                

                <body>
                    <div class="container">

                        <header>
                            <h1>Perfil de <?php echo htmlspecialchars($row_usuario['nome_usuario']); ?></h1>
                        </header>

                        <div class="tab-container">
                            <div class="tab" onclick="openTab('receitas')">Receitas</div>
                        </div>

                        <div class="profile">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row_usuario['foto_perfil']); ?>" alt="Foto de Perfil">
                            <div class="profile-info">
                                <h2><?php echo htmlspecialchars($row_usuario['nome_usuario']); ?></h2>
                                <p>Email: <?php echo htmlspecialchars($row_usuario['email']); ?></p>
                                <p>Descrição: <?php echo htmlspecialchars($row_usuario['descricao_perfil']); ?></p>
                                <a href="menu_receitas.php"><button class="log">Voltar</button></a>


                            </div>
                        </div>

                        <div class="receitas tab-content receitas-tab">
                            <?php
                            // Consulta as receitas do usuário
                            $sql_receitas = "SELECT * FROM Receitas WHERE user_id = ?";
                            $stmt_receitas = $mysqli->prepare($sql_receitas);
                            $stmt_receitas->bind_param("i", $perfil_usuario_id);

                            $stmt_receitas->execute();
                            $result_receitas = $stmt_receitas->get_result();

                            if ($result_receitas && $result_receitas->num_rows > 0) {
                                while ($row_receita = $result_receitas->fetch_assoc()) {
                            ?>
                                    <div class="receita">
                                        <h1>Receitas do Usuário</h1>
                                        <h3><?php echo htmlspecialchars($row_receita['titulo']); ?></h3>
                                        <p>Descrição: <?php echo htmlspecialchars($row_receita['descricao']); ?></p>
                                        <div class="image-container">
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row_receita['foto_receita']); ?>" alt="Foto da Receita">
                                        </div>
                                        <p>Ingredientes: <?php echo htmlspecialchars($row_receita['ingredientes']); ?></p>
                                        <p>Passos de Preparação: <?php echo htmlspecialchars($row_receita['passos_preparacao']); ?></p>
                                        <p>Tempo de Preparação: <?php echo htmlspecialchars($row_receita['tempo_preparacao']); ?> minutos</p>
                                        <p>Categoria: <?php echo htmlspecialchars($row_receita['categoria']); ?></p>
                                        <p>Dificuldade: <?php echo htmlspecialchars($row_receita['dificuldade']); ?></p>
                                        <p>Número de Porções: <?php echo htmlspecialchars($row_receita['num_porcoes']); ?></p>
                                        <p>Avaliação: <?php echo htmlspecialchars($row_receita['avaliacao']); ?></p>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "Nenhuma receita encontrada.";
                            }
                            ?>
                        </div>

                    </div>
                    <script>
                        function openTab(tabName) {
                            var i, tabcontent;

                            // Oculta todo o conteúdo da tab
                            tabcontent = document.getElementsByClassName("tab-content");
                            for (i = 0; i < tabcontent.length; i++) {
                                tabcontent[i].style.display = "none";
                            }

                            // Exibe o conteúdo da tab clicada
                            document.getElementsByClassName(tabName + "-tab")[0].style.display = "flex";
                        }
                    </script>
                </body>

                </html>

<?php
            } else {
                echo "Usuário não encontrado.";
            }
        } else {
            throw new Exception($mysqli->error);
        }
    } catch (Exception $e) {
        error_log("Erro na consulta SQL: " . $e->getMessage());
        echo "Desculpe, ocorreu um erro inesperado. Por favor, tente novamente mais tarde.";
    }
} else {
    echo "ID do usuário não fornecido.";
}

// Fecha a conexão com o banco de dados
$mysqli->close();
?>
