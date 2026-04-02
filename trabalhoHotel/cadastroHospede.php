<?php
$mensagem = "";
$tipo = "";

if (isset($_POST["botao"])) {

    if (!empty($_POST["cpf"]) && !empty($_POST["nome"]) && !empty($_POST["sobrenome"]) && !empty($_POST["sexo"]) && !empty($_POST["dataNascimento"])) {

        $cpf = $_POST["cpf"];
        $nome = $_POST["nome"];
        $sobrenome = $_POST["sobrenome"];
        $sexo = $_POST["sexo"];
        $dataNascimento = $_POST["dataNascimento"];

        if (strlen($cpf) != 11) {
            $mensagem = "O CPF deve ter exatamente 11 dígitos.";
            $tipo = "erro";
        } elseif ($dataNascimento > date('Y-m-d')) {
            $mensagem = "A data de nascimento não pode ser maior que hoje.";
            $tipo = "erro";
        } else {
            if (cpfExiste($cpf)) {
                $mensagem = "CPF já cadastrado!";
                $tipo = "erro";
            } else {
                $inserido = inserirHospede($cpf, $nome, $sobrenome, $sexo, $dataNascimento);
                if ($inserido) {
                    $mensagem = "Cadastro realizado com sucesso!";
                    $tipo = "sucesso";
                } else {
                    $mensagem = "Erro ao cadastrar hóspede!";
                    $tipo = "erro";
                }
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

function cpfExiste($cpf)
{
    $conexao = conectar("bdhotel");
    $sql = "SELECT id FROM hospede WHERE cpf = :cpf";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(":cpf", $cpf);
    $stmt->execute();

    $existe = false;
    if ($stmt->fetch()) {
        $existe = true;
    }

    $conexao = encerrar();
    return $existe;
}

function inserirHospede($cpf, $nome, $sobrenome, $sexo, $dataNascimento)
{
    $conexao = conectar("bdhotel");

    $sql_hospede = "INSERT INTO hospede (cpf, nome, sobrenome, sexo, dataNascimento) VALUES (:cpf, :nome, :sobrenome, :sexo, :dataNascimento)";
    $stmt = $conexao->prepare($sql_hospede);
    $stmt->bindValue(":cpf", $cpf);
    $stmt->bindValue(":nome", $nome);
    $stmt->bindValue(":sobrenome", $sobrenome);
    $stmt->bindValue(":sexo", $sexo);
    $stmt->bindValue(":dataNascimento", $dataNascimento);

    $resultado = $stmt->execute();
    $conexao = null;
    return $resultado;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Hóspede</title>
    <link rel="stylesheet" href="css/cadastroHospede.css">
</head>

<body>
    <header>
        <h1>Cadastro</h1>
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

            <form action="" method="post">
                <h3>Dados do Hóspede</h3>

                <label>CPF:</label>
                <input type="text" name="cpf" id="cpf" required><br><br>

                <label>Nome:</label>
                <input type="text" name="nome" id="nome" required><br><br>

                <label>Sobrenome:</label>
                <input type="text" name="sobrenome" id="sobrenome" required><br><br>

                <label>Sexo:</label>
                <div class="linha">
                    <input type="radio" name="sexo" value="M" id="sexoM" required>
                    <label for="sexoM">Masculino</label>

                    <input type="radio" name="sexo" value="F" id="sexoF" required>
                    <label for="sexoF">Feminino</label>
                </div>
                <br>

                <label>Data de Nascimento:</label>
                <input type="date" name="dataNascimento" id="dataNascimento"><br>

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