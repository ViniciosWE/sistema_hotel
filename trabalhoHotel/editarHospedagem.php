<?php
$mensagem = "";
$tipo = "";
$id = "";

if (isset($_POST['id']) && !isset($_POST['salvar'])) {
    $id = $_POST['id'];
}
if (isset($_POST['salvar'])) {
    $id = "";
    $pais = "";
    $estadia = "";
    $cias = "";

    if (isset($_POST['id'])) {
        $id = $_POST['id'];
    }
    if (isset($_POST['paisOrigem'])) {
        $pais = $_POST['paisOrigem'];
    }
    if (isset($_POST['previsaoEstadia'])) {
        $estadia = $_POST['previsaoEstadia'];
    }
    if (isset($_POST['ciasAereas']) && is_array($_POST['ciasAereas'])) {
        $cias = implode(",", $_POST['ciasAereas']);
    } else {
        $cias = "";
    }

    if ($id != "") {
        if (atualizarHospedagem($id, $pais, $estadia, $cias)) {
            $mensagem = "Dados atualizados com sucesso!";
            $tipo = "sucesso";
        } else {
            $mensagem = "Erro ao atualizar os dados!";
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

function mostrarFormularioEdicao($id)
{
    $conexao = conectar("bdhotel");
    $sql = "SELECT c.id, h.cpf, c.paisOrigem, c.previsaoEstadia, c.ciasAereas
            FROM controle c
            INNER JOIN hospede h ON c.hospede_id = h.id
            WHERE c.id = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(":id", $id);
    $stmt->execute();
    $dados = $stmt->fetch();
    $conexao = encerrar();

    $paisOrigem = $dados["paisOrigem"];
    $previsaoEstadia = $dados["previsaoEstadia"];

     if (is_array($dados["ciasAereas"])) {
        $ciasAereas = $dados["ciasAereas"];
    } else {
        $ciasAereas = explode(",", (string)$dados["ciasAereas"]);
        for ($i = 0; $i < count($ciasAereas); $i++) {
            $ciasAereas[$i] = trim($ciasAereas[$i]);
        }
    }

    echo '
        <form method="post" action="">
            <input type="hidden" name="id" value="' . $dados["id"] . '">

            <label>CPF:</label>
            <input class="edit" type="text" value="' . $dados["cpf"] . '" readonly><br><br>

            <label>País de Origem:</label>
            <div class="linha">';
            
                $paises = array("Brasil", "Argentina", "Paraguai", "Uruguai", "Chile", "Peru");
                for ($i = 0; $i < count($paises); $i++) {
                    echo '<input type="radio" name="paisOrigem" value="' . $paises[$i] . '"';
                    if ($paisOrigem == $paises[$i]) {
                        echo ' checked';
                    }
                    echo '> ' . $paises[$i] . ' ';
                }

    echo '  </div><br>

            <label>Previsão de Estadia:</label>
            <select name="previsaoEstadia" id="previsaoEstadia">';
            
                $opcoes = array("3 dias", "5 dias", "1 semana", "2 semanas", "3 semanas ou mais");
                for ($i = 0; $i < count($opcoes); $i++) {
                    echo '<option';
                    if ($previsaoEstadia == $opcoes[$i]) {
                        echo ' selected';
                    }
                    echo '>' . $opcoes[$i] . '</option>';
                }

    echo '  </select><br><br>

            <label>Companhias Aéreas:</label>
            <div class="linha">';
            
                $cias = array("GOL", "AZUL", "TRIP", "AVIANCA", "RISSETTI", "GLOBAL");
                for ($i = 0; $i < count($cias); $i++) {
                    echo '<input type="checkbox" name="ciasAereas[]" value="' . $cias[$i] . '"';
                    if (in_array($cias[$i], $ciasAereas)) {
                        echo ' checked';
                    }
                    echo '> ' . $cias[$i] . ' ';
                }

    echo '  </div><br><br>

            <input class="bt" type="submit" name="salvar" value="Editar">
        </form>
    ';
}


function atualizarHospedagem($id, $pais, $estadia, $cias)
{
    $conexao = conectar("bdhotel");
    $sql = "UPDATE controle 
            SET paisOrigem = :pais, previsaoEstadia = :estadia, ciasAereas = :cias 
            WHERE id = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(":pais", $pais);
    $stmt->bindValue(":estadia", $estadia);
    $stmt->bindValue(":cias", $cias);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Hospedagem</title>
    <link rel="stylesheet" href="css/cad.css">
</head>

<body>

    <header>
        <h1>Atualizar Hospedagem</h1>
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

            if ($id != "") {
                mostrarFormularioEdicao($id);
            } 
            ?>

        </div>
    </div>
    <a href="attHospedagem.php"><button>Voltar</button></a>

    <footer>
        <p>Vinicios 2025</p>
    </footer>

    <script src="js/mensagem.js"></script>
    <script>
        sumirMensagem("mensagem", 2000);
    </script>
</body>

</html>