<body>
    Hello {{$user}}!;<br>
    Here is an image:<br>    
    <img src="{{ $message->embed(public_path('images/test.jpg')) }}">
</body>