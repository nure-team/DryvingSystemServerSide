<?php
session_start();
global $db;
include_once 'db.php';

$id = (int) $_GET['id'];
if ($id < 1) {
    header ('location: /?page=admin');
}

$testId = $id;
if (!isset($_SESSION['test_id']) || $_SESSION['test_id'] != $testId) {
    $_SESSION['test_id'] = $testId;
    $_SESSION['test_score'] = 0;
}

$res = $db->query("SELECT * FROM tests WHERE id = {$testId}");
$row = $res->fetch();
$testTitle = $row['title'];

$questionNum = (int) $_POST['q'];
if (empty($questionNum)) {
    $questionNum = 0;
}
$questionNum++;
$questionStart = $questionNum - 1;

$res = $db->query("SELECT count(*) AS count FROM questions WHERE test_id = {$testId}");
$row = $res->fetch();
$questionCount = $row['count'];

$answerId = (int) $_POST['answer_id'];
if (!empty($answerId)) {
    $res = $db->query("SELECT * FROM answers WHERE id = {$answerId}");
    $row = $res->fetch();
    $score = $row['score'];
    $_SESSION['test_score'] += $score;
}

$showForm = 0;
if ($questionCount >= $questionNum) {
    $showForm = 1;

    $res = $db->query("SELECT * FROM questions WHERE test_id = {$testId} LIMIT {$questionStart}, 1");
    $row = $res->fetch();
    $question = $row['question'];
    $question_img = $row['question_img'];
    $questionId = $row['id'];

    $res = $db->query("SELECT * FROM answers WHERE question_id = {$questionId}");
    $answers = $res->fetchAll();
} else {
    $score = $_SESSION['test_score'];

    $res = $db->query("SELECT * FROM results WHERE test_id = {$testId} AND score_min <= {$score} AND score_max >= {$score}");
    $row = $res->fetch();
    $result = $row['result'];
}
?>
<!--<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Система тестирования</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/app.css">
</head>
<body>-->

<div class="container">
    <div class="row justify-content-center">
        <h1 style="font-weight: bold;">Тестування</h1>
    </div>
    <div class="row justify-content-center">
        <h4>Перевірте свої знання з ПДР, відповівши на 20 випадкових питань</h4>
    </div>
    <?php if ($showForm) { ?>
        <form action="/?page=test&id=<?php echo $testId; ?>" method="post">
            <input type="hidden" name="q" value="<?php echo $questionNum; ?>">

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="text-center mt-5">
                        <h4>Питання <?php echo $questionNum . ' з ' . $questionCount; ?></h4>
                    </div>
                    <div class="card mt-3" style="border: 2px solid #0e97fa; border-radius: 5px;">
                        <div class="card-header">
                            <h3><?php echo $question; ?></h3>
                            <?php
                                if ($question_img !== NULL) {
                            ?>
                            <div class="d-flex justify-content-center"><img src="<?=$question_img;?>" style="height: 150px; width: 150px;"/></div>
                            <?php } ?>
                        </div>
                        <div class="card-body bg-light">
                            <?php foreach ($answers AS $answer) { ?>
                                <div>
                                    <input type="radio" name="answer_id" id="answer_btn" required value="<?php echo $answer['id']; ?>"> <?php echo $answer['answer']; ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <?php if ($questionCount == $questionNum) { ?>
                            <button type="submit" class="btn btn-success" onClick={this.handleStopTimerClick}>Отримати результат</button>
                        <?php } else { ?>
                            <button type="submit" class="btn btn-lg btn-info" style="border-radius: 15px;">Далі &nbsp; <i class="fa-solid fa-caret-right"></i></button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </form>
    <?php } else { ?>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-3">
                    <div class="card-header">
                        <h3>Ваш результат</h3>
                    </div>
                    <div class="card-body">
                        <div class="result-print">
                            <?php echo $result; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="text-center mt-3">
        <div id="root" style="margin: 20px;"></div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.6.1/react.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.6.1/react-dom.min.js"></script>
<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
<script type="text/babel">
    class Timer extends React.Component {
        constructor(props) {
            super(props);
            const timeLeft = localStorage.getItem('timeLeft') || 20 * 60;
            const isTimerStopped = localStorage.getItem('isTimerStopped') === 'true';
            this.state = {
                timeLeft: parseInt(timeLeft),
                isTimerStopped: isTimerStopped
            };
        }

        componentDidUpdate() {
            localStorage.setItem('timeLeft', this.state.timeLeft);
            localStorage.setItem('isTimerStopped', this.state.isTimerStopped);
        }

        componentDidMount() {
            if (!this.state.isTimerStopped) {
                this.interval = setInterval(() => {
                    this.setState(prevState => ({
                        timeLeft: prevState.timeLeft - 1
                    }));
                }, 1000);
            }
        }

        componentWillUnmount() {
            clearInterval(this.interval);
        }

        handleStopTimerClick = () => {
            clearInterval(this.interval);
            this.setState({ isTimerStopped: true });
        };

        render() {
            const { timeLeft, isTimerStopped } = this.state;
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;

            const initialTime = 20 * 60;
            const timePassed = initialTime - this.state.timeLeft;

            return (
                <div>
                    <h1>
                        <b>Залишилось часу:</b> {minutes}:{seconds < 10 ? '0' : ''}
                        {seconds}
                    </h1>
                    {timeLeft === 0 && <p>Час вийшов!</p>}
                    {!isTimerStopped && (
                        <button type="button" onClick={this.handleStopTimerClick}>
                            Зупинити таймер
                        </button>
                    )}
                </div>
            );
        }
    }

    ReactDOM.render(<Timer />, document.getElementById('root'));
</script>
<script>
    function clearTimer() {
        window.addEventListener('beforeunload', () => {
            localStorage.clear();
        });
    }
</script>
<?php require_once "blocks/footer.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>