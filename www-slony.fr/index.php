<?PHP
require_once("layout.php");

$target=$_GET["target"];

switch($target){
  case '404':
	header("HTTP/1.0 404 Not Found");
	echo layout_header();
	echo layout_defaultblock('404, NOT FOUND','',file_get_contents('./content/404.txt'));
	break;
  case 'docs':
	$text = '';
        $file = str_replace(array("/",".."),"",$_GET["file"]);
        if ($file == "") $file="index.html";
        $text=@file_get_contents('./adminguide/current/doc/adminguide/'.$file);
	if ($text == ''){
		header("HTTP/1.0 404 Not Found");
		echo layout_header();
		echo layout_defaultblock('404, NOT FOUND','',file_get_contents('./content/404.txt'));
	} else {
        	echo layout_header();
		$title=between("TITLE\n>","</TITLE",$text);
	        echo layout_defaultblock($title,$link,$text);
	}
        break;
  case 'download':
       $version = "";
       if ($_GET["version"] == "1.0") $version = "1.0";
       if ($_GET["version"] == "1.1") $version = "1.1";
       if ($_GET["version"] == "1.2") $version = "1.2";
       if ($_GET["version"] == "2.0") $version = "2.0";
       $platform = "";
       if ($_GET["platform"]=="source") $platform = "source";
       if ($_GET["platform"]=="win32") $platform = "win32";
       if ($_GET["platform"]=="rpm") $platform = "rpm";

       if ($version == ""){
               $path = './downloads/';
       } else {
	       if (platform != "") {
               	  $path = './downloads/'.$version.'/'.$platform.'/';
	       } else {
               	  $path = './downloads/'.$version.'/';
	       }
               $sums = @file_get_contents($path.'MD5SUMS');
       }

       if ($dh = opendir($path)) {
                       $files = array();
               $mysums = array();
                       while (($file = readdir($dh)) !== false) {
                       if ((($version == "") || ($platform == ""))&& ($file != ".") && ($file != "..")){
                               array_push($files, $file);
                       } else  if ((substr($file, strlen($file) - 4) == '.bz2') ||
                                       (substr($file, strlen($file) - 4) == '.rpm') ||
                                       (substr($file, strlen($file) - 4) == '.zip')
					) {
                                       array_push($files, $file);
                               $pos = 0;
                               $pos = strpos($sums,$file);
                               if ($pos > 0)
                                       array_push($mysums,substr($sums,$pos-34,32));
                       }
                       }
       closedir($dh);
       }

       // Sort the files and display
       //sort($files);
       echo layout_header();
       $text = "";
       if ($version == "") {
                $text .= "<h1>Please select a Slony version</h1>";
                $text .= "<table>\n";
       } else {
	   if ($platform == ""){
                $text .= "<h1>Please select your preferred packaging</h1>";
                $text .= "<table>\n";
	   } else {
               $text .= "<h1>Slony $version files</h1>";
               $text .= "<table>\n<tr><td>File</td><td>Type</td><td>MD5SUM</td></tr>\n";
	   }
       }
       $c = 0;
       foreach ($files as $file) {
               $title = $file;
               $type = "";
               if (strpos($file,'tar.bz2')>0) $type="Slony tarball";
               if (strpos($file,'.zip')>0) $type="Slony for windows msi installer";
               if ((strpos($file,'doc')>0) && ((strpos($file,'tar.bz2')>0))) $type="Documentation";
               if (strpos($file,'source') !== false) $type="Slony source files and documentation";
               if (strpos($file,'win32') !== false) $type="Slony for Windows";
	       if (strpos($file,'rpm') !== false)  $type="Slony RPM files";
	       if (($version == "") || ($platform =="")) $file .= "/";

               $text .= "<tr><td><a href=\"$file\" title=\"$title\">$title</a></td><td>$type</td><td>$mysums[$c]</td></tr>\n";
               $c++;
       }
       $text .= "</table>\n";
       $url = "/downloads/";
       $title = "Slony downloads";
       if ($platform != "") {
           $url .= $version."/";
           $title .= " / ".$version." /";
       }
       echo layout_defaultblock($title,$url,$text);

       break;
  case 'cvs':
	echo layout_header();
	$fp = fopen('./content/cvs.txt','r');
	if ($fp){
		$title=trim(fgets($fp,256));
		$link=trim(fgets($fp,256));
		$text="";
		while (!feof($fp)) $text.=fgets($fp,256);
		fclose($fp);
	}
	echo layout_defaultblock($title,$link,$text);	
	break;
  default:
	echo layout_header();
	echo layout_leftcol_start();
	$fp = fopen('./content/intro.txt','r');
	if ($fp){
		$title=trim(fgets($fp,256));
		$link=trim(fgets($fp,256));
		$text="";
		while (!feof($fp)) $text.=fgets($fp,256);
		fclose($fp);
	}
	echo layout_introblock($title,$link,$text);

        $fp = fopen('./content/frontpage.txt','r');
        if ($fp){
                $text="";
		$article_count = 0;
                while (!feof($fp)){
			 $buf=fgets($fp,256);
			 if (strpos($buf,'---')!==false){
				if ($article_count < 5){
					if ($text != "") echo layout_defaultblock($title,$link,$text);
				} else {
					break;
				}
				$article_count++;
				$text = "";
				$title = trim(@fgets($fp,256));
				$link = trim(@fgets($fp,256));
			 } else {
				$text .= $buf;
			 }
		}
                fclose($fp);
        }

	echo layout_leftcol_stop();
	echo layout_rightcol_start();

        $fp = fopen('./content/news.txt','r');
        if ($fp){
                $text="";
		$article_count = 0;
                while (!feof($fp)){
			 $buf=fgets($fp,256);
			 if (strpos($buf,'---')!==false){
				if ($article_count < 12){
					if ($text != "") echo layout_defaultblock($title,$link,$text,$stamp,$poster);
				} else {
                                        break;
                                }
                                $article_count++;
				$text = "";
				$title = trim(@fgets($fp,256));
				$link = trim(@fgets($fp,256));
				$stamp = @fgets($fp,256);
				$poster = @fgets($fp,256);
			 } else {
				$text .= $buf;
			 }
		}
                fclose($fp);
        }

	echo layout_rightcol_stop();
}


echo layout_footer();

?>
