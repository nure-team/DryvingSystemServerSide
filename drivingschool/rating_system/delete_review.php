<?php

// delete_review.php

$connect = new PDO("mysql:host=localhost;dbname=drivingsystem", "root", "root");


if (isset($_POST['action']) && $_POST['action'] === 'delete_review') {

    $username = $_POST['username'];

    $sql = "DELETE FROM `review_table` WHERE user_name = :username";
    $stmt = $connect->prepare($sql);
    $stmt->bindParam(':username', $username);

if ($stmt->execute()) {
    $response = array('status' => 'success', 'message' => 'Reviews deleted successfully.', 'username' => $username);
    echo json_encode($response);

} else {
    $response = array('status' => 'error', 'message' => 'Failed to delete reviews.');
    echo json_encode($response);
}

}
