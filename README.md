hykw-countLinkClicks
====================

# 前準備
## ファイルの配置
- index.phpと .htaccess を DOCUMENT_ROOT/redir/ に配置

## functions.phpで設定
- 上記で配置したディレクトリを設定
> $ghykwCLC_redir = '/redir';

- ログ出力ディレクトリ
> $ghykwCLC_logdir = '/var/www/xxx/logs';

- ログファイルのprefix(prefix_2014-0625.log みたいな形で出力される）
> $ghykwCLC_logPrefix = 'redir_'

- リダイレクト先のホワイトリストを登録
> $ghykwCLC_whitelists = array(  
>    'https?:\/\/google.com',  
>    'https?:\/\/google.co.jp',  
>  );

## リンク記載方法
    <a href="/redir/http://google.com/">google</a>

