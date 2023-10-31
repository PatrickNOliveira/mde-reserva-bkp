<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pré reserva</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://static.tacdn.com/css2/webfonts/TripSans/TripSans.css?v1.002" 
    crossorigin>

    <!-- fontawesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" 
    integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" 
    crossorigin="anonymous">

    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet">  -->
    <!-- HTTPS <link href="{{ asset('css/main.css?', true) . time() }}" rel="stylesheet" type="text/css"> -->
    <link href="{{ secure_asset('css/main.css?') . time() }}" rel="stylesheet" type="text/css">
  
    <!-- Scripts -->
    <!-- <script type="text/javascript" src="{{ asset('js/main.js') }}"></script> -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

    <style>
        
        .avatar-upload {
            position: relative;
            max-width: 205px;
            margin: 50px auto;
        }

        .avatar-upload .avatar-preview {
            width: 192px;
            height: 192px;
            position: relative;
            border-radius: 100%;
            border: 6px solid #F8F8F8;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
        }

        .avatar-preview div {
          width: 100%;
          height: 100%;
          border-radius: 100%;
          background-size: cover;
          background-repeat: no-repeat;
          background-position: center;
        }

        .info {
          border-color: #F2B203;
          box-shadow: -6px 0 0 #F2B203;
          border: 1px solid #F2B203;
        }

    </style>

</head>

<body>
  <div class="container">
    @yield('content')
  </div>
</body>
