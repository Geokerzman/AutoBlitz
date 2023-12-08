<?php require APPROOT . '/views/inc/header.php'; ?>
<?php flash('post_message'); ?>
<div class="row mb-3">
    <div class="col-md-3 mx-auto">
        <label for="allBrands">Все марки:</label>
        <select id="allBrands" class="form-select form-control">
            <option value="brand1">Марка 1</option>
            <option value="brand2">Марка 2</option>
            <!-- Добавьте дополнительные опции по мере необходимости -->
        </select>
    </div>
    <div class="col-md-3 mx-auto">
        <label for="year">Год выпуска:</label>
        <select id="year" class="form-select form-control">
            <option value="2020">2020</option>
            <option value="2021">2021</option>
            <!-- Добавьте дополнительные опции по мере необходимости -->
        </select>
    </div>
    <div class="col-md-3 mx-auto">
        <label for="allModels">Все модели:</label>
        <select id="allModels" class="form-select form-control">
            <option value="model1">Модель 1</option>
            <option value="model2">Модель 2</option>
            <!-- Добавьте дополнительные опции по мере необходимости -->
        </select>
    </div>
</div>
<div class="row mb-3 mx-auto">
    <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/posts/add" class="btn btn-primary pull-right justify-content-end">
            <i class="fa fa-pencil"></i> Add Post
        </a>
    </div>
</div>
<?php foreach ($data['posts'] as $post) : ?>
    <div class="card card-body mb-3">
        <!-- ... (existing code) ... --> 
        <div class="card card-body mb-3">
            <div class="upper-bar">
                <i class="fa fa-circle" style="color: #029402"></i>
                <i class="fa fa-circle" style="color: #f1bf3f"></i>
                <i class="fa fa-circle" style="color: #b70101"></i>
            </div>
            <h4 class="card-title"><?php echo $post->title; ?></h4>
            <div class="p-2 mb-3">
                Written by <?php echo $post->name; ?> on <?php echo $post->postCreated; ?>
            </div>
            <?php if ($post->image) : ?>
            <img src="<?php echo URLROOT . $post->image; ?>" class="img-fluid" alt="Post Image">
        <?php endif; ?>
            <p class="card-text"><?php echo $post->body; ?></p>
            <a href="<?php echo URLROOT; ?>/posts/show/<?php echo $post->postId; ?>" class="btn btn-dark">More</a>
        </div>
    </div>
<?php endforeach; ?>




<!-- Pagination -->
<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <?php if ($data['currentPage'] > 1) : ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo URLROOT; ?>/posts/index/<?php echo $data['currentPage'] - 1; ?>">Previous</a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $data['totalPages']; $i++) : ?>
            <li class="page-item <?php echo ($i == $data['currentPage']) ? 'active' : ''; ?>">
                <a class="page-link" href="<?php echo URLROOT; ?>/posts/index/<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($data['currentPage'] < $data['totalPages']) : ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo URLROOT; ?>/posts/index/<?php echo $data['currentPage'] + 1; ?>">Next</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<?php
// Добавьте проверку на существование переменной
if (isset($data['totalPosts'])) {
    echo "Total Posts: " . $data['totalPosts'] . "<br>";
} else {
    echo "Total Posts variable is not set.<br>";
}
?>

<?php require APPROOT . '/views/inc/footer.php'; ?>
