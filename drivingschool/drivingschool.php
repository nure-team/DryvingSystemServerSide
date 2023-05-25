<?php
session_start();
global $mysqli;

require_once "blocks/header.php";

$category_b = mysqli_query($mysqli,'SELECT price FROM `cource_category` WHERE category_id=1');
$category_c = mysqli_query($mysqli, 'SELECT price FROM `cource_category` WHERE category_id=2');

$category_b = mysqli_fetch_assoc($category_b);
$category_c = mysqli_fetch_assoc($category_c);

?>

<div class="container">
    <h1 align="center">Автошкола "Кривий рух"</h1>
    <p class="subheader">Автошкола "Кривий рух" - це провідна онлайн-автошкола, яка допомагає майбутнім водіям отримати необхідні навички для безпечного та впевненого керування автомобілем.</p>
    <div style="display: block; justify-content: center;border: 2px solid #0e97fa;border-radius: 10px;padding: 10px;max-width: 80%;height: 250px; margin:20px; ">
        <h2>Чому саме ми?</h2>
        <p class="subheader">
            Ми пропонуємо вам досвідчених та сертифікованих вчителів, доступ до всіх потрібних для навчання матеріалів і тестування для закріплення матеріалу в зручний час для вас, а завдяки проведенню лекцій онлайн ви можете підєднатися з будь-якої точки світу.
        </p>
    </div>
    <div>
        <h1>Які послуги ми надаємо?</h1>
        <p class="subheader">1. Теоретичний курс підготовки водіїв за категорією "B" (Вартість навчання <?php echo $category_b['price'].'$'; ?>)</p>
        <p class="subheader">2. Теоретичний курс підготовки водіїв за категорією "C" (Вартість навчання <?php echo $category_c['price'].'$'; ?>)</p>
    </div>
    <h1 align="center">Відгуки користувачів</h1>
    <div class="mt-5" id="review_content"></div>
</div>

<script>
    $(document).ready(function(){

        var rating_data = 0;

        load_rating_data();

        function load_rating_data()
        {
            $.ajax({
                url:"/drivingschool/rating_system/submit_rating.php",
                method:"POST",
                data:{action:'load_data'},
                dataType:"JSON",
                success:function(data)
                {
                    $('#average_rating').text(data.average_rating);
                    $('#total_review').text(data.total_review);

                    var count_star = 0;

                    $('.main_star').each(function(){
                        count_star++;
                        if(Math.ceil(data.average_rating) >= count_star)
                        {
                            $(this).addClass('text-warning');
                            $(this).addClass('star-light');
                        }
                    });

                    $('#total_five_star_review').text(data.five_star_review);

                    $('#total_four_star_review').text(data.four_star_review);

                    $('#total_three_star_review').text(data.three_star_review);

                    $('#total_two_star_review').text(data.two_star_review);

                    $('#total_one_star_review').text(data.one_star_review);

                    $('#five_star_progress').css('width', (data.five_star_review/data.total_review) * 100 + '%');

                    $('#four_star_progress').css('width', (data.four_star_review/data.total_review) * 100 + '%');

                    $('#three_star_progress').css('width', (data.three_star_review/data.total_review) * 100 + '%');

                    $('#two_star_progress').css('width', (data.two_star_review/data.total_review) * 100 + '%');

                    $('#one_star_progress').css('width', (data.one_star_review/data.total_review) * 100 + '%');

                    if(data.review_data.length > 0)
                    {
                        var html = '';

                        for (var count = 0; count < data.review_data.length; count++)
                        {
                            html += '<div class="row mb-3">';

                            html += '<div class="col-sm-1"><div class="rounded-circle bg-danger text-white pt-2 pb-2"><h3 class="text-center">'+data.review_data[count].user_name.charAt(0)+'</h3></div></div>';

                            html += '<div class="col-sm-11">';

                            html += '<div class="card">';

                            html += '<div class="card-header"><b>'+data.review_data[count].user_name+'</b></div>';

                            html += '<div class="card-body">';

                            for(var star = 1; star <= 5; star++)
                            {
                                var class_name = '';

                                if(data.review_data[count].rating >= star)
                                {
                                    class_name = 'text-warning';
                                }
                                else
                                {
                                    class_name = 'star-light';
                                }

                                html += '<i class="fas fa-star '+class_name+' mr-1"></i>';
                            }

                            html += '<br />';

                            html += data.review_data[count].user_review;

                            html += '</div>';

                            html += '<div class="card-footer text-right">On '+data.review_data[count].datetime+'</div>';

                            html += '</div>';

                            html += '</div>';

                            html += '</div>';
                        }

                        $('#review_content').html(html);
                    }
                }
            })
        }

    });
</script>

<?php
$compl_course = '';

if (isset($_SESSION['email']) && $_SESSION['uid']) {
    $user_obj = new User();
    $user = $user_obj->getUserById($_SESSION['uid']);
    $user_first_name = $user['first_name'];
    $user_last_name = $user['last_name'];
    $compl_course = 'SELECT user_id FROM `order_cource` WHERE user_id=' .$_SESSION['uid'];
    $res = $mysqli->query($compl_course);

    if ($res->num_rows > 0) {
        echo '<div class="d-flex justify-content-center mb-2" ><a type="button" class="btn btn-outline-primary" href="/?page=rating&uid=' . $_SESSION['uid'] . '">Залишити відгук</a></div>';
    }
}

if (isset($_SESSION["id"]) && isset($_SESSION["sess"])) {
    $Controller = new Controller;
    $compl_course = 'SELECT user_id FROM `order_cource` WHERE user_id=' .$_SESSION['id'];
    $res = $mysqli->query($compl_course);

    if (($Controller->checkUserStatus($_SESSION["id"], $_SESSION["sess"])) && ($res->num_rows > 0)) {
        echo '<div class="d-flex justify-content-center mb-2" ><a type="button" class="btn btn-outline-primary" href="/?page=rating&uid=' .$_SESSION['id'] . '">Залишити відгук</a></div>';
    }
}

?>

<?php
require_once "blocks/footer.php";
?>
