<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Show Konsumen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: lightgray">

    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <img src="{{ asset('storage/konsumens/'.$konsumen->profilepic) }}" class="rounded" style="width: 100%">
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <h3>{{ $konsumen->Name }}</h3>
                        <hr/>
                        <p>Tanggal Lahir: {{ \Carbon\Carbon::parse($konsumen->birthday)->format('d-m-Y') }}</p>
                        <p>Alamat: {{ $konsumen->address }}</p>
                        <p>Gender: {{ $konsumen->gender }}</p>
                        <hr/>
                        <p>Profile Picture:</p>
                        <img src="{{ asset('storage/konsumens/'.$konsumen->profilepic) }}" class="img-fluid" style="max-width: 200px;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
