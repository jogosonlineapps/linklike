<?php
//settings
$cache_ext  = '.html'; //file extension
$cache_time     = 43200;  //Cache file expires after these seconds (1 hour = 3600 sec) (8 hour = 28800 sec) (12 hour = 43200 sec)
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
<?php 
include('siteconfig.php');
include('url_slug.php');

$link = $_GET['link'];
$link = explode("/", $link);

$link_id = $link[0];
$data = file_get_contents('https://itunes.apple.com/lookup?id='.$link_id.'&limit=1&entity=software&country='.$site_country.'');
$response = json_decode($data);

if ($response->resultCount == '0') {
$data = file_get_contents('https://itunes.apple.com/lookup?id='.$link_id.'&limit=1&entity=software');
$response = json_decode($data);
}
$seo_desc = substr($response->results[0]->description, 0, 160);
$item_title = $response->results[0]->trackName;
$artist_id = $response->results[0]->artistId;
$artist_title = $response->results[0]->artistName;
$coll_title = $response->results[0]->trackName;
$genre_title = $response->results[0]->primaryGenreName;
$main_image = preg_replace('/100x100bb.jpg/ms', "230x230bb.jpg", $response->results[0]->artworkUrl100);
$geo_link = preg_replace('/itunes/ms', "geo.itunes", $response->results[0]->trackViewUrl);

// Snippet from PHP Share: http://www.phpshare.org
function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' kB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
}

?>
<!doctype html>
<html lang="en">
    <head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="mobile-web-app-capable" content="yes">
		<title><?php echo $item_title;?> - Linklike</title>
		<meta name="description" content="<?php echo $seo_desc;?>">
		<meta property="og:site_name" content="<?php echo $site_title; ?>">
		<meta property="og:type" content="article">
		<meta property="og:title" content="<?php echo $item_title;?> - Linklike">
		<meta property="og:description" content="<?php echo $seo_desc;?>">
		<meta property="og:image" content="<?php echo $main_image;?>">
		<meta property="og:url" content="<?php echo $site_url;?>/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" />
		<link rel="canonical" href="<?php echo $site_url;?>/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" />
		<link rel="alternate" href="https://linklike.com.br/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" hreflang="pt" />
	    <link rel="alternate" href="https://linklike.com.br/de/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" hreflang="de" />
	    <link rel="alternate" href="https://linklike.com.br/jp/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" hreflang="ja" />
        <link rel="alternate" href="https://linklike.com.br/it/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" hreflang="it" />
        <link rel="alternate" href="https://linklike.com.br/fr/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" hreflang="fr" />
		<link rel="alternate" href="https://linklike.com.br/es/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" hreflang="es" />
		<link rel="alternate" href="https://linklike.com.br/kr/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" hreflang="ko" />
		<link rel="alternate" href="https://linklike.com.br/tr/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" hreflang="tr" />
		<link rel="alternate" href="https://linklike.com.br/vi/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" hreflang="vi" />
        <link rel="alternate" href="https://linklike.com.br/cn/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" hreflang="zh" />
		<link rel="alternate" href="https://linklike.com.br/ru/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" hreflang="ru" />
		<link rel="alternate" href="https://linklike.com.br/sa/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" hreflang="ar" />
		<link rel="alternate" href="https://linklike.com.br/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" hreflang="x-default" />
<?php include 'includes/headscripts.php'; ?>
<?php include 'ads/head_code.php'; ?>
</head>
<body>
<?php include 'includes/header-apps.php'; ?>
<div class="headpage">
<div class="container">
		<div class="headpageimage">
		<img src="<?php echo $main_image?>" height="230" width="230" alt="imagem do aplicativo <?php echo $item_title;?>">
		</div>
		<div class="headpageinfo">
		<h1 class="product-title"><?php echo $coll_title;?></h1>
		<h2 class="product-stock"><?php echo $byartist_mpage?> <?php echo $artist_title;?></h2>
		<ul style="list-style:none;padding: 0px;">
		<li><b><?php echo $cat_mpage;?>:</b> <a href="<?php echo $site_url?>/category/<?php echo $response->results[0]->primaryGenreId;?>/<?php echo cano($response->results[0]->primaryGenreName);?>"><?php echo $response->results[0]->primaryGenreName;?></a></li>
		<li><b><?php echo $release_mpage;?>:</b> <?php echo substr($response->results[0]->releaseDate,0,10);?></li>
		<li><b><?php echo $curv_mpage;?>:</b> <?php echo $response->results[0]->version;?></li>
		<li><b><?php echo $adrat_mpage;?>:</b> <?php echo $response->results[0]->contentAdvisoryRating;?></li>
		<li><b><?php echo $filsz_mpage;?>:</b> <?php echo formatSizeUnits($response->results[0]->fileSizeBytes);?></li>
		<?php
		if (isset($response->results[0]->sellerUrl) and $response->results[0]->sellerUrl == true){
		$sellurl = '<a href="'.$response->results[0]->sellerUrl.'" target="_blank">'.$artist_title.'</a>';
		}else{
		$sellurl = $artist_title;
		}
		?>
		<li><b><?php echo $dev_mpage;?>:</b> <?php echo $sellurl;?></li>
		<li><b><?php echo $comp_mpage;?> ios:</b> <?php echo $req_mpage;?> <?php echo $response->results[0]->minimumOsVersion;?> <?php echo $req_end_mpage;?></li>
		<li><b><?php echo $comp_mpage;?> Android:</b> Requer Android 4.4 ou superior</li>
		</ul>
		</div>
		<div class="headpageright">
		<div class="postactions">
<p>
</div>
</div>
</div>
</div>
<div class="container">
<div class="col-md-8" style="margin-top: 30px;padding-left:0px;">
<ul class="side-itemlist">
	<li class="side-item">
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2980309919316168"
     crossorigin="anonymous"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-2980309919316168"
     data-ad-slot="1502514354"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
	</li>
</ul>
<div class="postmain">
<div class="postmaintitle" style="margin-bottom:5px;">
<h3><?php echo $descriphead_mpage;?></h3>
<br>
<br>
<font face="Arial Black" color="#7817BE"><?php echo $coll_title;?></font></b> - <?php echo $response->results[0]->description;?>
<br>
<?php if(isset($itunes_id) and !empty($itunes_id)): ?>
<a href="<?php echo $geo_link; ?>&at=<?php echo $itunes_id;?>" target="_blank" class="btn btn-raised btn-warning"><?php echo $response->results[0]->formattedPrice;?> <?php echo $itunelink_mpage;?></a>
<a href="https://play.google.com/store/search?q=<?php echo $item_title;?>&c=apps" target="_blank" class="btn btn-raised btn-warning">Android</a>
<?php endif; ?>
</div>
</div>
<ul class="side-itemlist">
	<li class="side-item">
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2980309919316168"
     crossorigin="anonymous"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-2980309919316168"
     data-ad-slot="1502514354"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
	</li>
</ul>
<?php 
$postad = file_get_contents("ads/singlepage_ad_728x90.php",NULL);
if(isset($postad) and !empty($postad)): ?>
<div style="margin:0px 0px 20px 0px;">
<?php echo $postad; ?>
</div>
<?php endif; ?>
</div>
<div class="col-md-4" style="padding-right:0px;">
<div class="post-sidebar">
<div class="post-sidebar-box">
<?php 
$sidead = file_get_contents("ads/sidebar_ad_336x280.php",NULL);
if(isset($sidead) and !empty($sidead)): ?>
<div style="margin:0px 0px 20px 0px;">
<?php echo $sidead; ?>
</div>
<?php endif; ?>
<ul class="side-itemlist">
<?php
$data_al = file_get_contents('https://itunes.apple.com/lookup?id='.$artist_id.'&limit=4&entity=software');
$response_al = json_decode($data_al);
if (isset($response_al->results) and $response_al->results == true){
foreach ($response_al->results as $result_al)
{
	if ($result_al->wrapperType == 'software') {
	echo '<li class="side-item"><div class="side-thumb"><a href="'.$site_url.'/app/'.$result_al->trackId.'/'.cano($result_al->trackName).'" ><img data-src="'.$result_al->artworkUrl100.'" src="'.$site_url.'/images/loading.svg" alt="linklike.com.br" ></a></div>
	<div class="info"><h3><a href="'.$site_url.'/app/'.$result_al->trackId.'/'.cano($result_al->trackName).'">'.$result_al->trackName.'</a></h3>
		<h4>'.$result_al->artistName.'</h4>';
		if (isset($result_al->averageUserRating) and $result_al->averageUserRating == true){
		echo '';
		}
	echo '</div>
	</li>';
	}
}
}
?>
</ul>
</div>
</div>
</div>
</div>
<script src="<?php echo $site_url;?>/js/imglazyload.js"></script>
<script>
			$('.container img').imgLazyLoad({
				container: window,
				effect: 'fadeIn',
				speed: 600,
				delay: 400,
				callback: function(){}
			});
</script>
<?php include "includes/footer.php"; ?>
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