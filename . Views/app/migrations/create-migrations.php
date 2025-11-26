<?php
echo "🚀 CRIANDO MIGRATIONS PARA AS 4 TABELAS...\n";

$migrations = [
    '20240101000001_create_categories_table.php' => '<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCategoriesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table(\'categories\');
        $table->addColumn(\'name\', \'string\', [\'limit\' => 100])
                ->addColumn(\'description\', \'text\', [\'null\' => true])
                ->addColumn(\'color\', \'string\', [\'limit\' => 7, \'default\' => \'#007bff\'])
                ->addColumn(\'created_at\', \'datetime\')
                ->addColumn(\'updated_at\', \'datetime\', [\'null\' => true])
                ->create();
    }
}',

    '20240101000002_create_instructors_table.php' => '<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateInstructorsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table(\'instructors\');
        $table->addColumn(\'name\', \'string\', [\'limit\' => 100])
                ->addColumn(\'email\', \'string\', [\'limit\' => 100])
                ->addColumn(\'specialization\', \'string\', [\'limit\' => 100])
                ->addColumn(\'bio\', \'text\', [\'null\' => true])
                ->addColumn(\'photo\', \'string\', [\'null\' => true])
                ->addColumn(\'created_at\', \'datetime\')
                ->addColumn(\'updated_at\', \'datetime\', [\'null\' => true])
                ->create();
    }
}',

    '20240101000003_create_courses_table.php' => '<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCoursesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table(\'courses\');
        $table->addColumn(\'name\', \'string\', [\'limit\' => 100])
                ->addColumn(\'description\', \'text\')
                ->addColumn(\'duration\', \'integer\', [\'comment\' => \'Duração em horas\'])
                ->addColumn(\'price\', \'decimal\', [\'precision\' => 10, \'scale\' => 2])
                ->addColumn(\'instructor_id\', \'integer\', [\'null\' => true])
                ->addColumn(\'category_id\', \'integer\', [\'null\' => true])
                ->addColumn(\'created_at\', \'datetime\')
                ->addColumn(\'updated_at\', \'datetime\', [\'null\' => true])
                ->addForeignKey(\'instructor_id\', \'instructors\', \'id\', [
                  \'delete\' => \'SET_NULL\',
                  \'update\' => \'CASCADE\'
              ])
              ->addForeignKey(\'category_id\', \'categories\', \'id\', [
                  \'delete\' => \'SET_NULL\', 
                  \'update\' => \'CASCADE\'
              ])
              ->create();
    }
}',

    '20240101000004_create_enrollments_table.php' => '<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateEnrollmentsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table(\'enrollments\');
        $table->addColumn(\'student_id\', \'integer\')
                ->addColumn(\'course_id\', \'integer\')
                ->addColumn(\'enrolled_at\', \'datetime\')
                ->addColumn(\'status\', \'string\', [\'limit\' => 20, \'default\' => \'active\'])
                ->addColumn(\'progress\', \'integer\', [\'default\' => 0, \'comment\' => \'Progresso em porcentagem\'])
                ->addColumn(\'created_at\', \'datetime\')
                ->addColumn(\'updated_at\', \'datetime\', [\'null\' => true])
                ->addForeignKey(\'student_id\', \'users\', \'id\', [
                  \'delete\' => \'CASCADE\',
                  \'update\' => \'CASCADE\'
              ])
              ->addForeignKey(\'course_id\', \'courses\', \'id\', [
                  \'delete\' => \'CASCADE\',
                  \'update\' => \'CASCADE\'
              ])
              ->create();
    }
}'
];

foreach ($migrations as $file => $content) {
    $filepath = 'db/migrations/' . $file;

    if (!file_exists($filepath)) {
        file_put_contents($filepath, $content);
        echo "✅ Criado: $filepath\n";
    } else {
        echo "⚠️  Já existe: $filepath\n";
    }
}

echo "\n🎯 MIGRATIONS CRIADAS COM SUCESSO!\n";
echo "👉 Execute: vendor/bin/phinx migrate\n";