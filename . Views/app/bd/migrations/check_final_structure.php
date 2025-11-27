<?php
echo "🔍 VERIFICAÇÃO FINAL DA ESTRUTURA\n";
echo "================================\n\n";

$requiredFiles = [
    '.env.example' => 'Template de configuração',
    '.env' => 'Configurações do ambiente',
    'composer.json' => 'Dependências do projeto',
    'phinx.php' => 'Configuração de migrations',
    'routes.php' => 'Rotas da aplicação'
];

echo "📁 ARQUIVOS ESSENCIAIS:\n";
$allFilesExist = true;
foreach ($requiredFiles as $file => $description) {
    if (file_exists($file)) {
        echo "✅ $file - $description\n";
    } else {
        echo "❌ $file - $description - FALTANDO!\n";
        $allFilesExist = false;
    }
}

echo "\n📂 DIRETÓRIOS:\n";
$directories = [
    'app/Controllers',
    'app/Models',
    'app/Repositories',
    'app/Services',
    'views/admin',
    'db/migrations'
];

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $fileCount = count(glob("$dir/*"));
        echo "✅ $dir ($fileCount arquivos)\n";
    } else {
        echo "❌ $dir - FALTANDO!\n";
        $allFilesExist = false;
    }
}

echo "\n📊 MIGRATIONS:\n";
$migrations = glob('db/migrations/*.php');
if (count($migrations) >= 4) {
    echo "✅ " . count($migrations) . " migrations encontradas\n";
} else {
    echo "❌ Migrations insuficientes: " . count($migrations) . " encontradas\n";
    $allFilesExist = false;
}

if ($allFilesExist) {
    echo "\n🎉 ESTRUTURA COMPLETA! Projeto pronto para uso.\n";
} else {
    echo "\n⚠️  Alguns arquivos/diretórios estão faltando.\n";
}