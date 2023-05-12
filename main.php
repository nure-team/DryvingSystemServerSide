<?php
//session_start();
require_once "blocks/header.php";
require_once "system/configuration.php";
require_once "app/Main_page.php";
global $mysqli;

$main_page = new Main_page();
$cards = $main_page->showCards();

$result = $mysqli->query("SELECT id FROM tests");
$ids = array();

while ($row = $result->fetch_assoc()) {
    $ids[] = $row["id"];
}
$random_id = $ids[mt_rand(0, count($ids) - 1)];
$mysqli->query("UPDATE main_blocks SET link = '/?page=test&id=$random_id' WHERE main_blocks.header = 'Тести ПДР онлайн'");
?>

<div class="container">
    <div class="head">
        <h1>Автошкола "Кривий рух"</h1>
        <p class="subheader">Автошкола "Кривий рух" - це провідна онлайн-автошкола, яка допомагає майбутнім водіям отримати необхідні навички для безпечного та впевненого керування автомобілем.</p>
    </div>
    <?php
    foreach ($cards as $card) {
    ?>
        <div id="block">
            <img class="block_img" src="<?php echo $card->image; ?>" alt=""/>
            <div>
                <h3><a href="<?php echo $card->link; ?>" style="color: #0e97fa;"><?php echo $card->header; ?></a></h3>
                <p><?php echo $card->description . "<br/>"; ?></p>
            </div>
        </div>
    <?php } ?>
</div>
<?php require_once "blocks/footer.php"; ?>
