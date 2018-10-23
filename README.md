# deresute
[![GitHub license](https://img.shields.io/github/license/towa0131/deresute.svg?style=for-the-badge)](https://github.com/towa0131/deresute/blob/master/LICENSE)
[![GitHub forks](https://img.shields.io/github/forks/towa0131/deresute.svg?style=for-the-badge)](https://github.com/towa0131/deresute/network)
[![GitHub stars](https://img.shields.io/github/stars/towa0131/deresute.svg?style=for-the-badge)](https://github.com/towa0131/deresute/stargazers)
[![GitHub last commit](https://img.shields.io/github/last-commit/towa0131/deresute.svg?style=for-the-badge)](https://github.com/towa0131/deresute/commits/master)

[![PHP version](https://img.shields.io/travis/php-v/symfony/symfony.svg?style=for-the-badge)](https://github.com/towa0131/deresute/)
[![Travis CI](https://img.shields.io/travis/towa0131/deresute.svg?style=for-the-badge)](about:blank/)

## deresuteについて
**deresute**はCGSSAPI / CGSS AssetBundle ToolのPHP用ライブラリです。

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
- deresuteをGitHubからクローン
```
$ git clone https://github.com/towa0131/deresute
$ cd deresute
```
- Composerのダウンロード
```
$ curl -sS https://getcomposer.org/installer | php
```
- ライブラリをインストール
```
$ php composer.phar install
```

または、
- Packagistからderesuteをインストール
```
$ php composer.phar require towa0131/deresute
```

### テストの実行
```
$ php test-app.php
```
Packagistからインストールした場合は、
```
$ php vendor/towa0131/deresute/test-app.php
```

## FAQ
### unitylz4のコンパイルでエラー
既にmakeやg++などのインストールはされていますか？
もしされていないならコンパイルの前にインストールを行なってください。

### 本体のAPIの使用時にエラー
必要なエクステンションがインストールされていない可能性があります。一度、`php test-app.php`で`test-app.php`を実行し、エラーが出ないか確認してください。

### test-app.phpの実行時にエラー
`No module loaded : msgpack`などの文が表示されていませんか？
もしされているのならば、**deresute**の使用に必要なエクステンションがインストールされていません。インストールを行うことでエラーが表示されなくなります。