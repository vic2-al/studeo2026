<?php
echo "🎯 CONFIGURAÇÃO COMPLETA DO PROJETO\n";
echo "===================================\n\n";

// 1. Verificar e criar .env.example
echo "1. VERIFICANDO .env.example...\n";
if (!file_exists('.env.example')) {
    $envExample = '# Configurações do Banco de Dados
DB_HOST=localhost
DB_NAME=studeo2026
DB_USER=root
DB_PASS=

# URL da Aplicação
APP_URL=http://localhost:8001

# Chave CSRF para segurança
CSRF_KEY=change_this_to_a_random_32_character_string

# Ambiente da Aplicação
APP_ENV=development

# Configurações de Sessão
SESSION_NAME=studeo_session
SESSION_LIFETIME=7200

# Debug Mode
DEBUG=true';

    file_put_contents('.env.example', $envExample);
    echo "✅ .env.example criado\n";
} else {
    echo "✅ .env.example já existe\n";
}

// 2. Verificar e criar .env
echo "\n2. VERIFICANDO .env...\n";
if (!file_exists('.env')) {
    copy('.env.example', '.env');
    echo "✅ .env criado a partir do .env.example\n";

    // Gerar chave CSRF
    $csrfKey = 'CSRF_KEY=' . bin2hex(random_bytes(16));
    file_put_contents('.env', "\n$csrfKey", FILE_APPEND);
    echo "✅ Chave CSRF gerada automaticamente\n";
} else {
    echo "✅ .env já existe\n";
}

// 3. Verificar estrutura de pastas
echo "\n3. VERIFICANDO ESTRUTURA DE PASTAS...\n";
$folders = [
    'app/Controllers',
    'app/Models',
    'app/Repositories',
    'app/Services',
    'views/admin/categories',
    'views/admin/instructors',
    'views/admin/courses',
    'views/admin/enrollments',
    'db/migrations'
];

foreach ($folders as $folder) {
    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
        echo "✅ Criada pasta: $folder\n";
    } else {
        echo "✅ Pasta existe: $folder\n";
    }
}

// 4. Verificar migrations
echo "\n4. VERIFICANDO MIGRATIONS...\n";
$migrationFiles = [
    '20240101000001_create_categories_table.php',
    '20240101000002_create_instructors_table.php',
    '20240101000003_create_courses_table.php',
    '20240101000004_create_enrollments_table.php'
];

$migrationsExist = true;
foreach ($migrationFiles as $migration) {
    if (!file_exists("db/migrations/$migration")) {
        $migrationsExist = false;
        break;
    }
}

if (!$migrationsExist) {
    echo "⚠️  Migrations faltando - execute: php create_migrations.php\n";
} else {
    echo "✅ Todas as migrations existem\n";
}

echo "\n🎉 CONFIGURAÇÃO COMPLETA!\n";
echo "👉 Execute agora: composer dump-autoload\n";
echo "👉 Depois: vendor/bin/phinx migrate\n";
echo "👉 Finalmente: php -S localhost:8001 -t public\n";