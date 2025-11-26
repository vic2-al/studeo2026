<?php
require 'vendor/autoload.php';

// Carregar variáveis de ambiente do arquivo .env
if (file_exists('.env')) {
    $env = parse_ini_file('.env');
} else {
    die("❌ Arquivo .env não encontrado. Copie .env.example para .env e configure.\n");
}

return
    [
        'paths' => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
            'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
        ],
        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_environment' => 'development',
            'production' => [
                'adapter' => 'mysql',
                'host' => $env['DB_HOST'] ?? 'localhost',
                'name' => $env['DB_NAME'] ?? 'studeo2026',
                'user' => $env['DB_USER'] ?? 'root',
                'pass' => $env['DB_PASS'] ?? '',
                'port' => '3306',
                'charset' => 'utf8',
            ],
            'development' => [
                'adapter' => 'mysql',
                'host' => $env['DB_HOST'] ?? 'localhost',
                'name' => $env['DB_NAME'] ?? 'studeo2026',
                'user' => $env['DB_USER'] ?? 'root',
                'pass' => $env['DB_PASS'] ?? '',
                'port' => '3306',
                'charset' => 'utf8',
            ],
            'testing' => [
                'adapter' => 'mysql',
                'host' => $env['DB_HOST'] ?? 'localhost',
                'name' => $env['DB_NAME'] ?? 'studeo2026_test',
                'user' => $env['DB_USER'] ?? 'root',
                'pass' => $env['DB_PASS'] ?? '',
                'port' => '3306',
                'charset' => 'utf8',
            ]
        ],
        'version_order' => 'creation'
    ];