@extends('adminlte::page')


@section('title', 'Smart Home')

@section('content_header')
    <h1>Monitoring Rumah</h1>
@stop

@section('content')
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="code/highcharts.js"></script>
<script src="code/modules/exporting.js"></script>
<script src="code/modules/export-data.js"></script>
<script src="mqttws31.js" type="text/javascript"></script>
<script src="jquery.min.js" type="text/javascript"></script>
<script src="config.js" type="text/javascript"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script type="text/javascript">
var temp = [];
var i = 0;

var Chart = Highcharts.chart('container', {
  chart: {
    type: 'spline',
    animation: Highcharts.svg, // don't animate in old IE
    marginRight: 10,
    // events: {
    //   load: function () {
    //
    //     // set up the updating of the chart each second
    //     var series = this.series[0];
    //     setInterval(function () {
    //       var x = (new Date()).getTime(), // current time
    //       y = Math.random();
    //       series.addPoint([x, y], true, true);
    //     }, 1000);
    //   }
    // }
  },

  time: {
    useUTC: false
  },

  title: {
    text: 'Monitoring Suhu Ruangan'
  },
  xAxis: {
    type: 'datetime',
    tickPixelInterval: 150
  },
  yAxis: {
    title: {
      text: 'Value'
    },
    plotLines: [{
      value: 0,
      width: 1,
      color: '#808080'
    }]
  },
  tooltip: {
    headerFormat: '<b>{series.name}</b><br/>',
    pointFormat: '{point.x:%Y-%m-%d %H:%M:%S}<br/>{point.y:.2f}'
  },
  legend: {
    enabled: false
  },
  exporting: {
    enabled: false
  },
  series: [{
    name: 'Sensor',
    data: [0]
  }]});




  var mqtt;
  var reconnectTimeout = 2000;
  var client_name = "web_" + parseInt(Math.random() * 100, 10);
  var dataChart = [0,1,2,4];
  function MQTTconnect() {
    if (typeof path == "undefined") {
      path = '/mqtt';
    }
    mqtt = new Paho.MQTT.Client(
      host,
      port,
      path,
      client_name
    );
    var options = {
      timeout: 3,
      useSSL: useTLS,
      cleanSession: cleansession,
      onSuccess: onConnect,
      onFailure: function (message) {
        $('#status').val("Connection failed: " + message.errorMessage + "Retrying");
        setTimeout(MQTTconnect, reconnectTimeout);
      }
    };

    mqtt.onConnectionLost = onConnectionLost;
    mqtt.onMessageArrived = onMessageArrived;

    if (username != null) {
      options.userName = username;
      options.password = password;
    }
    console.log("Host="+ host + ", port=" + port + ", path=" + path + " TLS = " + useTLS + " username=" + username + " password=" + password);
    mqtt.connect(options);

    document.getElementById('name').innerHTML = "I am "+client_name;
  }

  function onConnect() {
    $('#status').val('Connected to ' + host + ':' + port + path);
    // Connection succeeded; subscribe to our topic
    mqtt.subscribe(topic, {qos: 0});
    mqtt.subscribe(topic1, {qos: 0});
    mqtt.subscribe(topic2, {qos: 0});
    mqtt.subscribe(topic3, {qos: 0});
    $('#topic').val(topic);
    $('#topic1').val(topic1);
    $('#topic2').val(topic2);
    $('#topic3').val(topic3);

    //use the below if you want to publish to a topic on connect
    //message = new Paho.MQTT.Message("Hello World");
    //	message.destinationName = topic;
    //	mqtt.send(message);
  }

  function onConnectionLost(response) {
    setTimeout(MQTTconnect, reconnectTimeout);
    $('#status').val("connection lost: " + responseObject.errorMessage + ". Reconnecting");

  };

  function onMessageArrived(message) {
    var xhr = new XMLHttpRequest();
    var topic = message.destinationName;
    var payload = message.payloadString;

    if(topic == '/arifgozi/smartfan/temp'){
        xhr.open("POST", 'http://10.33.109.93/api/v1/suhu', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(JSON.stringify({
            suhu: payload
        }));
        var time = (new Date()).getTime();
        var temporary = {x: time, y: parseInt(payload)};
        if(temp.length > 10) {
            temp.shift();
            // Chart.series[0].removePoint(0);
          }
        temp.push(temporary);
        console.log(temp);
        Chart.series[0].setData(temp);
        document.getElementById('suhu').innerHTML = payload;
    }
    else if(topic == '/arifgozi/smartfan/lamp'){
        xhr.open("POST", 'http://10.33.109.93/api/v1/lampu', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(JSON.stringify({
            status: payload
        }));
        if(payload=='1'){
            document.getElementById('lampu').innerHTML = 'Menyala';
        }else{
            document.getElementById('lampu').innerHTML = 'Padam';
        }
    }
    else if(topic == '/arifgozi/smartfan/door'){
        if(payload=='0'){
            document.getElementById('pintu').innerHTML = 'Terbuka';
        }
        else if(payload=='1'){
            document.getElementById('pintu').innerHTML = 'Tertutup';
        }
        else if(payload=='2'){
            document.getElementById('pintu').innerHTML = 'Menutup';
        }
        else if(payload=='3'){
            document.getElementById('pintu').innerHTML = 'Membuka';
        }

    }
    else if(topic == '/arifgozi/smartfan/fan1'){
        if(payload=='0'){
            document.getElementById('kipas').innerHTML = 'Mati';
        }
        else if(payload=='1'){
            document.getElementById('kipas').innerHTML = 'Menyala';
        }
    }
  };

  $(document).ready(function() {
    MQTTconnect();
  });
  </script>
<br>

    <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3 id='suhu'>0</h3>

              <p>Suhu</p>
            </div>
            <div class="icon">
              <i class="fas fa-temperature-low"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3 id='lampu'>0<sup style="font-size: 20px"></sup></h3>

              <p>Lampu</p>
            </div>
            <div class="icon">
              <i class="far fa-lightbulb"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3 id='pintu'>0</h3>

              <p>Gerbang</p>
            </div>
            <div class="icon">
              <i class="fas fa-dungeon"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3 id='kipas'>0</h3>

              <p>Kipas</p>
            </div>
            <div class="icon">
              <i class="fas fa-wind"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
@stop
