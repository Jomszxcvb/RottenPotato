<?php
require_once 'includes/DB_con.php';

$db = new DB_con();

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $result = $db->searchMovies($query);

    $suggestions = array();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $suggestions[] = $row["title"];
        }
    }

    echo json_encode($suggestions);
}
?>