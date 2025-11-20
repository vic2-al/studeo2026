<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$method = $_SERVER['REQUEST_METHOD'];
$table = $_GET['table'] ?? '';
$id = $_GET['id'] ?? '';

try {
    $db = DatabaseConfig::getConnection();
    
    switch ($method) {
        case 'GET':
            if ($table && $id) {
                $stmt = $db->prepare("SELECT * FROM $table WHERE id = ?");
                $stmt->execute([$id]);
                $result = $stmt->fetch();
            } elseif ($table) {
                $stmt = $db->query("SELECT * FROM $table ORDER BY id DESC");
                $result = $stmt->fetchAll();
            } else {
                $result = ['error' => 'Tabela não especificada'];
            }
            break;
            
        case 'POST':
            if ($table) {
                $input = json_decode(file_get_contents('php://input'), true);
                if (!$input) {
                    throw new Exception('Dados JSON inválidos');
                }
                
                $columns = implode(', ', array_keys($input));
                $placeholders = ':' . implode(', :', array_keys($input));
                
                $stmt = $db->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");
                $stmt->execute($input);
                $result = ['id' => $db->lastInsertId(), 'message' => 'Item criado com sucesso'];
            }
            break;
            
        case 'PUT':
            if ($table && $id) {
                $input = json_decode(file_get_contents('php://input'), true);
                if (!$input) {
                    throw new Exception('Dados JSON inválidos');
                }
                
                $set = [];
                foreach ($input as $key => $value) {
                    $set[] = "$key = :$key";
                }
                $setClause = implode(', ', $set);
                
                $stmt = $db->prepare("UPDATE $table SET $setClause WHERE id = :id");
                $input['id'] = $id;
                $stmt->execute($input);
                $result = ['message' => 'Item atualizado com sucesso'];
            }
            break;
            
        case 'DELETE':
            if ($table && $id) {
                $stmt = $db->prepare("DELETE FROM $table WHERE id = ?");
                $stmt->execute([$id]);
                $result = ['message' => 'Item excluído com sucesso'];
            }
            break;
            
        default:
            http_response_code(405);
            $result = ['error' => 'Método não permitido'];
    }
    
    echo json_encode($result ?? []);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>