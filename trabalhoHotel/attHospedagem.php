<?php
$mensagem = "";
$tipo = "";
$html = "";

if (isset($_POST['comboCpf'])) {
    $cpf = $_POST['comboCpf'];
    if ($cpf != "") {
        $html = consultaHospedagem($cpf);
    }
}

if (isset($_POST['excluir'])) {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
    } else {
        $id = "";
    }
    if ($id != "") {
        if (excluiHospedagem($id)) {
            $mensagem = "Registro excluído com sucesso!";
            $tipo = "sucesso";
        } else {
            $mensagem = "Erro ao excluir o registro!";
            $tipo = "erro";
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

function mostraCpfCombo()
{
    echo '<form name="menu" method="post" action="attHospedagem.php">
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

function consultaHospedagem($cpf)
{
    $conexao = conectar("bdhotel");

    $sql = "SELECT h.nome, h.sobrenome, h.cpf, c.id AS controle_id, 
                   c.paisOrigem, c.previsaoEstadia, c.ciasAereas
            FROM hospede h
            LEFT JOIN controle c ON h.id = c.hospede_id
            WHERE h.cpf = :cpf
            ORDER BY c.id";

    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(":cpf", $cpf);
    $stmt->execute();

    $html = "";
    $tabelaAberta = false;
    $temRegistro = false;
    $i = 0;

    while ($linha = $stmt->fetch()) {
        $temRegistro = true;

        if ($linha['controle_id'] !== null) {
            $temHospedagem = true;

            if (!$tabelaAberta) {
                $html .= '<table>
                            <tr>
                                <th>Hóspede</th>
                                <th>CPF</th>
                                <th>País de Origem</th>
                                <th>Previsão de Estadia</th>
                                <th>Companhias Aéreas</th>
                                <th>Ações</th>
                            </tr>';
                $tabelaAberta = true;
            }
            $corFundo = "";
            if ($i % 2 == 0) {
                $corFundo = ' style="background-color: rgba(255,255,255,0.08);"';
            }

            $html .= '<tr' . $corFundo . '>';
            $html .= '<td>' . $linha['nome'] . ' ' . $linha['sobrenome'] . '</td>';
            $html .= '<td>' . $linha['cpf'] . '</td>';

            if ($linha['paisOrigem'] != null && $linha['paisOrigem'] != "") {
                $html .= '<td>' . $linha['paisOrigem'] . '</td>';
            } else {
                $html .= '<td>-</td>';
            }

            if ($linha['previsaoEstadia'] != null && $linha['previsaoEstadia'] != "") {
                $html .= '<td>' . $linha['previsaoEstadia'] . '</td>';
            } else {
                $html .= '<td>-</td>';
            }

            if ($linha['ciasAereas'] != null && $linha['ciasAereas'] != "") {
                $html .= '<td>' . $linha['ciasAereas'] . '</td>';
            } else {
                $html .= '<td>-</td>';
            }

            $html .= '<td>
            <form method="post" action="editarHospedagem.php">
                <input type="hidden" name="id" value="' . $linha['controle_id'] . '">
                <input type="submit" name="editar" value="Editar">
            </form>
            <form method="post" action="">
                <input type="hidden" name="id" value="' . $linha['controle_id'] . '">
                <input type="submit" name="excluir" value="Excluir">
            </form>
        </td>';
            $html .= '</tr>';

            $i++;

        }
    }

    if ($tabelaAberta) {
        $html .= '</table>';
    } else {
        if ($temRegistro) {
            $html .= '<p class="pa">Este hóspede não possui hospedagens registradas.</p>';
        } else {
            $html .= '<p class="pa">Nenhum hóspede encontrado com este CPF.</p>';
        }
    }

    $conexao = encerrar();
    return $html;
}




function excluiHospedagem($id)
{
    $conexao = conectar("bdhotel");
    $sql = "DELETE FROM controle WHERE id = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(":id", $id);
    $resultado = $stmt->execute();
    $conexao = encerrar();

    return $resultado;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Atualizar Controle</title>
    <link rel="stylesheet" href="css/attHospedagem.css">
</head>

<body>
    <header>
        <h1>Atualizar Controle de Hospedagem</h1>
    </header>

    <div class="conteiner">
        <?php
        if ($mensagem != "") {
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
        echo $html;
        ?>
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