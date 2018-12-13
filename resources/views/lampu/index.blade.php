@extends('adminlte::page')

@section('title', 'Smart Home')

@section('content_header')

@stop

@section('content')

    <?php
    $db_host = '10.33.109.93:33061'; // Nama Server
    $db_user = 'root'; // User Server
    $db_pass = 'secret'; // Password Server
    $db_name = 'projectIoT'; // Nama Database

    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (!$conn) {
        die ('Gagal terhubung MySQL: ' . mysqli_connect_error());
    }

    $sql = 'SELECT id, status, created_at
            FROM lampus';

    $query = mysqli_query($conn, $sql);

    if (!$query) {
        die ('SQL Error: ' . mysqli_error($conn));
    }

    echo '<div class="box-body">
            <table class="table table-bordered">
              <tr>
                <th style="width: 10px">ID</th>
                <th>Status</th>
                <th>Waktu</th>
              </tr>';

    while ($row = mysqli_fetch_array($query))
    {
        if($row['status']=='1'){
            $status = "Menyala";
        }else{
            $status = "Padam";
        }
        echo '<tr>
                <td>'.$row['id'].'</td>
                <td>'.$status.'</td>
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

@endsection
