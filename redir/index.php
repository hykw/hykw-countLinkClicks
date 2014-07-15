<?php

$wpload = sprintf('%s/wp-load.php', $_SERVER['DOCUMENT_ROOT']);
require_once($wpload);

function getValue($values, $key, $retvalue = '')
{
  if (isset($values[$key]))
    return $values[$key];

  return $retvalue;
}

function writelog($logdir, $logprefix, $url, $_server, $_cookie)
{
  $date = date_i18n('Y-md', false, true);  // WP is UTC
  $file = sprintf('%s/%s%s.log', $logdir, $logprefix, $date);

  $now = date_i18n('Y/m/d H:i:s', false, true);
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

$isError = FALSE;
if (!isset($ghykwCLC) || !isset($_SERVER['REQUEST_URI']))
  $isError = TRUE;

$redir = $ghykwCLC['redir'];
$logdir = $ghykwCLC['logdir'];
$logprefix = $ghykwCLC['logprefix'];
$whitelists = $ghykwCLC['whitelists'];

if (!isset($redir) || !isset($logdir) || !isset($logprefix) || !isset($whitelists))
  $isError = TRUE;

if ($isError)
  onErrorRedirect();

$uri = $_SERVER['REQUEST_URI'];

$pattern = sprintf('/^\%s\/(https?:\/\/.*)/', $redir);
$redirectDir = preg_replace($pattern, '$1', $uri);

# illegal url
if ($uri == $redirectDir) {
  onErrorRedirect();
  exit;
}

foreach ($whitelists as $whiteURL) {
  $pattern = sprintf('/^%s.*/', $whiteURL);

  if (preg_match($pattern, $redirectDir)) {
    writelog($logdir, $logprefix, $redirectDir, $_SERVER, $_COOKIE);
    header('Location: ' . $redirectDir);
    exit;
  }
}
  
onErrorRedirect();
