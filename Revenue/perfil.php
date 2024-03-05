<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "tcc");

if ($mysqli->connect_error) {
    die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
}

// Verifica se a variável de sessão 'usuario_id' está definida e não está vazia
if (isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];

    // Consulta informações do usuário
    $sql_usuario = "SELECT * FROM Usuarios WHERE usuario_id = ?";
    $stmt_usuario = $mysqli->prepare($sql_usuario);
    $stmt_usuario->bind_param("i", $usuario_id);

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
                    <title>Perfil de <?php echo htmlspecialchars($row_usuario['nome_usuario']); ?></title>


                    <link rel="stylesheet" href="css/perfil.css">
                </head>


                <body>
                    <div class="container">

                        <header>

                            <h1>Perfil de <?php echo htmlspecialchars($row_usuario['nome_usuario']); ?></h1>
                        </header>


                        <div class="tab-container">
                            <div class="tab" onclick="openTab('receitas')">Receitas</div>
                            <div class="tab" onclick="openTab('favoritas')">Receitas Favoritas</div>

                        </div>

                        <div class="profile">
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row_usuario['foto_perfil']); ?>" alt="Foto de Perfil">
                            <div class="profile-info">
                                <h2><?php echo htmlspecialchars($row_usuario['nome_usuario']); ?></h2>
                                <p>Email: <?php echo htmlspecialchars($row_usuario['email']); ?></p>
                                <p>Descrição: <?php echo htmlspecialchars($row_usuario['descricao_perfil']); ?></p>
                                <div class="button-container">
                                    <a href="menu_receitas.php"><button class="log">Voltar</button></a>
                                    <a href="logout.php"><button class="log">Logout</button></a>
                                    <button class="edit" onclick="openEditForm()">✎</button>

                                    <style>
                                        .edit {
                                            background-color: #FFD700;
                                            color: #FFA500;
                                            border-radius: 5px;
                                            padding: 5px 10px;
                                            float: right;
                                            margin-top: 10px;
                                        }

                                        .edit:hover {
                                            background-color: #E8C400;
                                        }
                                    </style>





                                </div>
                            </div>
                        </div>

                        <div class="receitas tab-content receitas-tab">


                            <?php
                            // Consulta as receitas do usuário
                            $sql_receitas = "SELECT * FROM Receitas WHERE user_id = ?";
                            $stmt_receitas = $mysqli->prepare($sql_receitas);
                            $stmt_receitas->bind_param("i", $usuario_id);

                            $stmt_receitas->execute();
                            $result_receitas = $stmt_receitas->get_result();

                            if ($result_receitas && $result_receitas->num_rows > 0) {
                                while ($row_receita = $result_receitas->fetch_assoc()) {
                            ?>

                                    <!-- Exibir informações da receita -->
                                    <div class="receita">
                                        <h1>Receitas do Usuario</h1>
                                        <h3><?php echo htmlspecialchars($row_receita['titulo']); ?></h3>
                                        <p>Descrição: <?php echo htmlspecialchars($row_receita['descricao']); ?></p>
                                        <div class="image-container">
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row_receita['foto_receita']); ?>" alt="Foto da Receita">
                                        </div>
                                        <p>Ingredientes: <?php echo htmlspecialchars($row_receita['ingredientes']); ?></p>
                                        <p>Passos de Preparação: <?php echo htmlspecialchars($row_receita['passos_preparacao']); ?></p>
                                        <p>Tempo de Preparação: <?php echo htmlspecialchars($row_receita['tempo_preparacao']); ?> minutos</ <p>Tempo de Preparação: <?php echo htmlspecialchars($row_receita['tempo_preparacao']); ?> minutos</p>
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

                        <div class="receitas-favoritas tab-content favoritas-tab">
                            <!-- Aqui serão exibidas as receitas favoritas -->
                            <?php
                            // Consulta as receitas favoritas do usuário
                            $sql_favoritas = "SELECT r.* FROM Receitas r
                                  INNER JOIN Favoritos f ON r.receita_id = f.receita_id
                                  WHERE f.usuario_id = ?";
                            $stmt_favoritas = $mysqli->prepare($sql_favoritas);
                            $stmt_favoritas->bind_param("i", $usuario_id);

                            $stmt_favoritas->execute();
                            $result_favoritas = $stmt_favoritas->get_result();

                            if ($result_favoritas && $result_favoritas->num_rows > 0) {
                                while ($row_favorita = $result_favoritas->fetch_assoc()) {
                                    // Exibir informações da receita favorita
                            ?>
                                    <div class="receita-favorita">
                                        <h3><?php echo htmlspecialchars($row_favorita['titulo']); ?></h3>
                                        <p>Descrição: <?php echo htmlspecialchars($row_favorita['descricao']); ?></p>
                                        <div class="image-container">
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row_favorita['foto_receita']); ?>" alt="Foto da Receita">
                                        </div>
                                        <p>Ingredientes: <?php echo htmlspecialchars($row_favorita['ingredientes']); ?></p>
                                        <p>Passos de Preparação: <?php echo htmlspecialchars($row_favorita['passos_preparacao']); ?></p>
                                        <p>Tempo de Preparação: <?php echo htmlspecialchars($row_favorita['tempo_preparacao']); ?> minutos</p>
                                        <p>Categoria: <?php echo htmlspecialchars($row_favorita['categoria']); ?></p>
                                        <p>Dificuldade: <?php echo htmlspecialchars($row_favorita['dificuldade']); ?></p>
                                        <p>Número de Porções: <?php echo htmlspecialchars($row_favorita['num_porcoes']); ?></p>
                                        <p>Avaliação: <?php echo htmlspecialchars($row_favorita['avaliacao']); ?></p>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "Nenhuma receita favorita encontrada.";
                            }
                            ?>
                        </div>

                        


                        <!-- Formulário de Edição (inicialmente oculto) -->
                        <div id="editForm" style="display: none;">
                            <form action="editar_perfil.php" method="post">
                                <link rel="stylesheet" href="css/edit.css">
                                <label for="nome_usuario" class="form-label">Nome:</label>
                                <input type="text" id="nome_usuario" name="nome_usuario" class="nome" value="<?= htmlspecialchars($row_usuario['nome_usuario']); ?>">

                                <label for="email" class="form-label">Email:</label>
                                <input type="text" id="email" name="email" class="email" value="<?= htmlspecialchars($row_usuario['email']); ?>">

                                <label for="descricao_perfil" class="form-label">Descrição:</label>
                                <textarea id="descricao_perfil" name="descricao_perfil" class="descricao"><?= htmlspecialchars($row_usuario['descricao_perfil']); ?></textarea>

                                <button type="submit" class="salvar">Salvar</button>
                                <button type="button" onclick="closeEditForm()" class="cancelar">Cancelar</button>
                            </form>
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

                            function openEditForm() {
                                document.getElementById("editForm").style.display = "block";
                                // Oculta o conteúdo da tab atual
                                var tabcontent = document.getElementsByClassName("tab-content");
                                for (var i = 0; i < tabcontent.length; i++) {
                                    tabcontent[i].style.display = "none";
                                }
                            }

                            function closeEditForm() {
                                document.getElementById("editForm").style.display = "none";
                                // Exibe o conteúdo da tab ativa
                                var activeTab = document.querySelector(".tab-content[style*='display: flex;']");
                                if (activeTab) {
                                    activeTab.style.display = "flex";
                                }
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
    header("location: login.html");
    exit;
}

// Fecha a conexão com o banco de dados
$mysqli->close();
?>