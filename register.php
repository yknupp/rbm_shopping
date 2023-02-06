<?php
require_once "config.php";
 
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
$cpf_err = $cpf = "";
$data_nascimento_err = $data_nascimento = "";
$sexo_err = $sexo = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["username"]))){
        $username_err = "Por favor coloque um nome de usuário.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "O nome de usuário pode conter apenas letras, números e sublinhados.";
    } else{
        $sql = "SELECT id FROM users WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            $param_username = trim($_POST["username"]);
            
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "Este nome de usuário já está em uso.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }

            unset($stmt);
        }
    }

    if(empty(trim($_POST["cpf"]))){
        $cpf_err = "Por favor coloque um CPF.";
    } elseif(!preg_match('/^[0-9]+$/', trim($_POST["cpf"]))){
        $cpf_err = "O CPF pode conter apenas números.";
    } else{
        $sql = "SELECT id FROM users WHERE cpf = :cpf";
        
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":cpf", $param_cpf, PDO::PARAM_STR);
            
            $param_cpf = trim($_POST["cpf"]);
            
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $cpf_err = "Este CPF já está em uso.";
                } else{
                    $cpf = trim($_POST["cpf"]);
                }
            } else{
                echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }

            unset($stmt);
        }
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor insira uma senha.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "A senha deve ter pelo menos 6 caracteres.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Por favor, confirme a senha.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "A senha não confere.";
        }
    }

    if(empty(trim($_POST["data_nascimento"]))){
        $data_nascimento_err = "Por favor coloque uma data de nascimento.";
    } else{
        $param_data_nascimento = trim($_POST["data_nascimento"]);
    }

    if(empty(trim($_POST['sexo']))){
        $sexo_err = "Por favor selecione o sexo.";
    } else{
        $param_sexo = trim($_POST["sexo"]);
    }
    
    if(empty($username_err) && empty($password_err) && empty($cpf_err) && empty($data_nascimento_err) && empty($sexo_err) && empty($confirm_password_err)){
        $tipo_usuario = (int) trim($_POST['tipo_usuario']);

        $sql = "INSERT INTO users (username, password, cpf, sexo, data_nascimento, tipo_usuario) VALUES (:username, :password, :cpf, :sexo, :data_nascimento, :tipo_usuario)";
         
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":cpf", $param_cpf, PDO::PARAM_STR);
            $stmt->bindParam(":data_nascimento", $param_data_nascimento, PDO::PARAM_STR);
            $stmt->bindParam(":sexo", $param_sexo, PDO::PARAM_STR);
            $stmt->bindParam(":tipo_usuario", $tipo_usuario , PDO::PARAM_STR);
            
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            var_dump($sql);
            var_dump($stmt);
            
            if($stmt->execute()){
                header("location: login.php");
            } else{
                echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }

            unset($stmt);
        }
    }
    
    unset($pdo);
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
    <div class="wrapper">
        <h2>Cadastro</h2>
        <p>Por favor, preencha este formulário para criar uma conta.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Tipo de usuário</label>
                <select name="tipo_usuario" class="form-control" >
                    <option value="0">Administrador</option>
                    <option value="1">Usuário</option>
                </select>
            </div>
            <div class="form-group">
                <label>Nome do usuário</label>
                <input type="text" name="username" id="username" class="form-control  <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label>CPF</label>
                <input type="text" name="cpf" id="cpf" class="form-control  <?php echo (!empty($cpf_err)) ? 'is-invalid' : ''; ?>" >
                <span class="invalid-feedback"><?php echo $cpf_err; ?></span>
            </div>
            <div class="form-group">
                <label>Sexo</label>
                <select name="sexo" class="form-control">
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                </select>
                <span class="invalid-feedback"><?php echo $sexo_err; ?></span>
            </div>
            <div class="form-group">
                <label>Data de nascimento</label>
                <input type="date" name="data_nascimento" class="form-control">
                <span class="invalid-feedback"><?php echo $data_nascimento_err; ?></span>
            </div>
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="password" id="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirme a senha</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Criar Conta">
                <input type="reset" class="btn btn-secondary ml-2" value="Apagar Dados">
            <p>Já tem uma conta? <a href="login.php">Entre aqui</a>.</p>
        </form>
    </div>    
</body>
</html>