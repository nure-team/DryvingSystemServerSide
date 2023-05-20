<?php
global $mysqli;
require_once(PAYMENT_DIR. 'autoload.php');

use Omnipay\Omnipay;

define('CLIENT_ID', 'AbdC5320PX7hxKB99hXJf61mrceAZd83G3qNbMrREJvRPbfx128A5f3dQ6zePveLZGEp3528mMnlH6BV');
define('CLIENT_SECRET', 'EN2ExU_miv91s1Uc3yWsmyelNlsCgqmkl2aaO90gwUqACOLm35N3CrmKOBKdeugEXwoxJmJiqj5hg6yT');

define('PAYPAL_RETURN_URL', '/?page=success');
define('PAYPAL_CANCEL_URL', '/?page=cancel');
define('PAYPAL_CURRENCY', 'USD');

$uid = $_GET['uid'];
$category_id = $_GET['category'];
$courses = mysqli_query($mysqli, "SELECT * FROM `cource_category` WHERE category_id='$category_id'");
$courseArr = mysqli_fetch_assoc($courses);

$gateway = Omnipay::create('PayPal_Rest');
$gateway->setClientId(CLIENT_ID);
$gateway->setSecret(CLIENT_SECRET);
$gateway->setTestMode(true);

if (isset($_POST['submit'])) {
    try {
        $response = $gateway->purchase(array(
            'amount' => $_POST['amount'],
            'currency' => PAYPAL_CURRENCY,
            'returnUrl' => PAYPAL_RETURN_URL,
            'cancelUrl' => PAYPAL_CANCEL_URL,
            'items' => array(
                array(
                    'name' => 'Course subscription',
                    'price' => $_POST['amount'],
                    'description' => 'Get access to course.',
                    'quantity' => 1
                )
            )
        ))->send();

        if ($response->isRedirect()) {
            // forward customer to PayPal
            $response->redirect();
        } else {
            // not success
            echo $response->getMessage();
        }

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>
<div class="container">
    <h1 align="center">Оплата навчання</h1>
    <div style="display: block; justify-content: center;border: 2px solid #0e97fa;border-radius: 10px;padding: 10px;max-width: 80%;height: 500px; margin:0 auto;">
        <div>
            <h2><b>Ви обрали категорію <?php echo $courseArr['category_name']; ?></b></h2>
            <h4><b>Навчання включає:</b></h4>
            <?php
              if($courseArr['category_name'] === 'B') {
            ?>
            <ul style="font-size: 1.2em;">
                <li>12 лекцій (охопдюють усі розділи ПДР)</li>
                <li>2 лекції (будова легкового автомобіля)</li>
                <li>Проходження пробного екзаменаційного білету</li>
            </ul>
            <?php } else { ?>
                  <ul style="font-size: 1.2em;">
                      <li>14 лекцій (охопдюють усі розділи ПДР)</li>
                      <li>2 лекції (будова та різноманітність вантажних автомобілів)</li>
                      <li>Проходження пробного екзаменаційного білету (відповідно категорії C)</li>
                  </ul>
            <?php } ?>
            <h4><b>Тривалість курсу: <?php echo $courseArr['course_duration']; ?></b></h4>
            <h4><b>Вартість курсу: <?php echo $courseArr['price'] . "$"; ?></b></h4>
        </div>
    </div>
    <form action="" method="post">
        <input type="text" value="<?= $courseArr['price']; ?>" name="amount" style="display: none;">
        <div style="text-align: center; margin-top:10px;">
            <input type="submit" name="submit" value="Сплатити через PayPal" style="background-color: #0e97fa; color: white; border-radius: 20px; padding: 10px 20px; font-size: 1.2em; font-weight: bold; border: none; margin: 0 auto; display: block;">
        </div>
    </form>
</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var uid = '<?php echo $uid; ?>';
            $.ajax({
                type: 'GET',
                url: '/?page=success',
                data: { uid: uid },
                success: function(response) {
                    console.log('Data sent successfully!');
                },
                error: function() {
                    console.log('Error occurred while sending data.');
                }
            });
        });
    </script>
<?php require_once "blocks/footer.php"; ?>