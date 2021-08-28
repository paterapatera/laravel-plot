<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <style>
        .content {
            display: none;
        }
    </style>
</head>

<body class="antialiased">
    <form method="GET" action="{{url()->current()}}">
        UID：<input type="text" name="uid" />
        USER ID：<input type="text" name="user_id" />
        HASH IP：<input type="text" name="hash_ip" />
        <input type="hidden" name="file" value="{{$file}}" />
        <input type="submit" value="検索" />
    </form>
    <table border="1" style="border-collapse: collapse">
        <thead>
            <tr>
                <th>UID</th>
                <th>User ID</th>
                <th>Hash IP</th>
                <th>Message</th>
                <th>Context</th>
                <th>Level</th>
                <th>Date Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
            <tr>
                <td>{{ $log->extra->uid }}</td>
                <td>{{ $log->extra->user_id }}</td>
                <td>{{ $log->extra->hash_ip }}</td>
                <td>{{ $log->message }}</td>
                <td>
                    <button type="button" class="collapsible">Open</button>
                    <pre class="content">{{ json_encode($log->context, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                </td>
                <td>{{ $log->level_name }}</td>
                <td>{{ $log->datetime }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        Array.from(document.getElementsByClassName("collapsible"))
            .forEach((coll) => coll.addEventListener("click", function() {
                const content = this.nextElementSibling;
                content.style.display = content.style.display === "block" ? "none" : "block";
            }));
    </script>
</body>

</html>
