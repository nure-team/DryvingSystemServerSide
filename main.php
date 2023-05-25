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
    <div class="head">
        <h1>Відгуки про автошколу</h1>
        <div class="link_block"><a href="/?page=drivingschool" class="text-primary">Перейти до усіх відгуків</a></div>
    </div>
    <div class="mt-5" id="review_content"></div>
    <div class="head">
        <h1>Тож чого зволікати?</h1>
        <p class="subheader">Час настав, щоб здійснити свою мрію та отримати водійське посвідчення! Початок навчання в автошколі може здатися страшним, але це далеко не так, якщо ви оберете правильну школу. У нашій автошколі ми маємо досвідчених викладачів, які навчать вас усьому необхідному щоб стати безпечним та впевненим водієм. Наші знання будуть цікавими та корисними, а під час навчання ми надаємо всебічну підтримку та допомогу на кожному етапі.</p>
    </div>
    <div class="link_block"><a type="button" class="purple-btn" href="<?php if ((isset($_SESSION["id"]) && isset($_SESSION["sess"])) || (isset($_SESSION['email']) && $_SESSION['uid'])) { echo '/?page=profile_settings'; } else { echo '/login/login.php'; } ?>">Почати навчання</a></div>
</div>
<script>
    $(document).ready(function(){

        var rating_data = 0;

        load_rating_data();

        function load_rating_data() {
            $.ajax({
                url: "/drivingschool/rating_system/submit_rating.php",
                method: "POST",
                data: {action: 'load_data_three'},
                dataType: "JSON",
                success: function (data) {
                    $('#average_rating').text(data.average_rating);
                    $('#total_review').text(data.total_review);

                    var count_star = 0;

                    $('.main_star').each(function () {
                        count_star++;
                        if (Math.ceil(data.average_rating) >= count_star) {
                            $(this).addClass('text-warning');
                            $(this).addClass('star-light');
                        }
                    });

                    $('#total_five_star_review').text(data.five_star_review);

                    $('#total_four_star_review').text(data.four_star_review);

                    $('#total_three_star_review').text(data.three_star_review);

                    $('#total_two_star_review').text(data.two_star_review);

                    $('#total_one_star_review').text(data.one_star_review);

                    $('#five_star_progress').css('width', (data.five_star_review / data.total_review) * 100 + '%');

                    $('#four_star_progress').css('width', (data.four_star_review / data.total_review) * 100 + '%');

                    $('#three_star_progress').css('width', (data.three_star_review / data.total_review) * 100 + '%');

                    $('#two_star_progress').css('width', (data.two_star_review / data.total_review) * 100 + '%');

                    $('#one_star_progress').css('width', (data.one_star_review / data.total_review) * 100 + '%');

                    if(data.review_data.length > 0)
                    {
                        var html = '';

                        for (var count = 0; count < data.review_data.length; count++) {

                            if (count % 3 === 0) {
                                html += '<div class="row mb-3">';
                            }

                            html += '<div class="col-sm-4">';
                            html += '<div class="row mb-3">';
                            html += '<div class="col-sm-11">';
                            html += '<div class="card">';
                            html +=  '<div class="card-header">' +
                                '<div class="row">'+
                                '<div class="col-sm-2">' +
                                '<div class="rounded-circle bg-danger text-white mr-auto">' +
                                '<h3 class="text-center">' + data.review_data[count].user_name.charAt(0) + '</h3>' +
                                '</div>' +
                                '</div>' +
                                '<b>' + data.review_data[count].user_name + '</b>' +
                                '</div>' +
                                '</div>';
                            html += '<div class="card-body">';

                            for (var star = 1; star <= 5; star++) {
                                var class_name = '';

                                if (data.review_data[count].rating >= star) {
                                    class_name = 'text-warning';
                                } else {
                                    class_name = 'star-light';
                                }

                                html += '<i class="fas fa-star '+class_name+' mr-1"></i>';
                            }

                            html += '<br />';
                            html += data.review_data[count].user_review;
                            html += '</div>';
                            html += '<div class="card-footer text-right"><small>On '+data.review_data[count].datetime+'</small></div>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';

                            if ((count + 1) % 3 === 0 || count === data.review_data.length - 1) {
                                html += '</div>';
                            }
                        }

                        $('#review_content').html(html);

                    }
                }
            })
        }

    });
</script>
<?php require_once "blocks/footer.php"; ?>
