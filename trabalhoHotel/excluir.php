<?php
$mensagem = "";
$tipo = "";
$html = "";

if (isset($_POST['combo'])) {
    $cpf = $_POST['combo'];
    if ($cpf != "0") {
        $html = consultaHospede($cpf);
    }
} 

if (isset($_POST['excluir'])) {
    $id = $_POST['id'];
    if (excluiHospede($id)) {
        $mensagem = "Hóspede excluído com sucesso!";
        $tipo = "sucesso";
    } else {
        $mensagem = "Erro ao excluir hóspede!";
        $tipo = "erro";
    }
}

function conectar($bd) {
    return new PDO("mysql:host=localhost;dbname=$bd", "root", "");
}
function encerrar()
{
    return null;
}

function mostraCpfCombo()
{
    echo '<form name="menu" method="post" action="excluir.php">
            <input list="listaCPFs" name="combo" placeholder="Digite ou escolha um CPF">
            <datalist id="listaCPFs">';

    $conexao = conectar("bdhotel");
    $sql = "SELECT cpf FROM hospede";
    $pstmt = $conexao->prepare($sql);
    $pstmt->execute();

    while ($linha = $pstmt->fetch()) {
        echo '<option value="' . $linha["cpf"] . '">' . $linha["cpf"] . '</option>';
    }

    $conexao = encerrar();
    echo '</datalist>';
    echo '<input type="submit" value="Consultar">';
    echo '</form>';

}


function consultaHospede($cpf) {
    $conexao = conectar("bdhotel");
    $sql = "SELECT * FROM hospede WHERE cpf = :cpf";
    $pstmt = $conexao->prepare($sql);
    $pstmt->bindValue(":cpf", $cpf);
    $pstmt->execute();

    $html = "";

    if ($pstmt->rowCount() > 0) {
        $linha = $pstmt->fetch();
        if ($linha["sexo"] == "M") {
            $sexo = "Masculino";
        } else if ($linha["sexo"] == "F") {
            $sexo = "Feminino";
        } else {
            $sexo = "-";
        }

        $html .= '<form method="post" action="">';
        $html .= '<label>ID:<br><input type="text" name="id" value="' . $linha["id"] . '" readonly></label><br><br>';
        $html .= '<label>CPF:<br><input type="text" name="cpf" value="' . $linha["cpf"] . '" readonly></label><br><br>';
        $html .= '<label>Nome:<br><input type="text" name="nome" value="' . $linha["nome"] . '" readonly></label><br><br>';
        $html .= '<label>Sobrenome:<br><input type="text" name="sobrenome" value="' . $linha["sobrenome"] . '" readonly></label><br><br>';
        $html .= '<label>Sexo:<br><input type="text" name="sexo" value="' . $sexo . '" readonly></label><br><br>';
        $html .= '<label>Data de Nascimento:<br><input type="text" name="dataNascimento" value="' . $linha["dataNascimento"] . '" readonly></label><br><br>';
        $html .= '<input type="submit" name="excluir" value="Excluir">';
        $html .= '</form>';
    } else {
        $html .= "<h3>Hóspede não encontrado.</h3>";
    }

    return $html;
}

function excluiHospede($id) {
    $conexao = conectar("bdhotel");

    $sql1 = "DELETE FROM controle WHERE hospede_id = :id";
    $pstmt1 = $conexao->prepare($sql1);
    $pstmt1->bindValue(":id", $id);
    $ok1 = $pstmt1->execute();

    $sql2 = "DELETE FROM hospede WHERE id = :id";
    $pstmt2 = $conexao->prepare($sql2);
    $pstmt2->bindValue(":id", $id);
    $ok2 = $pstmt2->execute();

    if ($ok1 && $ok2) {
        return true;
    } else {
        return false;
    }
}
?>



<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/excluir.css">
    <title>Excluir Hóspede</title>
</head>

<body>
    <header>
        <h1>Excluir</h1>
    </header>

    <div class="fundo">
        <div class="conteiner">
            <?php
            if (!empty($mensagem)) {
                if ($tipo == "sucesso") {
                    $cor = "green";
                } else if ($tipo == "erro") {
                    $cor = "red";
                } else {
                    $cor = "black";
                }
                echo '<p class="mensagem" style="color:' . $cor . ';">' . $mensagem . '</p>';
            }

            mostraCpfCombo();
            echo $html;
            ?>
        </div>
    </div>

    <a href="index.php"><button>Voltar</button></a>

    <footer>
        <p>Vinicios 2025</p>
    </footer>

    <script src="js/mensagem.js"></script>
    <script>
        sumirMensagem("mensagem", 2000);
    </script>
</body>

</html>
