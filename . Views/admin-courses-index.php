<?php include 'views/partials/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Cursos</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/admin/courses/create" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Novo Curso
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Instrutor</th>
                <th>Duração</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?= $course->id ?></td>
                    <td><?= htmlspecialchars($course->name) ?></td>
                    <td><?= $course->getCategoryName() ?></td>
                    <td><?= $course->getInstructorName() ?></td>
                    <td><?= $course->duration ?>h</td>
                    <td>R$ <?= number_format($course->price, 2, ',', '.') ?></td>
                    <td>
                        <a href="/admin/courses/edit/<?= $course->id ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="/admin/courses/delete/<?= $course->id ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Tem certeza?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'views/partials/footer.php'; ?>