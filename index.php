<?php
$data = file_get_contents('./log.json');
if (empty($data)) {
    $data = '[{"t": {"t1":90, "t2":25, "t3":101.2},"r": {"n": true, "p": false},"time":"31:11.1000"},{"t": {"t1":10, "t2":15, "t3":11.2},"r": {"n": true, "p": false},"time":"31:12.1000"},{"t": {"t1":10, "t2":15, "t3":11.2},"r": {"n": true, "p": false},"time":"31:12.500"}]';
}

$data = json_decode($data, true);

$temperature = [];
$sensor1=[];
//$sensor11=[];
$sensor2=[];
$sensor3=[];

$time = [];

$data = array_slice($data, -50000);
$superMax = -100;
$superMin = 150;

for ($i = 0; $i <= count($data);) {
    $bufT1 =  $bufT2 =  $bufT3 =  $bufT4 = 0;

//    for ($j = 0; $j < 10; $j++) {
//        if (empty($data[$i+$j])) {
//            break 2;
//        }
//        $bufT1 += $data[$i+$j]['t']['t1'];
//        $bufT2 += $data[$i+$j]['t']['t2'];
//        $bufT3 += $data[$i+$j]['t']['t3'];
//
//    }
    $bufT1 = $data[$i]['t']['t1'];
    $bufT2 = $data[$i]['t']['t2'];
    $bufT3 = $data[$i]['t']['t3'];
    $i+=10;
    $arr_date = explode(':', $data[$i]['time']);
    $arr_date[0] += 2;
    $arr_date[0] %= 24;
    $time[] = implode(':',$arr_date);

    $sensor1[] = $bufT1;///10;
    $sensor2[] = $bufT2;///10;
    $sensor3[] = $bufT3;///10;

//
//    if ($superMax < $bufT1/10) {
//        $superMax = $bufT1/10;
//    }
//    if ($superMax < $bufT2/10) {
//        $superMax = $bufT2/10;
//    }
//    if ($superMax < $bufT3/10) {
//        $superMax = $bufT3/10;
//    }
//
//    if ($superMin > $bufT1/10) {
//        $superMin = $bufT1/10;
//    }
//    if ($superMin > $bufT2/10) {
//        $superMin = $bufT2/10;
//    }
//    if ($superMin > $bufT3/10) {
//        $superMin = $bufT3/10;
//    }

}

//echo '<br><br><br><br>';
//foreach ($data as $key => $d) {
//
//    $sensor1[] = $d['t']['t1'];
//    $sensor2[] = $d['t']['t2'];
//    $sensor3[] = $d['t']['t3'];
//    $sensor4[] = $d['t']['t4'];
//    $time[] = $d['time'];
//}

$temperature = [$sensor1,$sensor2,$sensor3];
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
