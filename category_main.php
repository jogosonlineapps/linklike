<?php include('siteconfig.php');
function cano($s){
	$s = $output = trim(preg_replace(array("`'`", "`[^a-z0-9]+`"),  array("", "-"), strtolower($s)), "-");
	return $s;
}
$genre_id = $_GET['id'];
$genre_title = $_GET['title'];
/* $genre_title = htmlspecialchars($genre_title); */
$genre_title = strip_tags($genre_title);
$genre_title = str_replace("-", " ", $genre_title);
$genre_title = ucwords($genre_title);
$page_title = $genre_title;
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0"/>
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="mobile-web-app-capable" content="yes">
		<title><?php echo $page_title; ?> <?php echo $catpage_title; ?> - <?php echo $site_title; ?></title>
		<meta name="description" content="<?php echo $page_title; ?> <?php echo $catpage_title; ?>: Several categories of apps, showing the peculiarities of each one. The objective was to present you with several applications with different features to help you choose which one or which ones best suit your needs." />
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
<h1><?php echo $page_title; ?> <?php echo $catpage_title; ?></h1>
</div>
<div class="pageresults">
<input type="radio" name="viewswitch" class="viewswitch-small" checked="checked" />
<ul class="page-itemlist">
<?php
$string = file_get_contents('https://itunes.apple.com/'.$site_country.'/rss/topgrossingapplications/limit=100/genre='.$genre_id.'/xml');
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
		<a class="genre" href="'.$site_url.'/genre/'.$catid[0].'/'.cano($val->category->attributes()->label).'">'.$val->category->attributes()->label.'</a>
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