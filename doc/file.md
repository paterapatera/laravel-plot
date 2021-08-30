# S3の設定

## 事前準備

### コンテナの追加

```
# docker-compose

    minio:
        image: 'minio/minio:latest'
        ports:
            - '${FORWARD_MINIO_PORT:-9000}:9000'
            - '${FORWARD_MINIO_CONSOLE_PORT:-8900}:8900'
        environment:
            MINIO_ROOT_USER: 'sail'
            MINIO_ROOT_PASSWORD: 'password'
        volumes:
            - 'sailminio:/data/minio'
        networks:
            - sail
        command: minio server /data/minio --console-address ":8900"
        healthcheck:
          test: ["CMD", "curl", "-f", "http://localhost:9000/minio/health/live"]
          retries: 3
          timeout: 5s
```

### アダプター追加
[ここで最新バージョンの確認](https://readouble.com/laravel/8.x/ja/filesystem.html#driver-prerequisites)

```sh
sail composer require --with-all-dependencies league/flysystem-aws-s3-v3 "^1.0"
sail composer require league/flysystem-cached-adapter "~1.0"
```

### キャッシュの追加

```php
// config/filesystems.php

    'disks' => [
        ...
        's3' => [
            'driver' => 's3',
            ...
            'cache' => [
                'store' => 'redis',
                'expire' => 600,
                'prefix' => 'cache-prefix',
            ],
        ],

    ],
```

### 環境変数の設定

```sh
# .env & .env.local

# FILESYSTEM_DRIVER=local
FILESYSTEM_DRIVER=s3
AWS_ACCESS_KEY_ID=sail
AWS_SECRET_ACCESS_KEY=password
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=local
AWS_ENDPOINT=http://localhost:9000
AWS_USE_PATH_STYLE_ENDPOINT=true
```

### hostsの追加
```
// hosts

minio localhost
```
