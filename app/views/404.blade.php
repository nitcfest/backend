<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Page not found</title>

    <link href="{{URL::to('/')}}/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/main.css" rel="stylesheet">


</head>

<body>

    <div style="height:50px;"></div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h2>Page not found!</h2>

                <h4><a href="{{URL::to(Config::get('app.homepage'))}}">&laquo; Back to homepage</a></h4>        
            </div>
        </div>
    </div>

</body>

</html>