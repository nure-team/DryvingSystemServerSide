<?php
global $mysqli;

$pdr_id = $_GET['pdr_id'];
$result = $mysqli->query("SELECT number,content FROM `pdr_content` WHERE partition_id='$pdr_id'");

if (isset($_POST['submit'])) {

    // Retrieve the edited values from $_POST array and update the corresponding records in the database
    foreach ($_POST['content'] as $key => $value) {
        // Perform proper sanitization/validation of the input before using it in the query to prevent SQL injection
        $content = $mysqli->real_escape_string($value);

        // Prepare and execute the update query
        $query = "UPDATE `pdr_content` SET content='$content' WHERE number='$key'";
        if ($mysqli->query($query)) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Data saved!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Failed to save data: ' . $mysqli->error . '
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
        }
    }

    // Close the database connection
    $mysqli->close();
}
?>

    <div class="container">
        <form method="post" action="">
            <?php
            // Check if the query was successful and fetch the content
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $key = $row['number']; // Replace 'column_name' with the appropriate column name
                    $value = $row['content']; // Assuming 'content' is the column name
                    echo '<textarea class="form-control text-left" name="content[' . $key . ']">' . $value . '</textarea><br>';
                }
            } else {
                echo "No content found.";
            }
            ?>
            <input type="submit" name="submit" class="btn btn-primary" value="Submit">
        </form>
    </div>


<?php require_once "blocks/footer.php"; ?>