<?php
$mensagem = "";
$tipo = "";
$html = "";


if (isset($_POST['comboCpf'])) {
    $cpf = $_POST['comboCpf'];
    $html = consultarHospedePorCpf($cpf);
}

if (isset($_POST['comboId'])) {
    $id = $_POST['comboId'];
    $html = consultarCpfPorId($id);
}

if (isset($_POST['alterar'])) {

    $cpf_antigo = "";
    $cpf = "";
    $nome = null;
    $sobrenome = null;
    $dataNascimento = null;

    if (isset($_POST['cpf_antigo'])) {
        $cpf_antigo = $_POST['cpf_antigo'];
    }

    if (isset($_POST['cpf'])) {
        $cpf = $_POST['cpf'];
    }

    if (isset($_POST['nome'])) {
        $nome = $_POST['nome'];
    }

    if (isset($_POST['sobrenome'])) {
        $sobrenome = $_POST['sobrenome'];
    }

    if (isset($_POST['dataNascimento'])) {
        $dataNascimento = $_POST['dataNascimento'];
    }


    if ($cpf_antigo != "") {
        if ($cpf == "") {
            $mensagem = "O novo CPF é obrigatório!";
            $tipo = "erro";
        } elseif (strlen($cpf) != 11 || !ctype_digit($cpf)) {
            $mensagem = "O CPF deve ter exatamente 11 números.";
            $tipo = "erro";
        } else {
            $resultado = alterarCpfPorId($cpf_antigo, $cpf);
            if ($resultado) {
                $mensagem = "CPF alterado com sucesso!";
                $tipo = "sucesso";
            } else {
                $mensagem = "Erro ao alterar o CPF";
                $tipo = "erro";
            }
        }
    } else if ($cpf != "") {

        if (strlen($cpf) != 11 || !ctype_digit($cpf)) {
            $mensagem = "O CPF deve ter exatamente 11 números.";
            $tipo = "erro";
        } elseif ($dataNascimento !== null && $dataNascimento > date('Y-m-d')) {
            $mensagem = "A data de nascimento não pode ser maior que hoje.";
            $tipo = "erro";
        } elseif ($nome === null && $sobrenome === null && $dataNascimento === null) {
            $mensagem = "Nenhum dado enviado para alteração.";
            $tipo = "erro";
        } else {
            $resultado = alterarHospedePorCpf($cpf, $nome, $sobrenome, $dataNascimento);
            if ($resultado) {
                $mensagem = "Dados do hóspede alterados com sucesso!";
                $tipo = "sucesso";
            } else {
                $mensagem = "Erro ao alterar os dados do hóspede.";
                $tipo = "erro";
            }
        }
    }
}

function conectar($bd)
{
    return new PDO("mysql:host=localhost;dbname=$bd", "root", "");
}

function encerrar()
{
    return null;
}

function alterarHospedePorCpf($cpf, $nome, $sobrenome, $dataNascimento)
{
    $conexao = conectar("bdhotel");
    $sql = "UPDATE hospede SET 
                nome = :nome, 
                sobrenome = :sobrenome, 
                dataNascimento = :dataNascimento
            WHERE cpf = :cpf";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(':nome', $nome);
    $stmt->bindValue(':sobrenome', $sobrenome);
    $stmt->bindValue(':dataNascimento', $dataNascimento);
    $stmt->bindValue(':cpf', $cpf);

    $resultado = $stmt->execute();
    encerrar();
    return $resultado;
}


function alterarCpfPorId($cpf_antigo, $novo_cpf)
{
    $conexao = conectar("bdhotel");
    $sql = "UPDATE hospede SET cpf = :novo_cpf WHERE cpf = :cpf_antigo";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(":novo_cpf", $novo_cpf);
    $stmt->bindValue(":cpf_antigo", $cpf_antigo);
    $resultado = $stmt->execute();
    $conexao = encerrar();
    return $resultado;
}

function mostraCpfCombo()
{
    echo '<form name="menu" method="post" action="">
            <input list="listaCPFs" name="comboCpf" placeholder="Digite ou escolha um CPF">
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

function mostraIdCombo()
{
    echo '<form name="menu" method="post" action="">
            <input list="listaIds" name="comboId" placeholder="Digite ou escolha um ID">
            <datalist id="listaIds">';

    $conexao = conectar("bdhotel");
    $sql = "SELECT id FROM hospede";
    $stmt = $conexao->prepare($sql);
    $stmt->execute();
    while ($linha = $stmt->fetch()) {
        echo '<option value="' . $linha["id"] . '">' . $linha["id"] . '</option>';
    }
    $conexao = encerrar();
    echo '</datalist>';
    echo '<input type="submit" value="Consultar">';
    echo '</form>';
}

function consultarHospedePorCpf($cpf)
{
    $conexao = conectar("bdhotel");
    $sql = "SELECT * FROM hospede WHERE cpf = :cpf";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(":cpf", $cpf);
    $stmt->execute();

    if ($linha = $stmt->fetch()) {
        $html = '<form method="post">';
        $html .= '<input type="hidden" name="cpf" value="' . $linha["cpf"] . '">';
        $html .= '<label>ID:<br><input type="text" value="' . $linha["id"] . '" readonly></label><br><br>';
        $html .= '<label>CPF:<br><input type="text" value="' . $linha["cpf"] . '" readonly></label><br><br>';
        $html .= '<label>Nome:<br><input type="text" name="nome" value="' . $linha["nome"] . '"></label><br><br>';
        $html .= '<label>Sobrenome:<br><input type="text" name="sobrenome" value="' . $linha["sobrenome"] . '"></label><br><br>';
        $html .= '<label>Data de Nascimento:<br><input type="date" name="dataNascimento" value="' . $linha["dataNascimento"] . '"></label><br><br>';
        $html .= '<input type="submit" name="alterar" value="Alterar">';
        $html .= '</form>';
        return $html;
    }
}

function consultarCpfPorId($id)
{
    $conexao = conectar("bdhotel");
    $sql = "SELECT cpf FROM hospede WHERE id = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(":id", $id);
    $stmt->execute();

    if ($linha = $stmt->fetch()) {
        $html = '<form method="post">';
        $html .= '<input type="hidden" name="cpf_antigo" value="' . $linha["cpf"] . '">';
        $html .= '<label>CPF:<br><input type="text" name="cpf" value="' . $linha["cpf"] . '"></label><br><br>';
        $html .= '<input type="submit" name="alterar" value="Alterar">';
        $html .= '</form>';
        return $html;
    }
}


?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Alterar Hóspede</title>
    <link rel="stylesheet" href="css/attHospede.css">
</head>

<body>
    <header>
        <h1>Atualizar Hóspede</h1>
    </header>

    <div class="fundo">
        <div class="conteiner">

            <?php
            if (!empty($mensagem)) {
                $cor = "black";
                if ($tipo == "sucesso") {
                    $cor = "green";
                }
                if ($tipo == "erro") {
                    $cor = "red";
                }
                echo '<p class="mensagem" style="color:' . $cor . ';">' . $mensagem . '</p>';
            }

            mostraCpfCombo();
            mostraIdCombo();

            if (!empty($html)) {
                echo $html;
            }
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