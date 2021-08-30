<x-layouts.guest>
    <div class="p-6">
        <h1 class="text-xl">ダウンロードしたいログをクリックしてください</h1>
        <ul class="list-disc list-inside">
            @foreach ($files as $file)
            <li><a class="font-medium text-blue-500 underline hover:text-blue-700"
                    href="{{route('csv.download', compact('file'))}}">{{ basename($file) }}</a></li>
            @endforeach
        </ul>
    </div>
</x-layouts.guest>
