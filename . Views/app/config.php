<?php
// config.php - Configurações do MySQL para InfinityFree
class DatabaseConfig
{
    // Configurações InfinityFree
    public static $hostname = 'sql306.infinityfree.com';
    public static $username = 'if0_40162099';
    public static $password = 'HVVPwzMLzpSD';
    public static $database = 'if0_40162099_nailstudio';

    public static function getConnection()
    {
        try {
            $dsn = "mysql:host=" . self::$hostname . ";dbname=" . self::$database . ";charset=utf8mb4";
            $pdo = new PDO($dsn, self::$username, self::$password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (PDOException $e) {
            throw new Exception("Erro de conexão: " . $e->getMessage());
        }
    }
}

// Criar tabelas automaticamente
function createTables()
{
    try {
        $db = DatabaseConfig::getConnection();

        // Tabela de serviços
        $db->exec("CREATE TABLE IF NOT EXISTS servicos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            descricao TEXT,
            preco DECIMAL(10,2) NOT NULL,
            duracao_minutos INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // Tabela de técnicas
        $db->exec("CREATE TABLE IF NOT EXISTS tecnicas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            especialidade VARCHAR(100) NOT NULL,
            telefone VARCHAR(20),
            email VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // Tabela de clientes
        $db->exec("CREATE TABLE IF NOT EXISTS clientes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            telefone VARCHAR(20),
            email VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // Tabela de agendamentos
        $db->exec("CREATE TABLE IF NOT EXISTS agendamentos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            cliente_id INT,
            servico_id INT,
            tecnica_id INT,
            data_agendamento DATE NOT NULL,
            hora TIME NOT NULL,
            status ENUM('pendente','confirmado','cancelado') DEFAULT 'pendente',
            observacoes TEXT,
            valor_total DECIMAL(10,2),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // Inserir dados iniciais
        $servicosExistem = $db->query("SELECT COUNT(*) as count FROM servicos")->fetch()['count'];
        if ($servicosExistem == 0) {
            $db->exec("INSERT INTO servicos (nome, descricao, preco, duracao_minutos) VALUES 
                ('Manicure Básica', 'Corte, lixa e esmaltação básica', 35.00, 30),
                ('Manicure Completa', 'Corte, lixa, cutículas e esmaltação', 45.00, 45),
                ('Pedicure Básica', 'Pedicure básica', 40.00, 40),
                ('Pedicure Completa', 'Pedicure completa com hidratação', 55.00, 60),
                ('Alongamento em Gel', 'Alongamento de unhas em gel', 85.00, 90),
                ('Spa das Mãos', 'Tratamento completo para mãos', 65.00, 60)
            ");
        }

        $tecnicasExistem = $db->query("SELECT COUNT(*) as count FROM tecnicas")->fetch()['count'];
        if ($tecnicasExistem == 0) {
            $db->exec("INSERT INTO tecnicas (nome, especialidade, telefone, email) VALUES 
                ('Camila', 'Alongamentos', '(11) 99999-1111', 'camila@nailstudio.com'),
                ('Juliana', 'Design Artístico', '(11) 99999-2222', 'juliana@nailstudio.com'),
                ('Fernanda', 'Pedicure', '(11) 99999-3333', 'fernanda@nailstudio.com')
            ");
        }

        return ['success' => true, 'message' => 'Tabelas criadas/verificadas com sucesso!'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

// Executar criação de tabelas
createTables();
?>