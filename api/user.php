<?php
require_once '../config/db.php';
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
// echo $method;

$acao = $_GET['acao'] ?? 'sem acao';

switch ($method) {
    case 'POST':

        if ($acao === 'cadastrar') {

            $data = json_decode(file_get_contents('php://input'));

            if (!isset($data->nome) || !isset($data->senha) || !isset($data->email)) {
                echo json_encode(["success" => false, "message" => "Dados incompletos"]);
                exit;
            }

            $nome = $data->nome;
            $email = $data->email;
            $senhaBruta = $data->senha;
            $cargo = $data->cargo ?? 0;
            $ativo = 1;

            if (strlen($senhaBruta) < 8) {
                echo json_encode(["success" => false, "message" => "A senha deve conter no mínimo 8 caracteres"]);
                exit;
            }
            $senha = password_hash($senhaBruta, PASSWORD_DEFAULT);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(["success" => false, "message" => "E-mail inválido"]);
                exit;
            }

            $sql = "SELECT usuarios.email_usuario FROM usuarios WHERE usuarios.email_usuario = '$email'";
            $res = mysqli_query($conn, $sql);

            if (mysqli_num_rows($res) > 0) {
                echo json_encode(["success" => false, "message" => "Este email já está sendo utilizado"]);
                exit;
            }

            $sql = "INSERT INTO usuarios (usuarios.nome_usuario, usuarios.email_usuario, usuarios.senha_usuario, usuarios.cargo_usuario, usuarios.ativo_usuario)
                    VALUES ('$nome','$email','$senha', '$cargo', '$ativo')";
            $res = mysqli_query($conn, $sql);
            if ($res) {
                echo json_encode(["success" => true, "message" => "Usuario cadastrado com sucesso"]);
                exit;
            }

            
        } elseif ($acao === 'login') {
            $data = json_decode(file_get_contents('php://input'));

            
            if (!isset($data->email) || !isset($data->senha)) {
                echo json_encode(["success" => false, "message" => "E-mail e senha são obrigatórios"]);
                exit;
            }

            $email = $data->email;
            $senhaInformada = $data->senha;

            
            $sql = "SELECT * FROM usuarios WHERE email_usuario = '$email' LIMIT 1";
            $res = mysqli_query($conn, $sql);

            if (mysqli_num_rows($res) > 0) {
                $usuario = mysqli_fetch_assoc($res);

                
                if ($usuario['ativo_usuario'] == 0) {
                    echo json_encode(["success" => false, "message" => "Esta conta está desativada"]);
                    exit;
                }

                
                if (password_verify($senhaInformada, $usuario['senha_usuario'])) {
                    
                    echo json_encode([
                        "success" => true,
                        "message" => "Login realizado com sucesso!",
                        "usuario" => [
                            "nome" => $usuario['nome_usuario'],
                            "email" => $usuario['email_usuario'],
                            "cargo" => $usuario['cargo_usuario']
                        ]
                    ]);
                    exit;
                } else {
                    
                    echo json_encode(["success" => false, "message" => "E-mail ou senha incorretos"]);
                    exit;
                }
            } else {
                
                echo json_encode(["success" => false, "message" => "E-mail ou senha incorretos"]);
                exit;
            }
        } else {

            echo json_encode(["success" => false, "message" => "Ação não informada."]);
            exit;
        }

        break;
}
