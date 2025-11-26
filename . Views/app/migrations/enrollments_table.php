<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateEnrollmentsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('enrollments');
        $table->addColumn('student_id', 'integer')
            ->addColumn('course_id', 'integer')
            ->addColumn('enrolled_at', 'datetime')
            ->addColumn('status', 'string', ['limit' => 20, 'default' => 'active'])
            ->addColumn('progress', 'integer', ['default' => 0, 'comment' => 'Progresso em porcentagem'])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addForeignKey('student_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->addForeignKey('course_id', 'courses', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->create();
    }
}