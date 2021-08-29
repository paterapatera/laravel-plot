@component('components.layouts.email')

# メールアドレスを確認するには以下のボタンをクリックしてください。

@component('mail::button', ['url' => $actionUrl, 'color' => 'primary'])
メールアドレスの確認
@endcomponent

このメールに心当たりがない場合は、お手数ですが削除してください。

@endcomponent
