<?php

$wpload = sprintf('%s/wp-load.php', $_SERVER['DOCUMENT_ROOT']);
require_once($wpload);

function getValue($values, $key, $retvalue = '')
{
  if (isset($values[$key]))
    return $values[$key];

  return $retvalue;
}

function writelog($logdir, $logprefix, $url, $_server, $_cookie, $tail = "")
{
  $date = date_i18n('Y-md');
  $file = sprintf('%s/%s%s.log', $logdir, $logprefix, $date);

  $now = date_i18n('Y/m/d H:i:s');
  $csession = getValue($_cookie, 'csession');
  $ip = getValue($_server, 'REMOTE_ADDR');
  $ua = getValue($_server, 'HTTP_USER_AGENT');
  $referer = getValue($_server, 'HTTP_REFERER');

  $log = sprintf("%s\t%s\t%s\t%s\t%s\t%s",
      $now,
      $csession,
      $ip,
      $ua,
      $url,
      $referer
  );
  $log .= sprintf("\t%s\n", $tail);

  $fp = fopen($file, 'a');
  fputs($fp, $log);
  fclose($fp);
}

function onErrorRedirect($location = '/')
{
  header(sprintf('Location: %s', $location));
  exit;
}

function get_CLC_values($hykwCLC)
{
  $redir = $hykwCLC['redir'];
  $logdir = $hykwCLC['logdir'];
  $logprefix = $hykwCLC['logprefix'];
  $whitelists = $hykwCLC['whitelists'];

  return array($redir, $logdir, $logprefix, $whitelists);
}


/**
 * parse_converge_path /redir/converge/ABC/URL → list(ABC, URL)
 *
 * @return array
 */
function parse_converge_path($path_converge, $uri, $hykwCLC)
{
  list($redir, $logdir, $logprefix, $whitelists) = get_CLC_values($hykwCLC);

  $delimiter = sprintf('%s/%s/', $redir, $path_converge);
  $work = explode($delimiter, $uri);
  $trimed_uri = $work[1];

  $split_uri = explode('/', $trimed_uri, 2);
  return array($split_uri[0], $split_uri[1]);
}


##################################################

$isError = FALSE;
if (!isset($ghykwCLC) || !isset($_SERVER['REQUEST_URI']))
  $isError = TRUE;


list($redir, $logdir, $logprefix, $whitelists) = get_CLC_values($ghykwCLC);

if (!isset($redir) || !isset($logdir) || !isset($logprefix) || !isset($whitelists))
  $isError = TRUE;

if ($isError)
  onErrorRedirect();

$uri = $_SERVER['REQUEST_URI'];

$appended_logstr = '';

################ converge 対応
$path_converge = "converge";
$ptn = sprintf("/^\\%s\/%s\//", $redir, $path_converge);
if (preg_match($ptn, $uri)) {
  list($appended_logstr, $redirectDir) = parse_converge_path($path_converge, $uri, $ghykwCLC);
} else {
  $pattern = sprintf('/^\%s\/(https?:\/\/.*)/', $redir);
  $redirectDir = preg_replace($pattern, '$1', $uri);
}



# illegal url
if ($uri == $redirectDir) {
  onErrorRedirect();
  exit;
}

foreach ($whitelists as $whiteURL) {
  $pattern = sprintf('/^%s.*/', $whiteURL);

  if (preg_match($pattern, $redirectDir)) {
    writelog($logdir, $logprefix, $redirectDir, $_SERVER, $_COOKIE, $appended_logstr);
    header('Location: ' . $redirectDir);
    exit;
  }
}

onErrorRedirect();
