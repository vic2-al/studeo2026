<?php include 'views/partials/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Nova Categoria</h1>
</div>

<form method="POST" action="/admin/categories/store">
    <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Descrição</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
    </div>

    <div class="mb-3">
        <label for="color" class="form-label">Cor</label>
        <input type="color" class="form-control" id="color" name="color" value="#007bff">
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="/admin/categories" class="btn btn-secondary">Cancelar</a>
</form>

<?php include 'views/partials/footer.php'; ?>