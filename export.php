<?php
require 'connect_db.php';

$csvFileName = 'export.csv';

$csvFile = fopen($csvFileName, 'w');
$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = $page > 1 ? $page * $limit : 0;
$stmt = $conn->prepare("SELECT * FROM meteo ORDER BY id  DESC LIMIT :limit OFFSET :offset;");
$stmt->bindParam('limit',$limit, PDO::PARAM_INT);
$stmt->bindParam('offset',$offset, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($results as $row) {
    fputcsv($csvFile, $row);
}

fclose($csvFile);

header("Content-type: index.php/csv");
header("Content-disposition: attachment; filename=$csvFileName");
readfile($csvFileName);
?>
