<?php
require_once '../config/db.php';
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

$acao = $_GET['acao'] ?? 'sem acao';

switch ($method) {
    case 'GET':
        if ($acao === 'listar') {
            $sql = "SELECT id_cliente, nome_cliente, telefone_cliente FROM clientes";
            $res = mysqli_query($conn, $sql);
            $clientes = [];

            if ($res) {
                while ($row = mysqli_fetch_assoc($res)) {
                    $clientes[] = $row;
                }
                echo json_encode(["success" => true, "clientes" => $clientes]);
            } else {
                echo json_encode(["success" => false, "message" => "Erro ao buscar clientes: " . mysqli_error($conn)]);
            }
            exit;
        }
        break;

    case 'POST':
        if ($acao === 'cadastrar') {
            $data = json_decode(file_get_contents('php://input'));

            if (!isset($data->nome) || !isset($data->telefone)) {
                echo json_encode(["success" => false, "message" => "Dados incompletos"]);
                exit;
            }

            $nome = $data->nome;
            $telefone = $data->telefone; 

            $sql = "INSERT INTO clientes (nome_cliente, telefone_cliente) VALUES ('$nome', '$telefone')";
            $res = mysqli_query($conn, $sql);

            if ($res) {
                echo json_encode(["success" => true, "message" => "Cliente cadastrado com sucesso"]);
            } else {
                echo json_encode(["success" => false, "message" => "Erro ao cadastrar cliente"]);
            }
            exit;
        }
        break;

    case 'PUT':
        if ($acao === 'editar') {
            $data = json_decode(file_get_contents('php://input'));

            if (!isset($data->id) || !isset($data->nome) || !isset($data->telefone)) {
                echo json_encode(["success" => false, "message" => "ID, nome e telefone são obrigatórios"]);
                exit;
            }

            $id = $data->id;
            $nome = $data->nome;
            $telefone = $data->telefone;

            $sql = "UPDATE clientes SET nome_cliente = '$nome', telefone_cliente = '$telefone' WHERE id_cliente = '$id'";
            $res = mysqli_query($conn, $sql);

            if ($res) {
                echo json_encode(["success" => true, "message" => "Cliente atualizado com sucesso"]);
            } else {
                echo json_encode(["success" => false, "message" => "Erro ao atualizar cliente"]);
            }
            exit;
        }
        break;

    default:
        echo json_encode(["success" => false, "message" => "Método não suportado"]);
        break;
}