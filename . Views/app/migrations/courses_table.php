<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCoursesTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('courses');
        $table->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('description', 'text')
            ->addColumn('duration', 'integer', ['comment' => 'Duração em horas'])
            ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addColumn('instructor_id', 'integer', ['null' => true])
            ->addColumn('category_id', 'integer', ['null' => true])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addForeignKey('instructor_id', 'instructors', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'CASCADE'
            ])
            ->addForeignKey('category_id', 'categories', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'CASCADE'
            ])
            ->create();
    }
}