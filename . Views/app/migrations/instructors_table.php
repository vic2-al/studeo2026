<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateInstructorsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('instructors');
        $table->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('email', 'string', ['limit' => 100])
            ->addColumn('specialization', 'string', ['limit' => 100])
            ->addColumn('bio', 'text', ['null' => true])
            ->addColumn('photo', 'string', ['null' => true])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->create();
    }
}