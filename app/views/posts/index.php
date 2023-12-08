





<?php require APPROOT . '/views/inc/footer.php'; ?>

<?php require APPROOT . '/views/inc/header.php'; ?>
<?php flash('post_message'); ?>
<div class="row mb-3">
    <div class="col-md-6">
        <h1>Posts</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/posts/add" class="btn btn-primary pull-right">
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
