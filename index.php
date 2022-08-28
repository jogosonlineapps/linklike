<?php include('siteconfig.php'); ?>
<!doctype html>
<html lang="en">
    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0"/>
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="mobile-web-app-capable" content="yes">
		<title><?php echo $homepage_title; ?></title>
		<meta name="description" content="<?php echo $homepage_desc; ?>" />
		<meta property="og:site_name" content="<?php echo $site_title; ?>"/>
		<meta property="og:type" content="website"/>
		<meta property="og:title" content="<?php echo $homepage_title; ?>"/>
		<meta property="og:description" content="<?php echo $homepage_desc; ?>"/>
		<link rel="canonical" href="<?php echo $site_url;?>" />
		<link rel="alternate" href="https://linklike.com.br/" hreflang="pt" />
		<link rel="alternate" href="https://linklike.com.br/de/" hreflang="de" />
		<link rel="alternate" href="https://linklike.com.br/jp/" hreflang="ja" />
		<link rel="alternate" href="https://linklike.com.br/fr/" hreflang="fr" />
		<link rel="alternate" href="https://linklike.com.br/it/" hreflang="it" />
		<link rel="alternate" href="https://linklike.com.br/es/" hreflang="es" />
		<link rel="alternate" href="https://linklike.com.br/kr/" hreflang="ko" />
		<link rel="alternate" href="https://linklike.com.br/tr/" hreflang="tr" />
		<link rel="alternate" href="https://linklike.com.br/vi/" hreflang="vi" />
		<link rel="alternate" href="https://linklike.com.br/cn/" hreflang="zh" />
		<link rel="alternate" href="https://linklike.com.br/ru/" hreflang="ru" />
		<link rel="alternate" href="https://linklike.com.br/sa/" hreflang="ar" />
		<link rel="alternate" href="https://linklike.com.br/" hreflang="x-default" />
		<?php include 'includes/headscripts.php'; ?>
		<script type="text/javascript" src="<?php echo $site_url;?>/js/jquery.jcarousel.min.js"></script>
		<?php include 'ads/head_code.php'; ?>
    </head>
<body>
<?php include 'includes/header.php'; ?>
<?php include('url_slug.php'); ?> 
<div class="scrollback">
<div class="container">
<div class="scrollcontent">
<div class="carousel fcarousel fcarousel-6">
<div class="scrolltitle">
<h1><?php echo $home_carousel_title; ?></h1>
</div>
<div class="carousel-container">
<div class="jcarousel">
<ul>
<?php
if (!empty($homeapps_genre_id)) {
$string = file_get_contents('https://itunes.apple.com/'.$site_country.'/rss/topgrossingapplications/limit=10/genre='.$homeapps_genre_id.'/xml');
}
else {
$string = file_get_contents('https://itunes.apple.com/'.$site_country.'/rss/topgrossingapplications/limit=10/xml');
}
$string = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $string);
$xml = simplexml_load_string($string);
$rssresults = '';
foreach ($xml->entry as $val) {
	$musicid = $val->id;
	$musicid = str_replace("/id/","xxx",$musicid);
	$musicid=explode('/id',$musicid);
	$musicid=explode('?',$musicid[1]);
	$bigimage = preg_replace('/100x100/ms', "180x180", $val->imimage[2]);
    $rssresults .= '<li><div class="homethumb"><a href="'.$site_url.'/app/'.$musicid[0].'/'.cano($val->imname).'"><img src="'.$bigimage.'" alt="" width="180px" height="180px" ><span class="overlay"><p>'.$val->imname.'</p></span></a></div></li>';
}
echo $rssresults;
?>
</ul>
</div>
<div class="carousel-prev"></div>
<div class="carousel-next"></div>
</div>
</div>
</div>
</div>
</div>
<div class="homeframe">
<div class="container" style="margin-bottom:20px;">
<div class="pagetitle" style="clear: both;">
<h1><?php echo $featapps_title;?></h1>
</div>
<?php 
$data = file_get_contents('https://itunes.apple.com/lookup?id='.$featapp_id1.'&entity=software&country='.$site_country.'');
$response = json_decode($data);
$artist_title = $response->results[0]->artistName;
$coll_title = $response->results[0]->trackName;
$main_image = preg_replace('/100x100bb.jpg/ms', "180x180bb.jpg", $response->results[0]->artworkUrl100);
?>
<div class="col-md-6" style="padding: 0px;">
<div class="featbox box1">
<div class="featimage">
<a href="<?php echo $site_url;?>/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>"><img data-src="<?php echo $main_image?>" src="<?php echo $site_url?>/images/loading.svg" width="180px" height="180px" alt="<?php echo $response->results[0]->trackName;?>"></a>
<?php if(isset($response->results[0]->averageUserRating) and !empty($response->results[0]->averageUserRating)): ?>
        <div class="feat-rating">
		<div class="score"><span><?php echo $score_mpage;?>: <?php echo $response->results[0]->averageUserRating;?></span></div>
		<div class="bigstarbox">
        <span class="bigstars"><?php echo $response->results[0]->averageUserRating;?></span>
		</div>
		<div class="scorecount"><span><?php echo $from_mpage;?> <?php echo number_format($response->results[0]->userRatingCount);?> <?php echo $ratings_mpage;?></span></div>
		</div>
<?php endif; ?>
</div>
<h3><?php echo $coll_title;?></h1>
<h4><?php echo $byartist_mpage?> <?php echo $artist_title;?></h2>
<ul style="list-style:none;padding: 0px;">
<li><b><?php echo $release_mpage;?>:</b> <?php echo substr($response->results[0]->releaseDate,0,10);?></li>
<li><b><?php echo $cat_mpage;?>:</b> <?php echo $response->results[0]->genres[0];?></li>
</ul>
<p>
<?php $feat_descr = strip_tags($response->results[0]->description); ?>
<?php echo substr($feat_descr,0,200); ?>...
</p>
<a href="<?php echo $site_url;?>/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" style="margin-right: 10px;" class="btn btn-raised btn-warning"><?php echo $rmore_title;?></a>
</div>
</div>
<?php 
$data = file_get_contents('https://itunes.apple.com/lookup?id='.$featapp_id2.'&entity=software&country='.$site_country.'');
$response = json_decode($data);

$artist_title = $response->results[0]->artistName;
$coll_title = $response->results[0]->trackName;
$main_image = preg_replace('/100x100bb.jpg/ms', "180x180bb.jpg", $response->results[0]->artworkUrl100);
?>
<div class="col-md-6" style="padding: 0px;">
<div class="featbox box2">
<div class="featimage">
<a href="<?php echo $site_url;?>/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>"><img data-src="<?php echo $main_image?>" src="<?php echo $site_url?>/images/loading.svg" width="180px" height="180px" alt="<?php echo $response->results[0]->trackName;?>"></a>
<?php if(isset($response->results[0]->averageUserRating) and !empty($response->results[0]->averageUserRating)): ?>
        <div class="feat-rating">
		<div class="score"><span><?php echo $score_mpage;?>: <?php echo $response->results[0]->averageUserRating;?></span></div>
		<div class="bigstarbox">
        <span class="bigstars"><?php echo $response->results[0]->averageUserRating;?></span>
		</div>
		<div class="scorecount"><span><?php echo $from_mpage;?> <?php echo number_format($response->results[0]->userRatingCount);?> <?php echo $ratings_mpage;?></span></div>
		</div>
<?php endif; ?>
</div>
<h3><?php echo $coll_title;?></h1>
<h4><?php echo $byartist_mpage?> <?php echo $artist_title;?></h2>
<ul style="list-style:none;padding: 0px;">
<li><b><?php echo $release_mpage;?>:</b> <?php echo substr($response->results[0]->releaseDate,0,10);?></li>
<li><b><?php echo $cat_mpage;?>:</b> <?php echo $response->results[0]->genres[0];?></li>
</ul>
<p>
<?php $feat_descr = strip_tags($response->results[0]->description); ?>
<?php echo substr($feat_descr,0,200); ?>...
</p>
<a href="<?php echo $site_url;?>/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" style="margin-right: 10px;" class="btn btn-raised btn-warning"><?php echo $rmore_title;?></a>
</div>
</div>
<?php 
$data = file_get_contents('https://itunes.apple.com/lookup?id='.$featapp_id3.'&entity=software&country='.$site_country.'');
$response = json_decode($data);
$artist_title = $response->results[0]->artistName;
$coll_title = $response->results[0]->trackName;
$main_image = preg_replace('/100x100bb.jpg/ms', "180x180bb.jpg", $response->results[0]->artworkUrl100);
?>
<div class="col-md-6" style="padding: 0px;">
<div class="featbox box3">
<div class="featimage">
<a href="<?php echo $site_url;?>/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>"><img data-src="<?php echo $main_image?>" src="<?php echo $site_url?>/images/loading.svg" width="180px" height="180px" alt="<?php echo $response->results[0]->trackName;?>"></a>
<?php if(isset($response->results[0]->averageUserRating) and !empty($response->results[0]->averageUserRating)): ?>
        <div class="feat-rating">
		<div class="score"><span><?php echo $score_mpage;?>: <?php echo $response->results[0]->averageUserRating;?></span></div>
		<div class="bigstarbox">
        <span class="bigstars"><?php echo $response->results[0]->averageUserRating;?></span>
		</div>
		<div class="scorecount"><span><?php echo $from_mpage;?> <?php echo number_format($response->results[0]->userRatingCount);?> <?php echo $ratings_mpage;?></span></div>
		</div>
<?php endif; ?>
</div>
<h3><?php echo $coll_title;?></h1>
<h4><?php echo $byartist_mpage?> <?php echo $artist_title;?></h2>
<ul style="list-style:none;padding: 0px;">
<li><b><?php echo $release_mpage;?>:</b> <?php echo substr($response->results[0]->releaseDate,0,10);?></li>
<li><b><?php echo $cat_mpage;?>:</b> <?php echo $response->results[0]->genres[0];?></li>
</ul>
<p>
<?php $feat_descr = strip_tags($response->results[0]->description); ?>
<?php echo substr($feat_descr,0,200); ?>...
</p>
<a href="<?php echo $site_url;?>/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" style="margin-right: 10px;" class="btn btn-raised btn-warning"><?php echo $rmore_title;?></a>
</div>
</div>
<?php 
$data = file_get_contents('https://itunes.apple.com/lookup?id='.$featapp_id4.'&entity=software&country='.$site_country.'');
$response = json_decode($data);
$artist_title = $response->results[0]->artistName;
$coll_title = $response->results[0]->trackName;
$main_image = preg_replace('/100x100bb.jpg/ms', "180x180bb.jpg", $response->results[0]->artworkUrl100);
?>
<div class="col-md-6" style="padding: 0px;">
<div class="featbox box4">
<div class="featimage">
<a href="<?php echo $site_url;?>/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>"><img data-src="<?php echo $main_image?>" src="<?php echo $site_url?>/images/loading.svg" width="180px" height="180px" alt="<?php echo $response->results[0]->trackName;?>"></a>
<?php if(isset($response->results[0]->averageUserRating) and !empty($response->results[0]->averageUserRating)): ?>
        <div class="feat-rating">
		<div class="score"><span><?php echo $score_mpage;?>: <?php echo $response->results[0]->averageUserRating;?></span></div>
		<div class="bigstarbox">
        <span class="bigstars"><?php echo $response->results[0]->averageUserRating;?></span>
		</div>
		<div class="scorecount"><span><?php echo $from_mpage;?> <?php echo number_format($response->results[0]->userRatingCount);?> <?php echo $ratings_mpage;?></span></div>
		</div>
<?php endif; ?>
</div>
<h3><?php echo $coll_title;?></h1>
<h4><?php echo $byartist_mpage?> <?php echo $artist_title;?></h2>
<ul style="list-style:none;padding: 0px;">
<li><b><?php echo $release_mpage;?>:</b> <?php echo substr($response->results[0]->releaseDate,0,10);?></li>
<li><b><?php echo $cat_mpage;?>:</b> <?php echo $response->results[0]->genres[0];?></li>
</ul>
<p>
<?php $feat_descr = strip_tags($response->results[0]->description); ?>
<?php echo substr($feat_descr,0,200); ?>...
</p>
<a href="<?php echo $site_url;?>/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" style="margin-right: 10px;" class="btn btn-raised btn-warning"><?php echo $rmore_title;?></a>
</div>
</div>
<?php 
$data = file_get_contents('https://itunes.apple.com/lookup?id='.$featapp_id5.'&entity=software&country='.$site_country.'');
$response = json_decode($data);
$artist_title = $response->results[0]->artistName;
$coll_title = $response->results[0]->trackName;
$main_image = preg_replace('/100x100bb.jpg/ms', "180x180bb.jpg", $response->results[0]->artworkUrl100);
?>
<div class="col-md-6" style="padding: 0px;">
<div class="featbox box5">
<div class="featimage">
<a href="<?php echo $site_url;?>/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>"><img data-src="<?php echo $main_image?>" src="<?php echo $site_url?>/images/loading.svg" width="180px" height="180px" alt="<?php echo $response->results[0]->trackName;?>"></a>
<?php if(isset($response->results[0]->averageUserRating) and !empty($response->results[0]->averageUserRating)): ?>
        <div class="feat-rating">
		<div class="score"><span><?php echo $score_mpage;?>: <?php echo $response->results[0]->averageUserRating;?></span></div>
		<div class="bigstarbox">
        <span class="bigstars"><?php echo $response->results[0]->averageUserRating;?></span>
		</div>
		<div class="scorecount"><span><?php echo $from_mpage;?> <?php echo number_format($response->results[0]->userRatingCount);?> <?php echo $ratings_mpage;?></span></div>
		</div>
<?php endif; ?>
</div>
<h3><?php echo $coll_title;?></h1>
<h4><?php echo $byartist_mpage?> <?php echo $artist_title;?></h2>
<ul style="list-style:none;padding: 0px;">
<li><b><?php echo $release_mpage;?>:</b> <?php echo substr($response->results[0]->releaseDate,0,10);?></li>
<li><b><?php echo $cat_mpage;?>:</b> <?php echo $response->results[0]->genres[0];?></li>
</ul>
<p>
<?php $feat_descr = strip_tags($response->results[0]->description); ?>
<?php echo substr($feat_descr,0,200); ?>...
</p>
<a href="<?php echo $site_url;?>/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" style="margin-right: 10px;" class="btn btn-raised btn-warning"><?php echo $rmore_title;?></a>
</div>
</div>
<?php 
$data = file_get_contents('https://itunes.apple.com/lookup?id='.$featapp_id6.'&entity=software&country='.$site_country.'');
$response = json_decode($data);
$artist_title = $response->results[0]->artistName;
$coll_title = $response->results[0]->trackName;
$main_image = preg_replace('/100x100bb.jpg/ms', "180x180bb.jpg", $response->results[0]->artworkUrl100);
?>
<div class="col-md-6" style="padding: 0px;">
<div class="featbox box6">
<div class="featimage">
<a href="<?php echo $site_url;?>/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>"><img data-src="<?php echo $main_image?>" src="<?php echo $site_url?>/images/loading.svg" width="180px" height="180px" alt="<?php echo $response->results[0]->trackName;?>"></a>
<?php if(isset($response->results[0]->averageUserRating) and !empty($response->results[0]->averageUserRating)): ?>
        <div class="feat-rating">
		<div class="score"><span><?php echo $score_mpage;?>: <?php echo $response->results[0]->averageUserRating;?></span></div>
		<div class="bigstarbox">
        <span class="bigstars"><?php echo $response->results[0]->averageUserRating;?></span>
		</div>
		<div class="scorecount"><span><?php echo $from_mpage;?> <?php echo number_format($response->results[0]->userRatingCount);?> <?php echo $ratings_mpage;?></span></div>
		</div>
<?php endif; ?>
</div>
<h3><?php echo $coll_title;?></h1>
<h4><?php echo $byartist_mpage?> <?php echo $artist_title;?></h2>
<ul style="list-style:none;padding: 0px;">
<li><b><?php echo $release_mpage;?>:</b> <?php echo substr($response->results[0]->releaseDate,0,10);?></li>
<li><b><?php echo $cat_mpage;?>:</b> <?php echo $response->results[0]->genres[0];?></li>
</ul>
<p>
<?php $feat_descr = strip_tags($response->results[0]->description); ?>
<?php echo substr($feat_descr,0,200); ?>...
</p>
<a href="<?php echo $site_url;?>/app/<?php echo $response->results[0]->trackId;?>/<?php echo cano($response->results[0]->trackName);?>" style="margin-right: 10px;" class="btn btn-raised btn-warning"><?php echo $rmore_title;?></a>
</div>
</div>
<div class="pagetitle">
<h1>Latest Apps</h1>
</div>
<div class="pageresults">
<input type="radio" name="viewswitch" class="viewswitch-small" checked="checked" />
<ul class="page-itemlist">
<?php
$string = file_get_contents('https://itunes.apple.com/'.$site_country.'/rss/newapplications/limit=100/xml');
$string = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $string);
$xml = simplexml_load_string($string);
$rssresults = '';
foreach ($xml->entry as $val) {
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
		</div>
	</li>';
}
echo $rssresults;
?>
</ul>
</div>
<script src="<?php echo $site_url;?>/js/imglazyload.js"></script>
<script>
			
			$('.homeframe img').imgLazyLoad({
				container: window,
				effect: 'fadeIn',
				speed: 600,
				delay: 400,
				callback: function(){}
			});
</script>
<script language="JavaScript" type="text/javascript" src="<?php echo $site_url;?>/js/bigstar-rating.js"></script>
<script type="text/javascript" language="JavaScript">
jQuery(function() {
           jQuery('span.bigstars').bigstars();
      });
</script>
</div>
</div>
<?php include 'includes/footer-index.php'; ?>
</body>
</html>