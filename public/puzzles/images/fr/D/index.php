<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Type: image/jpeg");

extract($_GET);

if($download) header("Content-Disposition: attachment; filename=$filename.jpg");

$fh=fopen("./$filename.jpg","rb");
$content=fread($fh,filesize("./$filename.jpg"));
fclose($fh);

echo $content;

?>