<!DOCTYPE html>
<html lang="en">

<head>
    <title>Image Processing</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="container">
        <h2>Enter VIDEOID</h2>
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
        <form method="post" action="{{ route('test') }}">
            @csrf
            <div class="form-group">
                <label for="single">Single</label>
                <input type="text" class="form-control" id="single" placeholder="Enter youtube video's ID"
                    name="single">
            </div>
            <div class="form-group">
                <label for="multiple">Multiple (seperated by comma)</label>
                <input type="text" class="form-control" id="multiple" placeholder="Enter youtube video's ID(s)"
                    name="multiple">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

</body>

</html>
