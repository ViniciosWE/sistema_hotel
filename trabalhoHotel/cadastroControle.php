<?php
$mensagem = "";
$tipo = "";
$cpf = "";

if (isset($_POST['combo'])) {
    $cpf = $_POST['combo'];
}

if (isset($_POST["botao"])) {

    if ($cpf == "0" || empty($cpf)) {
        $mensagem = "Selecione um CPF!";
        $tipo = "erro";
    } else {

        if (isset($_POST["paisOrigem"])) {
            $paisOrigem = $_POST["paisOrigem"];
        } else {
            $paisOrigem = "";
        }

        if (isset($_POST["previsaoEstadia"])) {
            $previsaoEstadia = $_POST["previsaoEstadia"];
        } else {
            $previsaoEstadia = "";
        }

        if (isset($_POST['ciasAereas']) && count($_POST['ciasAereas']) > 0) {
            $ciasAereas = implode(", ", $_POST['ciasAereas']);
        } else {
            $ciasAereas = "";
        }

        $inserido = inserirHospedagem($cpf, $paisOrigem, $previsaoEstadia, $ciasAereas);

        if ($inserido) {
            $mensagem = "Cadastro realizado com sucesso!";
            $tipo = "sucesso";
        } else {
            $mensagem = "Erro ao cadastrar hóspede ou controle!";
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
function pegaId($cpf)
{
    $conexao = conectar("bdhotel");
    $sql = "SELECT id FROM hospede WHERE cpf = :cpf";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(":cpf", $cpf);
    $stmt->execute();
    $linha = $stmt->fetch();
    $conexao = null;

    if ($linha) {
        return $linha[0];
    } else {
        return null;
    }
}

function mostraCpfCombo()
{
    echo '<label>CPF do Hóspede:</label><br>';
    echo '<input list="listaCPFs" name="combo" placeholder="Digite ou escolha um CPF">';
    echo '<datalist id="listaCPFs">';

    $conexao = conectar("bdhotel");
    $sql = "SELECT cpf FROM hospede";
    $pstmt = $conexao->prepare($sql);
    $pstmt->execute();

    while ($linha = $pstmt->fetch()) {
        echo '<option value="' . $linha["cpf"] . '">' . $linha["cpf"] . '</option>';
    }
    $conexao = encerrar();

    echo '</datalist><br>';
}

function inserirHospedagem($cpf, $paisOrigem, $previsaoEstadia, $ciasAereas)
{
    $hospede_id = pegaId($cpf);
    if (!$hospede_id) {
        return false;
    }

    $conexao = conectar("bdhotel");
    $sql_controle = "INSERT INTO controle (hospede_id, paisOrigem, previsaoEstadia, ciasAereas) 
                     VALUES (:hospede_id, :paisOrigem, :previsaoEstadia, :ciasAereas)";
    $stmt = $conexao->prepare($sql_controle);
    $stmt->bindValue(":hospede_id", $hospede_id);
    $stmt->bindValue(":paisOrigem", $paisOrigem);
    $stmt->bindValue(":previsaoEstadia", $previsaoEstadia);
    $stmt->bindValue(":ciasAereas", $ciasAereas);

    $resultado = $stmt->execute();
    $conexao = null;
    return $resultado;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cadastroControle.css">
    <title>Cadastro de Estadia</title>
</head>

<body>

    <header>
        <h1>Cadastro Hospedagem</h1>
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
            ?>

            <form method="post" action="">
                <?php mostraCpfCombo(); ?>

                <h3>Controle de Estadia</h3>

                <label>País de Origem:</label>
                <div class="linha">
                    <input type="radio" name="paisOrigem" value="Brasil"> Brasil
                    <input type="radio" name="paisOrigem" value="Argentina"> Argentina
                    <input type="radio" name="paisOrigem" value="Paraguai"> Paraguai
                    <input type="radio" name="paisOrigem" value="Uruguai"> Uruguai
                    <input type="radio" name="paisOrigem" value="Chile"> Chile
                    <input type="radio" name="paisOrigem" value="Peru"> Peru
                </div>
                <br>

                <label>Previsão de Estadia:</label>
                <select name="previsaoEstadia" id="previsaoEstadia">
                    <option>3 dias</option>
                    <option>5 dias</option>
                    <option>1 semana</option>
                    <option>2 semanas</option>
                    <option>3 semanas ou mais</option>
                </select>
                <br>

                <label>Companhias Aéreas:</label>
                <div class="linha">
                    <input type="checkbox" name="ciasAereas[]" value="GOL"> GOL
                    <input type="checkbox" name="ciasAereas[]" value="AZUL"> AZUL
                    <input type="checkbox" name="ciasAereas[]" value="TRIP"> TRIP
                    <input type="checkbox" name="ciasAereas[]" value="AVIANCA"> AVIANCA
                    <input type="checkbox" name="ciasAereas[]" value="RISSETTI"> RISSETTI
                    <input type="checkbox" name="ciasAereas[]" value="GLOBAL"> GLOBAL
                </div>

                <input class="btn" type="submit" value="Cadastrar" name="botao">
            </form>

        </div>
    </div>

    <a class="btn" href="index.php">Voltar</a>

    <footer>
        <p>Vinicios 2025</p>
    </footer>

    <script src="js/mensagem.js"></script>
    <script>
        sumirMensagem("mensagem", 2000);
    </script>
</body>

</html>