<?php include 'views/partials/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Novo Curso</h1>
</div>

<form method="POST" action="/admin/courses/store">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="name" class="form-label">Nome do Curso</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="duration" class="form-label">Duração (horas)</label>
                <input type="number" class="form-control" id="duration" name="duration" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="category_id" class="form-label">Categoria</label>
                <select class="form-select" id="category_id" name="category_id">
                    <option value="">Selecione uma categoria</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category->id ?>"><?= htmlspecialchars($category->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="instructor_id" class="form-label">Instrutor</label>
                <select class="form-select" id="instructor_id" name="instructor_id">
                    <option value="">Selecione um instrutor</option>
                    <?php foreach ($instructors as $instructor): ?>
                        <option value="<?= $instructor->id ?>"><?= htmlspecialchars($instructor->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Preço</label>
        <input type="number" step="0.01" class="form-control" id="price" name="price" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Descrição</label>
        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="/admin/courses" class="btn btn-secondary">Cancelar</a>
</form>

<?php include 'views/partials/footer.php'; ?>