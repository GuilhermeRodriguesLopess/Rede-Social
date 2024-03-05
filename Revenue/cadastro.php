<?php
// Inicia a sessão
session_start();

// Conecta ao banco de dados
$mysqli = new mysqli("localhost", "root", "", "tcc");

// Verifica se houve um erro na conexão
if ($mysqli->connect_error) {
    die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtém os dados do formulário
    $nome_usuario = $_POST["nome_usuario"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $descricao_perfil = $_POST["descricao_perfil"];

    // Lidar com o upload da foto de perfil
    $foto_perfil = $_FILES["foto_perfil"]["tmp_name"];

    // Verifica se a imagem de perfil foi selecionada
    if (empty($foto_perfil)) {
        echo "A imagem de perfil não foi selecionada.";
    } else {
        // Lê o conteúdo da imagem para um fluxo de dados
        $foto_perfil_content = file_get_contents($foto_perfil);

        // Verifica se o conteúdo da imagem foi lido com sucesso
        if ($foto_perfil_content) {
            echo "Conteúdo da imagem de perfil carregado com sucesso.";

            // Inserir os dados no banco de dados
            $stmt = $mysqli->prepare("INSERT INTO Usuarios (nome_usuario, email, senha, descricao_perfil, foto_perfil) VALUES (?, ?, ?, ?, ?)");

            // Verifica se a preparação da declaração foi bem-sucedida
            if ($stmt) {
                // Bind dos parâmetros
                $stmt->bind_param("ssssb", $nome_usuario, $email, $senha, $descricao_perfil, $foto_perfil_content);

                // Inserir o conteúdo da imagem
                $stmt->send_long_data(4, $foto_perfil_content);

                // Executar a declaração preparada
                if ($stmt->execute()) {
                    // Redirecionar para outra página após o registro bem-sucedido
                    header("Location: menu_receitas.php");
                    exit;
                } else {
                    echo "Erro ao registrar o usuário: " . $stmt->error;
                    echo "Erro MySQL: " . $mysqli->error;
                }
            } else {
                echo "Erro na preparação da declaração: " . $mysqli->error;
            }
        } else {
            echo "Erro ao ler o conteúdo da imagem de perfil.";
        }
    }
}

// Fecha a conexão com o banco de dados
$mysqli->close();
?>
