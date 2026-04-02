<?php
function conectar($bd)
{
    return new PDO("mysql:host=localhost;dbname=$bd", "root", "");
}

function encerrar()
{
    return null;
}

function listarTodosHospedes()
{
    $conexao = conectar("bdhotel");
    $sql = "SELECT h.id AS hospede_id, h.cpf, h.nome, h.sobrenome, h.sexo, h.dataNascimento,
                   c.id AS controle_id, c.hospede_id AS c_hospede_id, c.paisOrigem, c.previsaoEstadia, c.ciasAereas
            FROM hospede h
            LEFT JOIN controle c ON c.hospede_id = h.id";
    $pstmt = $conexao->prepare($sql);
    $pstmt->execute();

    echo "<table>
            <tr>
                <th>ID Hóspede</th>
                <th>Nome</th>
                <th>Sobrenome</th>
                <th>CPF</th>
                <th>Sexo</th>
                <th>Data de Nascimento</th>
                <th>ID Controle</th>
                <th>Hóspede ID Controle</th>
                <th>País de Origem</th>
                <th>Previsão de Estadia</th>
                <th>Cias Aéreas</th>
            </tr>";

    $i = 0;
    while ($linha = $pstmt->fetch()) {
        $sexo = "-";
        if ($linha['sexo'] == 'M' || $linha['sexo'] == 'm') {
            $sexo = "Masculino";
        }
        if ($linha['sexo'] == 'F' || $linha['sexo'] == 'f') {
            $sexo = "Feminino";
        }
        $dataNascimento = "-";
        if (!empty($linha['dataNascimento'])) {
            $dataNascimento = $linha['dataNascimento'];
        }

        $corFundo = "";
        if ($i % 2 == 0) {
            $corFundo = "background-color: rgba(255,255,255,0.08);";
        }

        echo "<tr style='$corFundo'>";
        echo "<td>" . $linha['hospede_id'] . "</td>";
        echo "<td>" . $linha['nome'] . "</td>";
        echo "<td>" . $linha['sobrenome'] . "</td>";
        echo "<td>" . $linha['cpf'] . "</td>";
        echo "<td>" . $sexo . "</td>";
        echo "<td>" . $dataNascimento . "</td>";
        echo "<td>" . $linha['controle_id'] . "</td>";
        echo "<td>" . $linha['c_hospede_id'] . "</td>";
        echo "<td>" . $linha['paisOrigem'] . "</td>";
        echo "<td>" . $linha['previsaoEstadia'] . "</td>";
        echo "<td>" . $linha['ciasAereas'] . "</td>";
        echo "</tr>";

        $i++;
    }

    echo "</table>";

    $conexao = encerrar();
}
?>



<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/listaHospedagem.css">
    <title>Lista das Hospedagens</title>
</head>

<body>
    <header>
        <h1>Hospedagens</h1>
    </header>

    <div>
        <?php listarTodosHospedes(); ?>
    </div>

    <a href="index.php"><button>Voltar</button></a>

    <footer>
        <p>Vinicios 2025</p>
    </footer>

</body>

</html>