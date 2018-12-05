<!DOCTYPE html>
<html>
    <head>
        <title>Erreur 404 | Page introuvable</title>
        <meta charset="UTF-8">
        <!-- <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css"> -->
        <link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet" >
        <style>
            html, body {
                height: 100%;
            }
        </style>
    </head>
    <body>
        <a href="{{url('/')}}">
            <div class="content">
                <img src="{{asset('img/error404.png')}}" class="img-responsive" alt="">
                <!-- <div class="title"><i class="fa fa-ban fa-3x"></i> Aucune page trouvée ! </div> -->
            </div>
        </a>
    </body>
</html>
