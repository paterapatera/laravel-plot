<x-layouts.guest>
    <x-admin.auth.card>
        <div class="mb-4 text-sm text-gray-600">
            入力したメールアドレスへパスワードリセットメールをお送りします
        </div>

        <!-- Session Status -->
        <x-admin.auth.session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-admin.auth.validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" placeholder="メールアドレス" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>送信</x-button>
            </div>
        </form>
    </x-admin.auth.card>
</x-layouts.guest>
