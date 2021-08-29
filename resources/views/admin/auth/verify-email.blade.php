<x-layouts.guest>
    <x-web.auth.card>
        <div class="mb-4 text-sm text-gray-600">
            ご入力のメールアドレスに確認URLをお送りしました。記載してあるURLをクリックし、登録を完了してください
        </div>

        @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            ご登録のメールアドレスに新しい確認URLをお送りしました
        </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('admin.verification.send') }}">
                @csrf

                <div>
                    <x-button>確認URLの再送信</x-button>
                </div>
            </form>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf

                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900">
                    ログアウト
                </button>
            </form>
        </div>
    </x-web.auth.card>
</x-layouts.guest>
