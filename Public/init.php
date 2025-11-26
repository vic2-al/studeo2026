<?php
// init.php - Configurado para InfinityFree
header('Content-Type: text/html; charset=utf-8');

try {
    // No InfinityFree, o SQLite funciona na pasta local
    $db_path = __DIR__ . '/agendamentos.db';
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Tabela de Serviços
    $db->exec("CREATE TABLE IF NOT EXISTS servicos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        descricao TEXT,
        preco DECIMAL(10,2) NOT NULL,
        duracao_minutos INTEGER DEFAULT 60,
        ativo BOOLEAN DEFAULT 1,
        data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Tabela de Técnicas
    $db->exec("CREATE TABLE IF NOT EXISTS tecnicas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        especialidade TEXT,
        telefone TEXT,
        email TEXT,
        ativo BOOLEAN DEFAULT 1,
        data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Tabela de Clientes
    $db->exec("CREATE TABLE IF NOT EXISTS clientes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        telefone TEXT,
        email TEXT UNIQUE,
        data_nascimento DATE,
        observacoes TEXT,
        data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Tabela de Agendamentos
    $db->exec("CREATE TABLE IF NOT EXISTS agendamentos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        cliente_id INTEGER,
        servico_id INTEGER,
        tecnica_id INTEGER,
        data_agendamento DATE NOT NULL,
        hora TIME NOT NULL,
        status TEXT DEFAULT 'agendado',
        observacoes TEXT,
        valor_total DECIMAL(10,2),
        data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (cliente_id) REFERENCES clientes(id),
        FOREIGN KEY (servico_id) REFERENCES servicos(id),
        FOREIGN KEY (tecnica_id) REFERENCES tecnicas(id)
    )");

    // Dados iniciais
    $db->exec("INSERT OR IGNORE INTO servicos (nome, descricao, preco, duracao_minutos) VALUES 
        ('Manicure Básica', 'Corte, lixa e esmaltação básica', 35.00, 30),
        ('Manicure Completa', 'Corte, lixa, cutículas e esmaltação', 45.00, 45),
        ('Pedicure Básica', 'Pedicure básica', 40.00, 40),
        ('Pedicure Completa', 'Pedicure completa com hidratação', 55.00, 60),
        ('Alongamento em Gel', 'Alongamento de unhas em gel', 85.00, 90),
        ('Spa das Mãos', 'Tratamento completo para mãos', 65.00, 60)
    ");

    $db->exec("INSERT OR IGNORE INTO tecnicas (nome, especialidade, telefone, email) VALUES 
        ('Camila', 'Alongamentos', '(11) 99999-1111', 'camila@nailstudio.com'),
        ('Juliana', 'Design Artístico', '(11) 99999-2222', 'juliana@nailstudio.com'),
        ('Fernanda', 'Pedicure', '(11) 99999-3333', 'fernanda@nailstudio.com')
    ");

    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Configuração - NailStudio</title>
        <meta charset='UTF-8'>
        <style>
            body { 
                font-family: Arial, sans-serif; 
                margin: 40px; 
                background: #1a1a2e; 
                color: #f8f7ff; 
            }
            .container { 
                max-width: 600px; 
                margin: 0 auto; 
                background: #2d2b55; 
                padding: 30px; 
                border-radius: 12px; 
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            }
            .success { 
                color: #80ed99; 
                font-weight: bold;
                padding: 15px;
                background: rgba(128, 237, 153, 0.1);
                border-radius: 8px;
                border-left: 4px solid #80ed99;
            }
            .warning { 
                color: #ffd166; 
                padding: 15px;
                background: rgba(255, 209, 102, 0.1);
                border-radius: 8px;
                border-left: 4px solid #ffd166;
            }
            a { 
                display: inline-block; 
                margin: 10px 5px; 
                padding: 12px 20px; 
                background: #d4aafc; 
                color: white; 
                text-decoration: none; 
                border-radius: 6px; 
                font-weight: bold;
            }
            .step {
                background: rgba(255,255,255,0.05);
                padding: 15px;
                border-radius: 8px;
                margin: 15px 0;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>🎯 NailStudio - Configuração</h1>
            
            <div class='success'>
                ✅ Banco de dados criado com sucesso!
            </div>
            
            <div class='step'>
                <h3>📊 Banco de Dados:</h3>
                <p><strong>Arquivo:</strong> $db_path</p>
                <p><strong>Tabelas criadas:</strong> servicos, tecnicas, clientes, agendamentos</p>
            </div>
            
            <div class='warning'>
                <h3>⚠️ Importante para InfinityFree:</h3>
                <p>Após testar, <strong>delete este arquivo (init.php)</strong> por segurança!</p>
            </div>
            
            <div class='step'>
                <h3>🚀 Próximo Passo:</h3>
                <p>Acesse o sistema principal e faça login como administrador:</p>
                <a href='index.html'>👉 Entrar no NailStudio</a>
            </div>
            
            <div class='step'>
                <h3>🔑 Credenciais de Teste:</h3>
                <p><strong>Admin:</strong> admin@nailstudio.com / admin123</p>
                <p><strong>Cliente:</strong> Qualquer email/senha no cadastro</p>
            </div>
        </div>
    </body>
    </html>";

} catch (PDOException $e) {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Erro - NailStudio</title>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; background: #1a1a2e; color: #f8f7ff; }
            .error { 
                background: rgba(255, 107, 107, 0.1); 
                padding: 20px; 
                border-radius: 8px; 
                border-left: 4px solid #ff6b6b;
            }
        </style>
    </head>
    <body>
        <div style='max-width: 600px; margin: 0 auto;'>
            <h1>❌ Erro na Configuração</h1>
            <div class='error'>
                <h3>Erro no Banco de Dados:</h3>
                <p><strong>" . $e->getMessage() . "</strong></p>
                <p>O InfinityFree pode não suportar SQLite. Verifique as configurações do seu hosting.</p>
            </div>
        </div>
    </body>
    </html>";
}
?>