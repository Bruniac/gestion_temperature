<?php

    require 'connect_db.php';

    for ($i = 0; $i < 100; $i++) {
        $temperature = rand(1, 30);
        $humidite = rand(1, 100);
        $date = date('Y-m-d H:i:s');
        $stmt = $conn->prepare("INSERT INTO meteo (temperature, humidite, date_heure) VALUES (:temperature, :humidite, :date_heure);");
        $stmt->bindParam(':temperature', $temperature);
        $stmt->bindParam(':humidite', $humidite);
        $stmt->bindParam(':date_heure', $date);
        $stmt->execute();
    }
?>

