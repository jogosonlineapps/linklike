<?php
//settings
$cache_ext  = '.html'; //file extension
$cache_time     = 3600;  //Cache file expires after these seconds (1 hour = 3600 sec) (8 hour = 28800 sec) (12 hour = 43200 sec)
$cache_folder   = 'cache/'; //folder to store Cache files
$ignore_pages   = array('', '');

$dynamic_url    = 'http://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING']; // requested dynamic page (full url)
$cache_file     = $cache_folder.md5($dynamic_url).$cache_ext; // construct a cache file
$ignore = (in_array($dynamic_url,$ignore_pages))?true:false; //check if url is in ignore list

if (!$ignore && file_exists($cache_file) && time() - $cache_time < filemtime($cache_file)) { //check Cache exist and it's not expired.
    ob_start('ob_gzhandler'); //Turn on output buffering, "ob_gzhandler" for the compressed page with gzip.
    readfile($cache_file); //read Cache file
    echo '<!-- cached page - '.date('l jS \of F Y h:i:s A', filemtime($cache_file)).', Page : '.$dynamic_url.' -->';
    ob_end_flush(); //Flush and turn off output buffering
    exit(); //no need to proceed further, exit the flow.
}
//Turn on output buffering with gzip compression.
ob_start('ob_gzhandler');
?>
<?php include('siteconfig.php');
include('url_slug.php');
$page_title = $topfreeapps_title;
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0"/>
		<title><?php echo $page_title; ?> - <?php echo $site_title; ?></title>
		<meta name="description" content="<?php echo $page_title; ?> - <?php echo $site_title; ?>" />
		<meta name="keywords" content="<?php echo $site_keywords; ?>" />
		<!-- CSS and Scripts -->
		<?php include 'includes/headscripts.php'; ?>
		<?php include 'ads/head_code.php'; ?>
	</head>
<body>
<?php include 'includes/header.php'; ?> 

<?php 
$catad = file_get_contents("ads/category_top_ad_728x90.php",NULL);
if(isset($catad) and !empty($catad)): ?>
<div class="pagetopbox">
<center>
<?php echo $catad; ?>
</center>
</div>
<?php endif; ?>

<div class="container">
<div class="pagetitle">
<h1><?php echo $page_title; ?></h1>
</div>
<div class="pageresults">
<input type="radio" name="viewswitch" class="viewswitch-small" checked="checked" />
<ul class="page-itemlist">
<?php
$string = file_get_contents('https://itunes.apple.com/'.$site_country.'/rss/topfreeapplications/limit=100/xml');
// Remove the colon ":" in the <xxx:yyy> to be <xxxyyy>
$string = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $string);
$xml = simplexml_load_string($string);

// Output
$rssresults = '';

foreach ($xml->entry as $val) {
    // edit foreach
	$musicid = $val->id;
	$musicid = str_replace("/id/","xxx",$musicid);
	$musicid=explode('/id',$musicid);
	$musicid=explode('?',$musicid[1]);
	$catid = $val->category->attributes()->scheme;
	$catid = str_replace("/id/","xxx",$catid);
	$catid=explode('/id',$catid);
	$catid=explode('?',$catid[1]);
	$bigimage = preg_replace('/100x100/ms', "150x150", $val->imimage[2]);
	
    $rssresults .= '<li class="page-item"><div class="pagethumb" data-toggle="tooltip" data-placement="top" title="'.$val->imname.'"><a href="'.$site_url.'/app/'.$musicid[0].'/'.cano($val->imname).'"><img data-src="'.$bigimage.'" src="'.$site_url.'/images/loading.svg" ></a></div>
		<div class="info"><h3><a href="'.$site_url.'/app/'.$musicid[0].'/'.cano($val->imname).'">'.$val->imname.'</a></h3>
		<h4>'.$val->imartist.'</h4>
		<a class="genre" href="'.$site_url.'/category/'.$catid[0].'/'.cano($val->category->attributes()->label).'">'.$val->category->attributes()->label.'</a>
		<span class="summary">'.substr($val->summary, 0, 430).'...</span>
		</div>
	</li>';
   
}
echo $rssresults;

?>
</ul>
</div>
</div>
<?php 
$catad = file_get_contents("ads/category_bottom_ad_728x90.php",NULL);
if(isset($catad) and !empty($catad)): ?>
<div class="pagebottombox">
<center>
<?php echo $catad; ?>
</center>
</div>
<?php endif; ?>
<script src="<?php echo $site_url;?>/js/imglazyload.js"></script>
<script>
			//lazy loading
			$('.page-itemlist img').imgLazyLoad({
				// jquery selector or JS object
				container: window,
				// jQuery animations: fadeIn, show, slideDown
				effect: 'fadeIn',
				// animation speed
				speed: 600,
				// animation delay
				delay: 400,
				// callback function
				callback: function(){}
			});
</script>
<?php include 'includes/footer-category.php'; ?>
</body>
</html>
<?php
######## Your Website Content Ends here #########

if (!is_dir($cache_folder)) { //create a new folder if we need to
    mkdir($cache_folder);
}
if(!$ignore){
    $fp = fopen($cache_file, 'w');  //open file for writing
    fwrite($fp, ob_get_contents()); //write contents of the output buffer in Cache file
    fclose($fp); //Close file pointer
}
ob_end_flush(); //Flush and turn off output buffering

?>