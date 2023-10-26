<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="/style.css">

</head>
<body>
    <div class="chat">

    <div class="top">
    <img id="imagen-achicada" src="https://st2.depositphotos.com/6473186/9838/i/450/depositphotos_98388668-stock-photo-chicken-isolated-on-white-background.jpg" alt="avatar">
        <p>El Pollopolloso</p>
        <ul id='Online-onlinoso'>Online</ul>
    </div>
    <div class="messages">
        @include('receive', ['message' => "Hola bebesita..."])

    </div>
    <div class="bottom">
        <form action="">
        <input type="text" id="message" name="message" placeholder="Enter message..." autocomplete="off">
        <button type="submit"></button>
        </form>

    </div>

    </div>
</body>
    <script>
        const pusher = new Pusher('{{config('broadcasting.connections.pusher.key')}}', {cluster:'eu'});
        const channel = pusher.subscribe('public');

        cahnnel.blind('chat', function(data){
            $.post("/receive", {
                _token: '{{csrf_token()}}',
                message: data.message,
            })
            .done(function(res){
                $(".messages > .message").last().after(res);
                $(document).scrollTop($(document).height());
            });
        });

        $("form").submit(function (event){
            event.preventDefault();

            $.ajax({
                url:"broadcast",
                method:'POST',
                headers: {
                    'X-Socket-Id': pusher.connection.socket_id
                },
                data: {
                    _token:     '{{csrf_token()}}',
                    message: $("form #message".val()),
                }
            }).done(function (res){
                $(".messages > .message").last().after(res);
                $("form #message".val(''));
                $(document).scrollTop($(document).height());
            })
        });
    </script>

</html>