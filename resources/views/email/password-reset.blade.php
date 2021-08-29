@component('components.layouts.email')

# アカウントのパスワードリセットを受け付けました。

@component('mail::button', ['url' => $actionUrl, 'color' => 'primary'])
パスワードリセット
@endcomponent

このパスワードリセットのリンクは、{{ $count }}分で期限切れになります。

このメールに心当たりがない場合は、お手数ですが削除してください。

@endcomponent
