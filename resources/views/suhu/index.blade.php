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
                for (index = 0, len = obj.length; index < len; index++) {
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
        request.open("GET", "http://www.komputronika.com/iot/baca/coba/json", true);
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
    <?php
    $db_host = '127.0.0.1:33061'; // Nama Server
    $db_user = 'root'; // User Server
    $db_pass = 'secret'; // Password Server
    $db_name = 'projectIoT'; // Nama Database

    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (!$conn) {
        die ('Gagal terhubung MySQL: ' . mysqli_connect_error());
    }

    $sql = 'SELECT id, suhu, created_at
            FROM suhus';

    $query = mysqli_query($conn, $sql);

    if (!$query) {
        die ('SQL Error: ' . mysqli_error($conn));
    }

    echo '<div class="box-body">
            <table class="table table-bordered">
              <tr>
                <th style="width: 10px">ID</th>
                <th>Suhu</th>
                <th>Waktu</th>
              </tr>';

    while ($row = mysqli_fetch_array($query))
    {
        echo '<tr>
                <td>'.$row['id'].'</td>
                <td>'.$row['suhu'].'</td>
                <td>'.$row['created_at'].'</td>
            </tr>';
    }
    echo '
        </tbody>
    </table>';

    // Apakah kita perlu menjalankan fungsi mysqli_free_result() ini? baca bagian VII
    mysqli_free_result($query);

    // Apakah kita perlu menjalankan fungsi mysqli_close() ini? baca bagian VII
    mysqli_close($conn); ?>
@endsection

@section('scripts')
    {!! $html->scripts() !!}
@endsection
