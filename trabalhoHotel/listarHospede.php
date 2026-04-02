<?php
$html = "";

if (isset($_POST['combo'])) {
    $cpf = $_POST['combo'];
    if ($cpf != "0") {
        $html = consultaHospede($cpf);
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

function mostraCpfCombo()
{
    echo '<form name="menu" method="post" action="">
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




function consultaHospede($cpf)
{
    $conexao = conectar("bdhotel");
    $sql = "SELECT * FROM hospede WHERE cpf = :cpf";
    $pstmt = $conexao->prepare($sql);
    $pstmt->bindValue(":cpf", $cpf);
    $pstmt->execute();

    $html = "<table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Sobrenome</th>
                <th>CPF</th>
                <th>Sexo</th>
                <th>Data de Nascimento</th>
            </tr>";

    if ($linha = $pstmt->fetch()) {
        $html .= "<tr>";
        $html .= "<td>" . $linha['id'] . "</td>";
        $html .= "<td>" . $linha['nome'] . "</td>";
        $html .= "<td>" . $linha['sobrenome'] . "</td>";
        $html .= "<td>" . $linha['cpf'] . "</td>";

        $sexo = "-";

        if ($linha['sexo'] == 'M' || $linha['sexo'] == 'm') {
            $sexo = "Masculino";
        }
        if ($linha['sexo'] == 'F' || $linha['sexo'] == 'f') {
            $sexo = "Feminino";
        }

        $html .= "<td>" . $sexo . "</td>";

        if (!empty($linha['dataNascimento'])) {
            $html .= "<td>" . $linha['dataNascimento'] . "</td>";
        } else {
            $html .= "<td>-</td>";
        }

        $html .= "</tr>";
    }

    $html .= "</table>";
    $conexao = encerrar();
    return $html;
}
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/listaHospede.css">
    <title>Procure o Hospede</title>
</head>

<body>
    <header>
        <h1>Procure o Hospede desejado</h1>
    </header>

    <div>
        <?php
        mostraCpfCombo();
        echo $html;
        ?>
    </div>

    <a href="index.php"><button>Voltar</button></a>

    <footer>
        <p>Vinicios 2025</p>
    </footer>
</body>

</html>