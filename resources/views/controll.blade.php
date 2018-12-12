@extends('adminlte::page')

@section('title', 'Smart Home')

@section('content_header')
   <h1>Kontrol Rumah</h1>
  <script src="mqttws31.js" type="text/javascript"></script>
  <script src="jquery.min.js" type="text/javascript"></script>
  <script src="config.js" type="text/javascript"></script>
  <script type="text/javascript">
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
    mqtt.subscribe(topic1, {qos: 0});
    mqtt.subscribe(topic3, {qos: 0});
    mqtt.subscribe(topic4, {qos: 0});
    mqtt.subscribe(topic5, {qos: 0});
    $('#topic1').val(topic1);
    $('#topic3').val(topic3);
    $('#topic4').val(topic4);
    $('#topic5').val(topic5);

    //use the below if you want to publish to a topic on connect
    //message = new Paho.MQTT.Message("Hello World");
    //	message.destinationName = topic;
    //	mqtt.send(message);
  }

  function sendONKipas(e){
    //use the below if you want to publish to a topic on connect
    var key=e.keyCode || e.which;
      var Message = '1';
      // message = new Paho.MQTT.Message(client_name+" : "+Message);
      message = new Paho.MQTT.Message(Message);
      message.destinationName = topic4;
      mqtt.send(message);
      document.getElementById('kipas1').innerHTML = 'Menyala';
  }
  function sendOFFKipas(e){
    //use the below if you want to publish to a topic on connect
    var key=e.keyCode || e.which;
      var Message = '0';
      // message = new Paho.MQTT.Message(client_name+" : "+Message);
      message = new Paho.MQTT.Message(Message);
      message.destinationName = topic4;
      mqtt.send(message);
      document.getElementById('kipas1').innerHTML = 'Mati';
  }
  function sendONLampu(e){
    //use the below if you want to publish to a topic on connect
    var key=e.keyCode || e.which;
      var Message = '1';
      // message = new Paho.MQTT.Message(client_name+" : "+Message);
      message = new Paho.MQTT.Message(Message);
      message.destinationName = topic5;
      mqtt.send(message);
      document.getElementById('lampu1').innerHTML = 'Menyala';
  }
  function sendOFFLampu(e){
    //use the below if you want to publish to a topic on connect
    var key=e.keyCode || e.which;
      var Message = '0';
      // message = new Paho.MQTT.Message(client_name+" : "+Message);
      message = new Paho.MQTT.Message(Message);
      message.destinationName = topic5;
      mqtt.send(message);
      document.getElementById('lampu1').innerHTML = 'Mati';
  }
  function onConnectionLost(response) {
    setTimeout(MQTTconnect, reconnectTimeout);
    $('#status').val("connection lost: " + responseObject.errorMessage + ". Reconnecting");

  };

  function onMessageArrived(message) {

    var topic = message.destinationName;
    var payload = message.payloadString;
    $('#ws').prepend(payload+"</br>");
    if(topic == '/arifgozi/smartfan/fan1'){
        if(payload=='0'){
            document.getElementById('kipas1').innerHTML = 'Mati';
        }
        else if(payload=='1'){
            document.getElementById('kipas1').innerHTML = 'Menyala';
        }
    }else if(topic == '/arifgozi/smartfan/lamp'){
        if(payload=='1'){
            document.getElementById('lampu1').innerHTML = 'Menyala';
        }else{
            document.getElementById('lampu1').innerHTML = 'Padam';
        }
    }
    // dataChart.push(payload);
    // document.getElementById('ws').innerHTML = payload;

    // document.getElementById('ws').innerHTML = dataChart[dataChart.length - 1];
    // $('#dataChart').data("["+data[0]+","+data[1]+","+data[2]+","+data[3]+"]");
    // $('#dataChart').text(data);
  };

  $(document).ready(function() {
    MQTTconnect();
  });
  </script>
@stop

@section('content')

        <div class="row">
                <div class="col-lg-3 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-aqua">
                        <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <b>Kontrol Kipas</b>
                                </div>
                                <br>
                                <a class="btn btn-app" onclick='sendONKipas(event)'>
                                    <i class="fa fa-play"></i> Play
                                </a>
                                <a class="btn btn-app" onclick='sendOFFKipas(event)'>
                                    <i class="fa fa-stop"></i> Stop
                                </a>
                                <a class="btn btn-app">
                                         <b><p id='kipas1'>Mati</p></b>
                                    </a>
                                <div class="icon">
                                    <i class="fas fa-wind"></i>
                                </div>
                            </div>
                  </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                        <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <b>Kontrol Lampu</b>
                                </div>
                                <br>
                                <a class="btn btn-app" onclick='sendONLampu(event)'>
                                        <i class="fa fa-play"></i> Play
                                      </a>
                                      <a class="btn btn-app" onclick='sendOFFLampu(event)'>
                                            <i class="fa fa-stop"></i> Stop
                                          </a>
                                          <a class="btn btn-app" >
                                                <b><p id='lampu1'>Mati</p></b>
                                           </a>
                                      <div class="icon">
                                            <i class="far fa-lightbulb"></i>
                                          </div>
                            </div>
                  </div>
                </div>
                <!-- ./col -->
              </div>
    </div>
@stop
