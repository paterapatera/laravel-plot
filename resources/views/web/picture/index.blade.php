<x-layouts.guest>
    <div class="p-6">
        <form method="POST" action="{{ route('picture.upload') }}" enctype="multipart/form-data">
            @csrf
            <!-- Email Address -->
            <div>
                @error('file')
                <div class="text-red-700 py-3">{{ $message }}
                </div>
                @enderror
                <x-input id="file" class="block mt-1 w-full" type="file" name="file" :value="old('file')" required />
            </div>

            <div class="flex items-center mt-4">
                <x-button class="ml-3">アップロード</x-button>
            </div>
        </form>
    </div>
    <div class="p-6">
        <h1 class="text-xl">画像一覧</h1>
        <div class="flex space-x-4">
            @foreach ($files as $file)
            <div><a class="font-medium text-blue-500 underline hover:text-blue-700" href="{{$file}}"><img
                        class="object-contain h-48 w-full" src="{{ urldecode($file) }}"></a></div>
            @endforeach
        </div>
    </div>
</x-layouts.guest>
