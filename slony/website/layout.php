<?PHP

function layout_header($active=""){
$output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
<title>Slony-I</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="/style.css" type="text/css" />
<!-- Design Copyright 2007-2009 Niels Breet -->
</head>

<body>
<div class="header">
  <div class="left">
  <div class="menu">
    <a href="/" title="Aller sur la page principale">Accueil</a>
    <a href="/scm/" title="Aller sur le dépôt CVS de slony">CVS</a>
    <a href="http://lists.slony.info/mailman/listinfo" title="Slony mailinglists">Listes de diffusion</a>
    <a href="/documentation/" title="Aller vers la section documentation">Documentation</a>
    <a href="/downloads/" title="Téléchargez Slony!">Téléchargez !</a>
    <a href="http://bugs.slony.info/bugzilla/" title="Slony-I Bugzilla"> Bugs </a>
  </div>
  </div>
  <div class="right">
  </div>
</div>

<div class="content">
';
return $output;
}

function layout_footer(){
$output ='  
  <br clear="all">
  <div class="footer">
    <div class="intro-header">
      <div class="left"></div>
      <div class="right"><div class="copyright">Contenu &copy; 2007-2010 Slony Development Group / Traduction et hébergement par <a href="http://www.postgresql.fr">PostgreSQLFr</a></div></div>
    </div>
  </div>
</div>

</body>
</html>
';
return $output;
}


function layout_leftcol_start(){
$output = '
  <div class="col1">
';
return $output;
}

function layout_leftcol_stop(){
$output = '
  </div>
';
return $output;
}

function layout_rightcol_start(){
$output = '
  <div class="col2">
';
return $output;
}

function layout_rightcol_stop(){
$output = '
  </div>
';
return $output;
}


function layout_introblock($title="",$link="",$text=""){
$output ='
    <div class="intro-header">
      <div class="left"></div>
      <div class="right"><a href="'.$link.'">'.$title.'</a></div>

    </div>
    <div class="intro-body">
      <div class="top">
        <div class="top-left"></div>
        <div class="top-right"></div>
      </div>
      <div class="text-intro">
	'.$text.'
      </div>
      <div class="bottom">
        <div class="bottom-left"></div>
        <div class="bottom-right"></div>

      </div>
    </div>
';
return $output;
}

function layout_defaultblock($title="",$link="",$text="",$stamp="",$poster=""){
$output = '
    <div class="intro-header">
      <div class="left"></div>
      <div class="right"><a href="'.$link.'">'.$title.'</a></div>
    </div>
    <div class="intro-body">

      <div class="top">
        <div class="top-left"></div>
        <div class="top-right"></div>
      </div>
      <div class="text">
	'.$text.'
	';
if ($stamp != ""){
    $output .= '<div class="news-stamp">'.$poster.' <i>'.$stamp.'</i></div>';
}
    $output .='
      </div>
      <div class="bottom">

        <div class="bottom-left"></div>
        <div class="bottom-right"></div>
      </div>
    </div>

';
return $output;
}

function between ($pre, $post, &$data, $n = 1) {
        $offset = 0;
        for (; $n > 0; $n--) {
                $prepos = strpos($data, $pre, $offset);
                if ($prepos == 0) return false;
                $prepos += strlen($pre);
                $postpos = strpos($data, $post, $prepos);
                if ($postpos == 0) return false;
                $offset = $postpos + strlen($post);
        }
        $taglen = $postpos - $prepos;
        return trim(substr($data, $prepos, $taglen));
}

?>
