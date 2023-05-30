<?php
global $mysqli;

$query = mysqli_query($mysqli, "select password from `users` where status='admin'");
$user = mysqli_fetch_assoc($query);

?>
<div class="container d-flex justify-content-center mt-5 mb-5">
<div class="card text-center" style="width: 18rem;">
    <div class="card-header">
        Сторінка адміністратора
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item"><a href="/?page=admin&id=<?= $user['password']; ?>">Додати новий тест</a></li>
        <li class="list-group-item"><a href="/?page=comments_edit&id=<?= $user['password']; ?>">Керувати відгуками</a></li>
        <li class="list-group-item"><a href="/?page=content_edit&id=<?= $user['password']; ?>">Керувати налаштуванням контенту</a></li>
    </ul>
</div>
</div>

<?php
require_once "blocks/footer.php";
?>