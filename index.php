<?php

require 'connect_db.php';

$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = $page > 1 ? $page * $limit : 0;
$stmt = $conn->prepare("SELECT * FROM meteo ORDER BY id  DESC LIMIT :limit OFFSET :offset;");
$stmt->bindParam('limit',$limit, PDO::PARAM_INT);
$stmt->bindParam('offset',$offset, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


$numberLines = $conn->query("SELECT COUNT(*) FROM meteo")->fetchColumn();


$temperature = [];
foreach($results as $row) {
    $data = $row;
    $data['value'] = $row['temperature'];
    $temperature[] = $data;
}
$temperature = json_encode($temperature);


$humidite = [];
foreach($results as $row) {
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
    <div>
<select name="limit" id="limit" class="form-control">
            <option value="10" <?php if ($limit == '10') { echo 'selected'; }?>>10</option>
            <option value="20" <?php if ($limit == '20') { echo 'selected'; }?>>20</option>
            <option value="30" <?php if ($limit == '30') { echo 'selected'; }?>>30</option>
            <option value="40" <?php if ($limit == '40') { echo 'selected'; }?>>40</option>
            <option value="<?= $numberLines ?>" <?php if ($limit == $numberLines) { echo 'selected'; }?>>Tout</option>
        </select>
    </div>
    <div class="container">
        <h1>Météo</h1>
        <a href="export.php?limit=<?= $limit ?>&page=<?= $page ?>" class="btn btn-primary">Exporter</a>

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
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if ($numberLines <= $limit || $page == 1) { echo 'disabled'; } ?>"><a class="page-link" href="?page=1&limit=<?= $limit ?>"><<</a></li>
                <li class="page-item <?php if ($numberLines <= $limit || $page == 1) { echo 'disabled'; } ?>"><a class="page-link" href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>"><</a></li>
                <?php
                    if ($numberLines > $limit) {
                        for ($i = 0; $i < ceil($numberLines / $limit) - 1; $i++) {
                            if (($i + 1) > $page - 4 && ($i + 1) < $page + 4) {
                                $addClass = '';
                                if ($page == $i + 1) {
                                    $addClass = 'active';
                                }
                                echo '<li class="page-item '. $addClass .'"><a class="page-link" href="?page='.($i + 1).'&limit='.$limit.'">'.($i + 1).'</a></li>';
                            }
                        }
                    }
                ?>
                <li class="page-item <?php if ($numberLines <= $limit || $page == ceil($numberLines / $limit) - 1) { echo 'disabled'; } ?>"><a class="page-link" href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>">></a></li>
                <li class="page-item <?php if ($numberLines <= $limit || $page == ceil($numberLines / $limit) - 1) { echo 'disabled'; } ?>"><a class="page-link" href="?page=<?= ceil($numberLines / $limit) - 1 ?>&limit=<?= $limit ?>">>></a></li>

            </ul>
        </nav>
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

        document.getElementById('limit').addEventListener('change', function () {
            window.location.href = 'index.php?limit=' + this.value + '&page=1';
        })
    </script>     
<!DOCTYPE html>
<html lang="en">
<head>
    
    <style>
        body {
            background: linear-gradient(to bottom, #3498db, #2c3e50); 
            color: white; 
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.1); 
            border-radius: 10px; 
            padding: 20px;
            margin-top: 20px;
        }

        .table {
            background: rgba(0, 0, 0, 0.3); 
            border-radius: 10px;
            overflow: hidden; 
        }

        th, td {
            border: none; 
            padding: 8px;
        }

        th {
            background-color: #AFD3E2 !important; 
            
        }
        
        td {
            background: #3C4048 ; 
            font-family: 'Roboto', sans-serif;

        }

        .pagination {
            margin-top: 10px;
        }

        #temperatures {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
   
</body>
</html>
    

    </body>
</html>