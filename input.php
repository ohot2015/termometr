<?php
$data = file_get_contents('php://input');
$data = '[{"t": {"t1":90, "t2":25, "t3":101.2},"r": {"n": true, "p": false},"time":"31:11.1000"},{"t": {"t1":10, "t2":15, "t3":11.2},"r": {"n": true, "p": false},"time":"31:12.1000"},{"t": {"t1":10, "t2":15, "t3":11.2},"r": {"n": true, "p": false},"time":"31:12.500"}]';
$data = json_decode($data, true);
$temperature = [];
$sensor1=[];
$sensor2=[];
$sensor3=[];
$sensor4=[];
$time = [];
foreach ($data as $key => $d) {
    $sensor1[] = $d['t']['t1'];
    $sensor2[] = $d['t']['t2'];
    $sensor3[] = $d['t']['t3'];
    $sensor4[] = $d['t']['t4'];
    $time[] = $d['time'];
}
$temperature = [$sensor1,$sensor2,$sensor3,$sensor4];
$time = json_encode($time);
$temperature = json_encode($temperature);
//echo 123;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>termometr</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        #myChart {
            width: calc(100vw - 140px);
        }
    </style>
</head>
<body>
<div class="wrap"><canvas id="myChart"  height="600"></canvas></div>

<script
    src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs="
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script>

  let temperatere = JSON.parse('<?php echo $temperature;?>');
  let temp = [];
  let colors = ['blue','green','yellow', 'black'];
  temperatere.forEach((el ,i) => {
    temp.push({
      label: 'term' + i,
      backgroundColor: 'red',
      borderColor: colors[i],
      data: el,
      fill: false,
    })
  })

  var ctx = document.getElementById('myChart');
  var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?php echo $time;?>,
      datasets: temp
    },
    options: {
      responsive: false,
      title: {
        display: true,
        text: 'Chart.js Line Chart'
      },
      tooltips: {
        mode: 'index',
        intersect: false,
      },
      hover: {
        mode: 'nearest',
        intersect: true
      },
      scales: {
        xAxes: [{
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Month'
          }
        }],
        yAxes: [{
          display: true,
          scaleLabel: {
            display: true,
            labelString: 'Value'
          }
        }]
      }
    }

  });
</script>
</body>
</html>
