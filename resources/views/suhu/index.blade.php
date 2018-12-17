@extends('adminlte::page')

@section('title', 'Smart Home')

@section('content_header')
<!-- Load library Chartjs -->
<script src="http://www.chartjs.org/dist/2.7.2/Chart.js"></script>

<style>
    canvas {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
    }
</style>
@stop

@section('content')
<div style="width:50%; margin:30px auto; text-align:center">
        <div id="container"></div>
        <canvas style="left:-50px; position:relative" id="canvas"></canvas>
    </div>
    <script>
        // Deklarasikan variable array untuk menampung label dan data
        var mylabel = [];
        var mydata  = [];
        var request = new XMLHttpRequest();

        // Fungsi untuk menghandle response dari server
        request.onreadystatechange = function() {

            // Kalau request berhasil
            if (this.readyState == 4 && this.status == 200) {

                // Ubah response menjadi objek JSON
                var obj = JSON.parse(this.responseText);

                // Baca semua bari JSON, tambahkan ke variable array
                for (index = 0, len = obj.length; index < 20; index++) {
                    mylabel.push(obj[index].created_at);
                    mydata.push(obj[index].suhu);
                }

                //console.log(obj);

                // Hilangkan gambar loading
                document.getElementById('container').innerHTML= '';

                // Jalankan Chartjs
                var ctx = document.getElementById('canvas').getContext('2d');
                window.myLine = new Chart(ctx, config);
            }
        }

        //=========================================================================
        // Baca data dari web server
        //=========================================================================
        request.open("GET", "http://10.33.109.93/api/v1/suhu", true);
        request.send();
        //=========================================================================

        // Konfigurasi Chartjs
        var color = Chart.helpers.color;
        var config = {
            type: 'line',
            data: {
                // Label diset dengan variable array yang kita buat
                labels: mylabel,

                datasets: [{
                    label: 'Suhu Ruangan',
                    backgroundColor: "red",
                    borderColor: "red",
                    fill: false,

                    // Data diset dengan variable array yang kita buat
                    data: mydata
                }]
            },

            // Pemanis buatan
            options: {
                title: {
                    text: 'Demo Chart IoT Server'
                },
                scales: {
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Waktu'
                        }
                    }],
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Suhu'
                        }
                    }]
                },
            }
        };

        // Tampilkan gambar loading saat halaman baru muncul
        window.onload = function() {
            document.getElementById('container').innerHTML= '<br><br><img src="http://v3.preloaders.net/preloaders/725/Alternative.gif"> <p style="font-family: arial, sans-serif">Tunggu sebentar ya bos, sedang loading..</p>';
        };
    </script>

@endsection

@section('scripts')
    {!! $html->scripts() !!}
@endsection
