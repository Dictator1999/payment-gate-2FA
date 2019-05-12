<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>E-mail confirmation</title>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5>E-mail Verification</h5>
        </div>
        <div class="card-body">
            <p class="lead">Hi {{ $name }}. You have recenty signup to our website. To verify your E-mail, please confirm</p>
            <a class='btn btn-info' href="{{ url('email-verify',['link'=>$link]) }}">Confirm</a>
            <p>Best Regard</p>
            <p>Dictator Team</p>
        </div>
    </div>
</div>
</body>
</html>