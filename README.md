hykw-countLinkClicks
====================

# 前準備
## ファイルの配置
- index.phpと .htaccess を DOCUMENT_ROOT/redir/ に配置

## functions.phpで、連想配列 $ghykwCLC にパラメータを設定
- redir
 - 上記で配置したディレクトリを設定
- logdir
 - ログ出力ディレクトリ
- logprefix
 - ログファイルのprefix(prefix_2014-0625.log みたいな形で出力される）
- whitelists
 - リダイレクト先のホワイトリストを登録

### 例）
    $ghykwCLC = array(
      'redir' => '/redir',
      'logdir' => '/var/www/xxx/logs',
      'logPrefix' => 'redir_',
      'whitelists' => array(
        'https?:\/\/google.com',
        'https?:\/\/google.co.jp',
       ),
    );

## 外部リンク記載方法
    <a href="/redir/http://google.com/">google</a>

