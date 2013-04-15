<?php
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile('https://www.google.com/jsapi');
$cs->registerScript('line_chart',
"    google.load('visualization', '1', {'packages':['corechart']});
    google.setOnLoadCallback(drawChart);

    function drawChart(id, optitle, data) {
          
      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(data);


      var options = {
          title: optitle
        };


      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.LineChart(document.getElementById(id));
      chart.draw(data,options);
    }",
        CClientScript::POS_HEAD);

$cs->registerScript('user_line_chart', 'drawChart(\'user_chart\', \''. Yii::t('app','Users').'\', '.Site::userChartData().')', CClientScript::POS_READY);
?>
<div id="user_chart" style="width: 900px; height: 500px;"></div>
