<?php
session_start();
require_once "config.php";
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if (isset($_POST['cadastrarCupom'])) {
    $codigo = $_POST['codigo'];
    $cpf = $_POST['cpf'];
    $valor = $_POST['valor'];
    $loja = $_POST['loja'];
    $dataHoraCompra = $_POST['dataHoraCompra'];
    $status = $_POST['status'];
    $numeroSorte = "";

    if ($valor >= 300.00) {
        $numeroSorte = rand(1000000, 9999999);
    }

    $verificaCupom = $pdo->prepare("SELECT codigo FROM cupons WHERE codigo = :codigo");
    $verificaCupom->bindValue(':codigo', $codigo);
    $verificaCupom->execute();
    if ($verificaCupom->rowCount() > 0) {
        echo "Este cupom já foi cadastrado anteriormente.";
    } else {
        $cadastraCupom = $pdo->prepare("INSERT INTO cupons (codigo, cpf, valor, loja, dataHoraCompra, status, numeroSorte) VALUES (:codigo, :cpf, :valor, :loja, :dataHoraCompra, :status, :numeroSorte)");
        $cadastraCupom->bindValue(':codigo', $codigo);
        $cadastraCupom->bindValue(':cpf', $cpf);
        $cadastraCupom->bindValue(':valor', $valor);
        $cadastraCupom->bindValue(':loja', $loja);
        $cadastraCupom->bindValue(':dataHoraCompra', $dataHoraCompra);
        $cadastraCupom->bindValue(':status', $status);
        $cadastraCupom->bindValue(':numeroSorte', $numeroSorte);
        if ($cadastraCupom->execute()) {
            echo "Cupom cadastrado com sucesso.";
        } else {
            echo "Erro ao cadastrar o cupom.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <script src="js/validarCPF.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <h1>Seja bem vindo Administrador do Independência Shopping!</h1>
    <P>Caso deseje cadastrar um cupom, preencha o formulário abaixo.</P>
    <div class="wrapper">
        <h2>Cadastrar Cupom</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Código</label>
                <input type="text" name="codigo" class="form-control">
            </div>
            <div class="form-group">
                <label>CPF</label>
                <input type="text" name="cpf" class="form-control">
            </div>
            <div class="form-group">
                <label>Valor</label>
                <input type="text" name="valor" class="form-control">
            </div>
            <div class="form-group">
                <label>Loja</label>
                <input type="text" name="loja" class="form-control">
            </div>
                <div class="form-group">
                <label>Data e Hora da Compra</label>
                </div>
            <div class="form-group">
                <input type="text" name="dataHoraCompra" class="form-control">
            </div>
                <div class="form-group">
                <label>Status</label>
            </div>
            <div class="form-group">
                <input type="text" name="status" class="form-control">
            </div>
                <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Cadastrar Cupom" name="cadastrarCupom">
            </div>
        </form>
    </div>
    <p>Para fazer consulta no banco de dados do shopping, clique no botão abaixo.</p>
    <p>
        <a href="consulta.php" class="btn btn-primary ml-3">Consultar Dados</a>
    
        <a href="logout.php" class="btn btn-danger ml-3">Sair da conta</a>
    </p>
</body>
</html>