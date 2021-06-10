<!DOCTYPE html>
<html>
    <head>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Ada Request kesehatan dari </div><br>
                                    Nama : {{ $nama }}<br>
                                    NIK  : {{ $nik }}
                                    <br><br>
                                    Silahkan klik link berikut untuk menindaklanjuti <a href="{{ $link }}">klik</a>
            </div>
        </div>
    </body>
</html>
