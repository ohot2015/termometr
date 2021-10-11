<?php
$data = file_get_contents('./log.json');
if (empty($data)) {
    $data = '[{"t": {"t1":90},"r": {"n": true, "p": false},"time":"31:11.1000"}]';
}

$data = json_decode($data, true);
$temperature = [];
$sensor1 = [];
$time = [];
$rele = [];

foreach ($data as $key => $d) {
    $sensor1[] = $d['t']['t1'];
    $time[] = $d['time'];
    $rele[] = $d['r']['n'];
}

$superMin = min($sensor1);
$superMax = max($sensor1);

$temperature = [$sensor1
//    ,$sensor2,$sensor3
];
//var_dump($temperature);
$time = json_encode($time);
$temperature = json_encode($temperature);
//echo 123;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">

  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>termometr</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css">
  <style>
      body {
          margin: 0;
          padding: 0;
      }
      .wrap{
          width:100%;
      }
      #myChart {
          width: calc(100vw - 140px);
      }

  </style>
</head>
<body>
<div class="wrap"><canvas id="myChart" width="350px" height="600"></canvas></div>
<button class="clear">очистить</button>
<div><span>максимальная температура:</span><?php echo $superMax;?></div>
<div><span>минимальная температура:</span><?php echo $superMin;?></div>
<div><span>всего измерений:</span><?php echo count($data);?></div>
<div><span>РЕЛЕ :</span><?php $r = (array_pop($rele) === false ) ?  'Вкл' : 'Выкл'; echo $r;?></div>

<!--<script-->
<!--    src="https://code.jquery.com/jquery-3.5.1.slim.min.js"-->
<!--    integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs="-->
<!--    crossorigin="anonymous"></script>-->
<script
        src="https://code.jquery.com/jquery-1.12.4.min.js"
        integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
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
  $('.clear').click(()=> {
    $.ajax({
      url: '/clear.php',
      type: 'post',
      dataType: 'json',
      contentType: 'application/json',
      success: function (data) {
        console.log(data)
        location.reload()
      },
//      data: JSON.stringify(person)
    });
  })
</script>
</body>
</html>
