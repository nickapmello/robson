<?php
include '../model/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT id, senha FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($senha, $row['senha'])) {
            $idusuario = $row['id'];

            // Verificar se já tem token válido
            $sql = "SELECT token FROM login_control WHERE idusuario = ? AND expira > NOW() ORDER BY criado DESC LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $idusuario);
            $stmt->execute();
            $result = $stmt->get_result();
            $log_acesso = $result->fetch_assoc();
            if (isset($log_acesso['token'])) {
                $token = $log_acesso['token'];
                $response = ["status" => "1", "msg" => "Login bem-sucedido! Token válido.", "token" => $token];
            } else {
                // Gerar novo token
                $token = md5(uniqid(mt_rand(), true));
                $sql = "INSERT INTO login_control (idusuario, token, criado, expira) VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 12 HOUR))";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("is", $idusuario, $token);
                $stmt->execute();
                $response = ["status" => "1", "msg" => "Login bem-sucedido! Novo token gerado.", "token" => $token];
            }
        } else {
            $response = ["status" => "0", "msg" => "Senha incorreta!"];
        }
    } else {
        $response = ["status" => "0", "msg" => "Usuário não encontrado!"];
    }

    echo json_encode($response);
    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>Mello Login</title>
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
    </style>
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card border-0 shadow rounded-3 my-5">
          <div class="card-body p-4 p-sm-5">
            <h5 class="card-title text-center mb-5 fw-light fs-5">Login</h5>
            <form method="POST">
              <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com">
                <label for="floatingInput">Email</label>
              </div>
              <div class="form-floating mb-3">
                <input type="password" class="form-control" id="floatingPassword" name="senha" placeholder="Password">
                <label for="floatingPassword">Senha</label>
              </div>

              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="" id="rememberPasswordCheck">
                <label class="form-check-label" for="rememberPasswordCheck">
                    Lembrar senha
                </label>
              </div>
              <div class="d-grid">
                <button class="btn btn-primary btn-login text-uppercase fw-bold" type="submit">Entrar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
