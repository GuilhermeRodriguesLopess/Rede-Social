<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $receita_id = $_POST["receita_id"];
    $tipo = $_POST["tipo"];
    $descricao = $_POST["descricao"];
    
    if (isset($_SESSION["usuario_id"])) {
        $usuario_id = $_SESSION["usuario_id"];
        
        $mysqli = new mysqli("localhost", "root", "", "tcc");

        if ($mysqli->connect_error) {
            die("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
        }

        try {
            // Verificar se o receita_id existe em Receitas
            $check_sql = "SELECT receita_id FROM Receitas WHERE receita_id = ?";
            $check_stmt = $mysqli->prepare($check_sql);
            $check_stmt->bind_param("i", $receita_id);
            $check_stmt->execute();
            $check_stmt->store_result();

            if ($check_stmt->num_rows == 0) {
                echo "Erro: receita_id não encontrado em Receitas. Valor: " . $receita_id;
                exit;
            }

            // Iniciar transação
            $mysqli->begin_transaction();

            // Inserir denúncia
            $insert_sql = "INSERT INTO Denuncias (tipo, descricao, usuario_id, receita_id) VALUES (?, ?, ?, ?)";
            $stmt = $mysqli->prepare($insert_sql);

            if (!$stmt) {
                throw new Exception("Erro na preparação da consulta: " . $mysqli->error);
            }

            $stmt->bind_param("ssii", $tipo, $descricao, $usuario_id, $receita_id);

            if ($stmt->execute()) {
                // Confirmar as alterações no banco de dados
                $mysqli->commit();

                // Redirecionar para a página desejada
                header("Location: menu_receitas.php");
                exit;
            } else {
                throw new Exception("Erro ao enviar a denúncia: " . $stmt->error);
            }
        } catch (Exception $e) {
            // Reverter as alterações no banco de dados se houver um erro
            $mysqli->rollback();
            echo $e->getMessage();
        } finally {
            // Fechar as conexões
            $stmt->close();
            $check_stmt->close();
            $mysqli->close();
        }
    } else {
        echo "Usuário não autenticado. Redirecionando para a página de login...";
        header("Location: login.html");
        exit;
    }
}
?>
