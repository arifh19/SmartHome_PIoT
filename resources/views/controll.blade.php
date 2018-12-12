@extends('adminlte::page')
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.0/css/all.css" integrity="sha384-aOkxzJ5uQz7WBObEZcHvV5JvRW3TUc2rNPA7pe3AwnsUohiw1Vj2Rgx2KSOkF5+h" crossorigin="anonymous">


@section('title', 'Smart Home')

@section('content_header')
   <h1>Monitoring Rumah</h1>
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
    mqtt.subscribe(topic4, {qos: 0});
    $('#topic4').val(topic4);

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
      document.getElementById('publish').value ="";
  }
  function sendOFFKipas(e){
    //use the below if you want to publish to a topic on connect
    var key=e.keyCode || e.which;
      var Message = '0';
      // message = new Paho.MQTT.Message(client_name+" : "+Message);
      message = new Paho.MQTT.Message(Message);
      message.destinationName = topic4;
      mqtt.send(message);
      document.getElementById('publish1').value ="";
  }
  function onConnectionLost(response) {
    setTimeout(MQTTconnect, reconnectTimeout);
    $('#status').val("connection lost: " + responseObject.errorMessage + ". Reconnecting");

  };

  function onMessageArrived(message) {

    var topic = message.destinationName;
    var payload = message.payloadString;
    $('#ws').prepend(payload+"</br>");

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
    <h1>Mosquitto Websockets</h1>
    <div>
    <div>Subscribed to <input type='text' id='topic' disabled />
        Status: <input type='text' id='status' size="80" disabled /></div>
        <button type="button" id="publish" value="1" onclick="sendMessage(event)" class="btn btn-success">ON</button>
        <button type="button" id="publish1" value="0" onclick="sendMessage1(event)" class="btn btn-success">OFF</button>
        <p id='name'></p>

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
                                <a class="btn btn-app">
                                        <i class="fa fa-play"></i> Play
                                      </a>
                                      <a class="btn btn-app">
                                            <i class="fa fa-stop"></i> Stop
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
