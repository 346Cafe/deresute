# deresute
![banner](https://github.com/346Cafe/deresute/raw/master/docs/deresute.png)

[![GitHub license](https://img.shields.io/github/license/346Cafe/deresute.svg?style=for-the-badge)](https://github.com/346Cafe/deresute/blob/master/LICENSE)
[![GitHub forks](https://img.shields.io/github/forks/346Cafe/deresute.svg?style=for-the-badge)](https://github.com/346Cafe/deresute/network)
[![GitHub stars](https://img.shields.io/github/stars/346Cafe/deresute.svg?style=for-the-badge)](https://github.com/346Cafe/deresute/stargazers)
[![GitHub last commit](https://img.shields.io/github/last-commit/346Cafe/deresute.svg?style=for-the-badge)](https://github.com/346Cafe/deresute/commits/master)

[![PHP version](https://img.shields.io/travis/php-v/symfony/symfony.svg?style=for-the-badge)](https://github.com/346Cafe/deresute/)
[![Travis CI](https://img.shields.io/travis/346Cafe/deresute.svg?style=for-the-badge)](about:blank/)
[![Packagist version](https://img.shields.io/packagist/v/towa0131/deresute.svg?style=for-the-badge)](https://packagist.org/packages/towa0131/deresute)
[![Packagist download](https://img.shields.io/packagist/dt/towa0131/deresute.svg?style=for-the-badge)](https://packagist.org/packages/towa0131/deresute)

## deresuteについて
**deresute**はCGSS API / CGSS AssetBundle ToolのPHP用ライブラリです。

## セットアップ
### unitylz4のコンパイル
- php-unity-lz4をGitHubからクローン
```
$ git clone https://github.com/towa0131/php-unity-lz4
$ cd php-unity-lz4
```

- コンパイル/インストールを実行
```
$ ./install.sh
```

これでエクステンションがインストールされるので`php.ini`ファイルに`extension=unitylz4`を追加し、エクステンションを有効化してください。

### deresute本体のセットアップ
***下記のいずれかの方法でインストールが可能です。***

#### GitHubからダウンロード
- **deresute**をGitHubからクローン
```
$ git clone https://github.com/346Cafe/deresute
$ cd deresute
```

- Composerのインストール
```
$ curl -sS https://getcomposer.org/installer | php
```

- 各ライブラリのインストール
```
$ php composer.phar install
```

#### Packagistから**deresute**をダウンロード
- Composerのインストール
```
$ curl -sS https://getcomposer.org/installer | php
```

- **deresute**のダウンロード / 各ライブラリのインストール
```
$ php composer.phar require towa0131/deresute
```

### テストの実行
- #### GitHubからダウンロードした場合
```
$ php test-app.php
```
- #### Packagistからダウンロードした場合
```
$ php vendor/towa0131/deresute/test-app.php
```

## FAQ
### unitylz4のコンパイルでエラー
既にmakeやg++などのインストールはされていますか？
もしされていないならコンパイルの前にインストールを行なってください。

### 本体のAPIの使用時にエラー
必要なエクステンションがインストールされていない可能性があります。一度、`php test-app.php`でテストスクリプトを実行し、エラーが出ないか確認してください。

### test-app.phpの実行時にエラー
`No module loaded : msgpack`などの文が表示されていませんか？
もしされているのならば、**deresute**の使用に必要なエクステンションがインストールされていません。インストールを行うことでエラーが表示されなくなります。

## 使用しているライブラリ
- [towa0131/unity-lz4](https://github.com/towa0131/php-unity-lz4) - unity3d.lz4フォーマットを扱うPHPエクステンション
- [phpseclib/mcrypt_compat](https://github.com/phpseclib/mcrypt_compat) - データの暗号化 / 復号化を行うライブラリ
- [gabrielelana/byte-units](https://github.com/gabrielelana/byte-units) - バイト数値をパース / 変換するためのライブラリ
- [j4mie/idiorm](https://github.com/j4mie/idiorm) - ORMを扱うライブラリ