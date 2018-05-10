<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <title>Green Eye</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Green Eye</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
        </ul>
    </div>
</nav>
<br><br>
<div class="container">
    @include('flash::message')
    <div class="card">
        <div class="card-body">
            <h2>Application Options:</h2>

            <hr>
            @if($light)
            <a type="button" class="btn btn-danger btn-block" href="/setting/light">Turn Light Off</a>
            @else
            <a type="button" class="btn btn-success btn-block" href="/setting/light">Turn Light On</a>
            @endif
            <br>
            @if($music)
                <a type="button" class="btn btn-danger btn-block" href="/setting/music">Turn Music Off</a>
            @else
                <a type="button" class="btn btn-success btn-block" href="/setting/music">Turn Music On</a>
            @endif



        </div>
    </div>
    <br>
    <div class="card">
        <div class="card-body">
            <h2>Latest Events:</h2>
            @foreach(\App\Models\Event::orderBy('created_at','desc')->get() as $event)
                <div class="media">
                    <img class="mr-3" src="{{$event->getImage()}}" style="max-width: 150px; max-height: 150px;" alt="Generic placeholder image">
                    <div class="media-body">
                        {{$event->message}}
                    </div>
                </div>
                <br>
            @endforeach
            <br>
            <a href="/app/events" class="btn btn-primary btn-block">View All Events</a>
        </div>
    </div>

</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>