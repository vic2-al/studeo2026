<?php include 'views/partials/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Categorias</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/admin/categories/create" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Nova Categoria
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Cor</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= $category->id ?></td>
                    <td><?= htmlspecialchars($category->name) ?></td>
                    <td><?= htmlspecialchars($category->description) ?></td>
                    <td>
                        <span class="badge" style="background-color: <?= $category->color ?>">
                            <?= $category->color ?>
                        </span>
                    </td>
                    <td>
                        <a href="/admin/categories/edit/<?= $category->id ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="/admin/categories/delete/<?= $category->id ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Tem certeza?')">
                            <i class="fas fa-trash"></i> Excluir
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'views/partials/footer.php'; ?>