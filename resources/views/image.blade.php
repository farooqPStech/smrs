<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Meter Picture</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="bg-dark">
    <div style="width:800px; margin:0 auto;">
        <img style=" width: 100%" class="img-thumbnail"
            src="data:image/png;base64,{{ $reading->image }}">
    </div>
</body>

</html>
