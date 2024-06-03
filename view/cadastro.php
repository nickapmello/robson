<?php
include '../model/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografar senha

    $sql = "INSERT INTO users (nome, cpf, email, telefone, senha) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nome, $cpf, $email, $telefone, $senha);

    if ($stmt->execute()) {
        $idusuario = $stmt->insert_id;
        // Gerar token para o novo usuário
        $token = md5(uniqid(mt_rand(), true));
        $sql = "INSERT INTO login_control (idusuario, token, criado, expira) VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 12 HOUR))";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $idusuario, $token);
        $stmt->execute();

        echo "Usuário cadastrado com sucesso! Token: $token";
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Mello Register</title>
    <meta charset="UTF-8">
    <link rel="icon" href="favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f7f7f7;
            font-family: Arial, sans-serif;
        }
        .card {
            background-color: #ffffff;
            border-radius: 20px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            color: #333333;
        }
        .form-floating label {
            color: #666666;
        }
        .btn-cancel {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            text-transform: none;
            transition: all 0.3s ease;
        }
        .btn-cancel:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-login {
            background-color: #2ecc71;
            border-color: #2ecc71;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background-color: #27ae60;
            border-color: #27ae60;
        }
        .btn-login:focus {
            box-shadow: 0 0 0 0.25rem rgba(46, 204, 113, 0.5);
        }
        .small {
            color: #999999;
        }
    </style>
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-xl-9 mx-auto">
        <div class="card flex-row my-5 border-0 shadow rounded-3 overflow-hidden">
          <div class="card-body p-4 p-sm-5">
            <h2 class="card-title text-center mb-5 fw-light fs-4">Cadastro de Usuário</h2>
            <form method="POST">
              <div class="row mb-3">
                <div class="col-md-8">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInputUsername" name="nome" placeholder="myusername" required autofocus>
                    <label for="floatingInputUsername">Nome</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInputCpf" name="cpf" placeholder="000.000.000-00" required autofocus>
                    <label for="floatingInputCpf">CPF</label>
                  </div>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-8">
                  <div class="form-floating">
                    <input type="email" class="form-control" id="floatingInputEmail" name="email" placeholder="name@example.com">
                    <label for="floatingInputEmail">Email</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="floatingInputTelefone" name="telefone" placeholder="+55" required autofocus>
                    <label for="floatingInputUsername">Telefone</label>
                  </div>
                </div>
              </div>


              <div class="row mb-3">
                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" name="senha" placeholder="Password">
                    <label for="floatingPassword">Senha</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPasswordConfirm" placeholder="Confirm Password">
                    <label for="floatingPasswordConfirm">Confirmar Senha</label>
                  </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                  <div class="d-grid gap-2 d-md-block">
                    <button class="btn btn-cancel fw-bold me-md-2" type="button">Cancelar</button>
                    <button class="btn btn-login fw-bold" type="submit">GRAVAR</button>
                  </div>
                </div>
              </div>

              <a class="d-block text-center small" href="#">Já possui uma conta? Logar</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
