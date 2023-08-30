<?php

require 'connect_db.php';

$stmt = $conn->prepare("SELECT * FROM meteo ORDER BY id DESC LIMIT 20");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$req_temp = $conn->prepare("SELECT temperature, date_heure FROM meteo");
$req_temp->execute();
$dataTemperature = $req_temp->fetchAll(PDO::FETCH_ASSOC); 
$temperature = [];
foreach($dataTemperature as $row) {
    $data = $row;
    $data['value'] = $row['temperature'];
    $temperature[] = $data;
}
$temperature = json_encode($temperature);


$req_hum = $conn->prepare("SELECT humidite, date_heure FROM meteo");
$req_hum->execute();
$dataHumidite = $req_hum->fetchAll(PDO::FETCH_ASSOC);
$humidite = [];
foreach($dataHumidite as $row) {
    $data = $row;
    $data['value'] = $row['humidite'];
    $humidite[] = $data;
}
$humidite = json_encode($humidite);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion température</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1>Météo</h1>
        <table class="table">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Température</th>
            <th scope="col">Humidité</th>
            <th scope="col">Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($results as $row) :
            ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['temperature'] ?></td>
                <td><?= $row['humidite'] ?></td>
                <td><?= $row['date_heure'] ?></td>
            </tr>
            <?php
                endforeach
            ?>
        </tbody>
        </table>
    </div>
    <canvas id="temperatures">
    </canvas>
     <script type="text/javascript">
    
     </script>     
           
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
 const cfgTemp = {
            type: 'line',
            data: {
                datasets: [{
                    label: 'Température',
                    data: <?= $temperature ?>,
                    borderColor: 'rgb(75, 192, 192)',
                },
                    {
                        label: 'Humidité',
                        data: <?= $humidite ?>,
                        borderColor: 'rgb(255, 99, 132)',
                    }],
            },
            options: {
                parsing: {
                    xAxisKey: 'date_heure',
                    yAxisKey: 'value'
                },
            }
        }
        const ctxTemp = document.getElementById('temperatures').getContext('2d');
        const myChartTemp = new Chart(ctxTemp, cfgTemp);
    </script>     
        

</body>
</html>