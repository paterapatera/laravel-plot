# ログ表示機能

これはローカル環境でのみ動く  
`storage/logs` 内のログをブラウザで確認と検索ができる  
URL: `/read-log`  

## 変更内容

- ローカル環境でのみリクエストが実行されるミドルウェア作成(localEnv)
- ログを確認するコントローラ作成

## 変更したファイル

- [app/Http/Kernel.php](../app/Http/Kernel.php)
- [app/Http/Middleware/LocalEnv.php](../app/Http/Middleware/LocalEnv.php)
- [app/Http/Controllers/Web/ReadLogController.php](../app/Http/Controllers/Web/ReadLogController.php)
- [resources/views/web/read-log](../resources/views/web/read-log)
