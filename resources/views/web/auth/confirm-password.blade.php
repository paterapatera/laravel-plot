<x-layouts.guest>
    <x-web.auth.card>
        <div class="mb-4 text-sm text-gray-600">
            続行する前にパスワードの確認が必要です
        </div>

        <!-- Validation Errors -->
        <x-web.auth.validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <!-- Password -->
            <div>
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" placeholder="パスワード" required autocomplete="current-password" />
            </div>

            <div class="flex justify-end mt-4">
                <x-button>確認</x-button>
            </div>
        </form>
    </x-web.auth.card>
</x-layouts.guest>
