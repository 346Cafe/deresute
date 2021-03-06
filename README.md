# deresute
![banner](https://is2-ssl.mzstatic.com/image/thumb/Purple113/v4/70/df/b2/70dfb280-6f72-9894-90d6-1a23ab8159d9/pr_source.png/1920x1080bb.png)

[![GitHub license](https://img.shields.io/github/license/346Cafe/deresute.svg?style=for-the-badge)](https://github.com/346Cafe/deresute/blob/master/LICENSE)
[![GitHub forks](https://img.shields.io/github/forks/346Cafe/deresute.svg?style=for-the-badge)](https://github.com/346Cafe/deresute/network)
[![GitHub stars](https://img.shields.io/github/stars/346Cafe/deresute.svg?style=for-the-badge)](https://github.com/346Cafe/deresute/stargazers)
[![GitHub last commit](https://img.shields.io/github/last-commit/346Cafe/deresute.svg?style=for-the-badge)](https://github.com/346Cafe/deresute/commits/master)

[![PHP from Packagist](https://img.shields.io/packagist/php-v/towa0131/deresute.svg?style=for-the-badge)](https://github.com/346Cafe/deresute/)
[![Travis CI](https://img.shields.io/travis/346Cafe/deresute.svg?style=for-the-badge)](about:blank/)
[![Packagist version](https://img.shields.io/packagist/v/towa0131/deresute.svg?style=for-the-badge)](https://packagist.org/packages/towa0131/deresute)
[![Packagist download](https://img.shields.io/packagist/dt/towa0131/deresute.svg?style=for-the-badge)](https://packagist.org/packages/towa0131/deresute)

## deresuteについて
**deresute**はCGSS API / AssetBundle ToolのPHP用ライブラリです。

## セットアップ
### 必要なパッケージのインストール
```
$ apt -y install git g++ make cmake composer curl php-dev php-bcmath php-curl php-mbstring php-msgpack php-sqlite3
```

### php-unity-lz4のコンパイル
- php-unity-lz4をGitからクローン
```
$ git clone https://github.com/towa0131/php-unity-lz4
$ cd php-unity-lz4
```

- コンパイル及びインストールを実行
```
$ ./install.sh
```

### php-cgssのコンパイル
- php-cgssをGitからクローン
```
# サブモジュールも同時にクローン
$ git clone --recursive https://github.com/towa0131/php-cgss
$ cd php-cgss
```
- libcgssをコンパイル
```
$ cd libcgss
$ cmake .
$ make
$ cp bin/x64/libcgss.* /usr/lib/
```

- php-cgssをコンパイル

```
$ cd ..
$ phpize
$ ./configure

# インストール
$ make install
```

エクステンションがインストールされるので`php.ini`ファイルに`extension=unitylz4`, `extension=cgss`を追加し、有効化してください。

### deresute本体のセットアップ
***下記のいずれかの方法でインストールが可能です。***

#### Gitからクローン
- **deresute**をGitからクローン
```
$ git clone https://github.com/346Cafe/deresute
$ cd deresute
```

- 各ライブラリのインストール
```
$ composer install
```

#### Packagistからダウンロード
- **deresute**のダウンロード / 各ライブラリのインストール
```
$ composer require towa0131/deresute
```

### テストの実行
- #### Gitからダウンロードした場合
```
$ php test-app.php
```
- #### Packagistからダウンロードした場合
```
$ php vendor/towa0131/deresute/test-app.php
```

## 機能
### 音声ファイル/アセットバンドルのダウンロード
**deresute**の機能の一つとして、音声ファイル、アセットバンドルのダウンロード機能があります。
```
$ cd tools/AssetDownloader/
$ php app.php
```
音声ファイルはすべてのファイルのダウンロード完了後、自動でWAVEフォーマットに変換されます。

## 追加予定の機能
- アカウントの作成機能
    - 正規クライアントへのアカウントの引継ぎ
- ダウンロードするデータをユーザが指定可能にする

## FAQ
### unitylz4のコンパイルでエラー
事前パッケージのインストールはされていますか？
もしされていないならコンパイルの前にインストールを行なってください。

### 本体のAPIの使用時にエラー
必要なエクステンションがインストールされていない可能性があります。一度、`php test-app.php`でテストスクリプトを実行し、エラーが発生しないか確認してください。

### test-app.phpの実行時にエラー
`No module loaded : msgpack`などの文が表示されていませんか？
もしされているのならば、必要なエクステンションがインストールされていません。インストールを行うことでエラーが発生しなくなります。

### わからないことがあれば
お気軽に[Issues](https://github.com/346Cafe/deresute/issues)、[Twitter](https://twitter.com/usaminium)にてご質問お願いします。

## 使用しているライブラリ
- [towa0131/php-unity-lz4](https://github.com/towa0131/php-unity-lz4) - unity3d.lz4フォーマットを扱うPHPエクステンション
- [towa0131/php-cgss](https://github.com/towa0131/php-cgss) - ACBファイルの展開、HCAファイルのデコードを行うPHPエクステンション
- [phpseclib/mcrypt_compat](https://github.com/phpseclib/mcrypt_compat) - データの暗号化 / 復号化を行うライブラリ
- [gabrielelana/byte-units](https://github.com/gabrielelana/byte-units) - バイト数値をパースするためのライブラリ
- [j4mie/idiorm](https://github.com/j4mie/idiorm) - ORMを扱うライブラリ
