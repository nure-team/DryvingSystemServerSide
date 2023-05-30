<?php
global $mysqli;
require_once "app/Main_page.php";
$user_password = $_GET['id'];
$main_page = new Main_page();
$partitions = $main_page->showPDRcontent();

if(isset($_GET['action']) && $_GET['action'] == "delete") {
    $mysqli->query("DELETE FROM `pdr_partitions` WHERE partition_id='" . $_GET['p_id'] . "'") or die($mysqli->error);
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Success!</strong> Розділ був видалений!
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
}

if (isset($_POST['partition_name'])) {
    $id = $_POST['partition_id'];
    $partition = $_POST['partition_name'];
    $mysqli->query("INSERT INTO `pdr_partitions` (partition_id, title, link) VALUES (NULL, '$partition', '/?page=pdr_content&id=$id')") or die($mysqli->error);
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> Новий розділ був доданий!
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
}
?>

<div class="container d-flex justify-content-left mt-5 mb-5">
        <div class="card col-9 text-left" style="width: 18rem;">
            <div class="card-header">
                <h4>Розділи пдр</h4>
            </div>
            <ul class="list-group list-group-flush">
                <?php foreach ($partitions as $partition) { ?>
                    <li class="list-group-item"><a href="/?page=pdr_text_edit&pdr_id=<?= $partition->partition_id ?>"><?php echo $partition->title; ?></a> &nbsp; <a type="button" href="/?page=content_edit&id=<?= $user_password; ?>&action=delete&p_id=<?= $partition->partition_id; ?>" class="text-danger">Delete</a></li>
                <?php } ?>
            </ul>
    </div>

</div>
<div class="d-flex justify-content-center">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">
       Додати новий розділ
    </button>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Впишіть id</label>
                        <input type="text" class="form-control" id="recipient-name" name="partition_id">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Впишіть назву розділу</label>
                        <input type="text" class="form-control" id="recipient-name" name="partition_name">
                    </div>
                    <button type="submit" class="btn btn-primary">Додати</button>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
