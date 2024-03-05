<?php
session_start();

// Conectar ao banco de dados e obter detalhes da receita com base no receita_id
$mysqli = new mysqli("localhost", "root", "", "tcc");

if ($mysqli->connect_error) {
    die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
}

if (isset($_GET['receita_id'])) {
    $receitaId = $_GET['receita_id'];

    $sql = "SELECT * FROM Receitas WHERE receita_id = $receitaId";
    $result = $mysqli->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $titulo = $row["titulo"];
            $descricao = $row["descricao"];
            $ingredientes = $row["ingredientes"];
            $passosPreparacao = $row["passos_preparacao"];
            $tempoPreparacao = $row["tempo_preparacao"];
            $categoria = $row["categoria"];
            $dificuldade = $row["dificuldade"];
            $numPorcoes = $row["num_porcoes"];
            $fotoReceita = base64_encode($row["foto_receita"]); // Codifica a imagem para exibição
        } else {
            echo "Receita não encontrada.";
        }
    } else {
        echo "Erro na consulta SQL: " . $mysqli->error;
    }
} else {
    echo "ID da receita não fornecido.";
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/detalhes.css">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <title>Detalhes da Receita</title>

</head>

<body>
    <div class="container">
        <h1>Detalhes da Receita</h1>

        <div class="receita">
            <h2><?php echo $titulo; ?></h2>
            <img src="data:image/jpeg;base64,<?php echo $fotoReceita; ?>" alt="Foto da Receita">
            <p><?php echo $descricao; ?></p>
            <p>Ingredientes: <?php echo $ingredientes; ?></p>
            <p>Passos de Preparação: <?php echo $passosPreparacao; ?></p>
            <p>Tempo de Preparação: <?php echo $tempoPreparacao; ?> minutos</p>
            <p>Categoria: <?php echo $categoria; ?></p>
            <p>Dificuldade: <?php echo $dificuldade; ?></p>
            <p>Número de Porções: <?php echo $numPorcoes; ?></p>
            <!-- ... outros campos da receita -->

            <!-- Adicione mais detalhes conforme necessário -->

            <a href="menu_receitas.php" class="botao-voltar">Voltar</a>
        </div>
    </div>
</body>

</html>