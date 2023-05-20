<?php
global $mysqli;
require_once "blocks/header.php";

$user = $_GET['id'];

if (($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($user))) {
    // Retrieve form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $category = $_POST['category'];
    $time = $_POST['timetable'];

    // Validate form data
    $errors = array();

    if (empty($first_name)) {
        $errors[] = 'Username is required';
    }

    if (empty($last_name)) {
        $errors[] = 'Surname is required';
    }

    if (empty($phone)) {
        $errors[] = 'Phone number is required';
    } elseif (strlen($phone) < 10) {
        $errors[] = 'Phone number must be at least 10 characters';
    }

    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Provide a valid email address';
    }

    if (empty($category)) {
        $errors[] = 'Category is required';
    }

    if (empty($time)) {
        $errors[] = 'Time is required';
    }

    // Process form data if there are no errors
    if (empty($errors)) {

        mysqli_query($mysqli, "INSERT INTO `order_cource` VALUES(NULL, '$phone', '$category', '$user', '$time')");
        echo "<script>location.replace('/?page=payment&uid=" . $user . "&category=" . $category . "')</script>";

    } else {
        // Display errors
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>" . $error . "
<button type='button' class='close' data-dismiss='alert' aria-label='Close' 
<span aria-hidden='true'>&times;</span>
</button></div>";
        }
    }
} else {
    // If the script is accessed directly without a form submission
    echo 'Invalid request';
}

?>

<?php
if (isset($user)) {
    $uinfo = mysqli_query($mysqli, "SELECT * FROM `users` WHERE user_id=" . $_GET['id']);
}
?>

<div class="container">
    <form id="form" method="post">
        <h1>Записатися на заняття</h1>
        <?php
        $user_info = $uinfo->fetch_object();
        ?>
        <div class="input-control">
            <label for="username">Ваше ім'я</label>
            <input id="username" name="first_name" type="text" value="<?=$user_info->first_name;?>">
            <div class="error"></div>
        </div>
        <div class="input-control">
            <label for="surname">Ваше прізвище</label>
            <input id="surname" name="last_name" type="text" value="<?=$user_info->last_name;?>">
            <div class="error"></div>
        </div>
        <div class="input-control">
            <label for="phone">Ваш номер телефону</label>
            <input id="phone" name="phone" type="number">
            <div class="error"></div>
        </div>
        <div class="input-control">
            <label for="email">Email</label>
            <input id="email" name="email" type="text" value="<?=$user_info->email;?>">
            <div class="error"></div>
        </div>
        <div class="form-group form-check">
            <label for="category">Оберіть катеорію та зручний для вас час занять</label>
            <br/>
            <?php
            $categories = $mysqli->query("SELECT * FROM `cource_category`");
            while ($course_categories = $categories->fetch_object()) {
                ?>
                <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                    <input type="radio" class="btn-check" id="<?php echo $course_categories->category_name; ?>" name="category" autocomplete="off" style="display: none;" value="<?=$course_categories->category_id;?>"/>
                    <label class="btn btn-info mr-4" for="<?php echo $course_categories->category_name; ?>"><?php echo $course_categories->category_name; ?></label>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="timetable" class="form-control">
                <?php
                $study_times = $mysqli->query("SELECT * FROM `course_times` ORDER BY time_id ASC");

                while ($time = $study_times->fetch_object()) {
                ?>
                <option value="<?php echo $time->time_id; ?>"><?php echo $time->time_info; ?></option>
                <?php } ?>
            </select>
        </div>
        <button type="submit">Sign Up</button>
    </form>
</div>
<script>
    $(document).ready(function() {
        $('.btn-check').change(function() {
            if ($(this).is(':checked')) {
                $('.btn-info').removeClass('active focus');
                $(this).next('.btn-info').addClass('active focus');
            }
        });
    });
</script>
<?php require_once "blocks/footer.php"; ?>
