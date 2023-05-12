<?php
require_once "system/configuration.php";

class Main_page
{
    public function showCards()
    {
        global $mysqli;
        $content = mysqli_query($mysqli, "SELECT * FROM `main_blocks`");

        if (!$content) {
            throw new Exception("An error occured: " . mysqli_error($mysqli));
        }
        $all_cards = array();

        while ($card = $content->fetch_object()) {
            $all_cards[] = $card;
        }
        return $all_cards;
    }
}