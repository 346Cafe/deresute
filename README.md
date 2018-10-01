# deresute
## deresuteについて
**deresute**はCGSSAPI/CGSS AssetBundleのPHP用ライブラリです。

## セットアップ
### unitylz4のコンパイル
```
# php-unity-lz4をGitHubからクローン
git clone https://github.com/towa0131/php-unity-lz4
cd php-unity-lz4

# コンパイルを実行
./install.sh
```
これでエクステンションがインストールされるので`php.ini`ファイルに`extension=unitylz4`を追加してください。

### deresute本体のセットアップ
```
# deresuteをGitHubからクローン
git clone https://github.com/towa0131/deresute
cd deresute

# Composerのダウンロード
curl -sS https://getcomposer.org/installer | php
# ライブラリをインストール
php composer.phar install
```

### アプリの実行
`php app.php`

## FAQ
### unitylz4のコンパイルでエラー
既にmakeやg++などのインストールはされていますか？
もしされていないならコンパイルの前にインストールを行なってください。

### 本体のAPIの使用時にエラー
必要なエクステンションがインストールされていない可能性があります。一度、`php app.php`で`app.php`を実行し、エラーが出ないか確認してください。

### app.phpの実行時にエラー
`No module loaded : msgpack`などの文が表示されていませんか？
もしされているのならば、**deresute**の使用に必要なエクステンションがインストールされていません。インストールを行うことでエラーが表示されなくなります。