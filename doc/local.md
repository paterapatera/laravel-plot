# 開発環境の準備

## 事前準備

- Docker Desktopをインストールする
- Gitでファイルをプルする

## チェックアウト後、最初に一回だけ実行するコマンド

1. プロダクトのディレクトリ内で下記を実行

```sh
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php80-composer:latest \
    composer install --ignore-platform-reqs
```

2. `.env.local` を複製して `.env` を作成  
基本的にローカル環境では `.env.local` が呼ばれるが、dockerのMySQLなどは `.env` が読まれる  
apacheなどで設定した `APP_ENV=loca` によって `.env.local` が呼ばれるのはLaravelの隠れ仕様

## 開発環境の起動 

以下のコマンドでDockerを起動

```sh
./vendor/bin/sail up
```

SailコマンドのエイリアスをBashに設定すると便利（推奨）

```sh
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```

以降は以下のように実行可能

```sh
sail up
sail up -d
sail down
sail composer require laravel/sanctum
```

[sailコマンドの詳細](https://readouble.com/laravel/8.x/ja/sail.html)

## Dockerで起動するサーバー

- laravel
- mysql
- redis
- meilisearch
- mailhog
- selenium

## メールの出力確認方法

http://localhost:8025

## MySQLの確認用URL

DBクライアントに下記のURLを指定することでDBの確認が可能

http://localhost:3306
