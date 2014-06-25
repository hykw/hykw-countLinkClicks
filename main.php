<?php
  /**
   * @package HYKW count link clicks plugin
   * @version 0.1
   */
  /*
    Plugin Name: HYKW count link clicks plugin
    Plugin URI: https://github.com/hykw/hykw-countLinkClicks
    Description: 外部サイトへのリンククリック数をカウント
    Author: hitoshi-hayakawa
    Version: 0.1
  */
  /*
[Usage]
前準備
  index.phpと .htaccess を DOCUMENT_ROOT/redir/ に配置

functions.phpで設定
・配置ディレクトリを設定
  $ghykwCLC_redir = '/redir';

・ログ出力ディレクトリ
  $ghykwCLC_logdir = '/var/www/xxx/logs';

・ログファイルのprefix(prefix_2014-0625.log みたいな形で出力される）
  $ghykwCLC_logPrefix = 'redir_'

・ホワイトリスト登録
  $ghykwCLC_whitelists = array(
     'https?:\/\/google.com',
     'https?:\/\/google.co.jp',
  );

★リンク記載方法
       <a href="/redir/http://google.com/">google</a>

   */

