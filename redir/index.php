<?php

require('../wp-load.php');

function getValue($values, $key, $retvalue = '')
{
  if (isset($values[$key]))
    return $values[$key];

  return $retvalue;
}

function writelog($logdir, $logprefix, $url, $_server, $_cookie)
{
  $date = date('Y-md');
  $file = sprintf('%s/%s%s.log', $logdir, $logprefix, $date);

  $now = date('Y/m/d H:i:s');
  $csession = getValue($_cookie, 'csession');
  $ip = getValue($_server, 'REMOTE_ADDR');
  $ua = getValue($_server, 'HTTP_USER_AGENT');

  $log = sprintf("%s\t%s\t%s\t%s\t%s\n",
      $now,
      $csession,
      $ip,
      $ua,
      $url
  );

  $fp = fopen($file, 'a');
  fputs($fp, $log);
  fclose($fp);
}

function onErrorRedirect($location = '/')
{
  header(sprintf('Location: %s', $location));
  exit;
}

##################################################

if (!isset($_SERVER['REQUEST_URI']) || !isset($ghykwCLC_redir) || !isset($ghykwCLC_whitelists) || !isset($ghykwCLC_logdir) || !isset($ghykwCLC_logPrefix)) {
    onErrorRedirect();
}

$uri = $_SERVER['REQUEST_URI'];

$pattern = sprintf('/^\%s\/(https?:\/\/.*)/', $ghykwCLC_redir);
$redirectDir = preg_replace($pattern, '$1', $uri);

# illegal url
if ($uri == $redirectDir) {
  onErrorRedirect();
  exit;
}

foreach ($ghykwCLC_whitelists as $whiteURL) {
  $pattern = sprintf('/^%s.*/', $whiteURL);

  if (preg_match($pattern, $redirectDir)) {
    writelog($ghykwCLC_logdir, $ghykwCLC_logPrefix, $redirectDir, $_SERVER, $_COOKIE);
    header('Location: ' . $redirectDir);
    exit;
  }
}
  
onErrorRedirect();
