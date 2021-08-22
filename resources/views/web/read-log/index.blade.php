<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
</head>

<body class="antialiased">
    <form method="GET" action="{{url()->current()}}">
        Prefix：<input type="text" name="prefix" />
        <input type="submit" value="検索" />
    </form>
    <ul>
        @foreach ($files as $file)
        <li><a href="{{url('/read-log/show?file=' . $file)}}">{{ basename($file) }}</a></li>
        @endforeach
    </ul>
</body>

</html>
