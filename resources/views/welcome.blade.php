<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1>Hello world</h1>
{{-- <img src="{{ url('storage/images/ouisso.png') }}" alt="" title=""> --}}
<img src="{{ route('image.show', ['imageName' => 'ouisso.png']) }}" alt="Your Image">
</body>
</html>
