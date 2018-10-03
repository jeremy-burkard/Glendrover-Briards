<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<link rel="stylesheet" type="text/css" href="stylesheets/standard.css" />
	<link rel="stylesheet" type="text/css" href="stylesheets/menu.css" />
	<title>GlenDrover Briards - Photos</title>

	<style type="text/css">
		.container {
			/*relative causes the children to be positioned within the div!*/
			position: relative;
			top: 100px;
			left: 100px;
			/*Size of a small photo in Flickr is Max250 x Max250*/
			height: 530px;
			width: 530px;
			background-color: black;
			}
			
		.slidePhoto {
			/* Use position absolute on the class to position all of the photos over the same spot*/
			border: 2px ridge gray;
			}
	</style>

	<!-- Go to http://mootools.net, download entire core
	Go to More, select Assets and Element.Position, and download -->
	<script type="text/javascript" src="mootools-1.2.5-core-yc.js"></script>
	<script type="text/javascript" src="mootools-1.2.5.1-more.js"></script> 

	<script type="text/javascript">
		var photos = new Array();
		<?php
			//Get the list of photos from Flickr
			$url = "https://api.flickr.com/services/rest/?method=flickr.photosets.getPhotos&api_key=8de16aa893fd4cd2644f9fe2343588a5&photoset_id=72157632002728289&extras=url_m,description";
			$rsp = file_get_contents($url);

			//Load the XML list into a DOM Document
			$coll_doc = new DOMDocument();
			$coll_doc->loadXML($rsp);
			$root = $coll_doc->documentElement;

			//Add the URL for each photo to a javascript array
			$i=0;
			foreach ($root->getElementsByTagName("photo") as $photo){
				$photoURL = $photo->getAttribute("url_m");
				echo "photos[$i]=new Array('$photoURL');";
				$i=$i+1;
			}
		?>

		var photoIndex=0;
		var photo;
		var preloader = new Asset.images(photos, {
			onComplete: function(){
				photo=$('photo');
				photo.setProperty('src',photos[photoIndex][0]);
				changePhoto.periodical(2000);
				$('photo').position({relativeTo: $('container'), position: 'center'});
			}
		});
		
		var changePhoto = function() {
			if (photoIndex + 1 >= photos.length) {
				photoIndex = 0;
			} else {
				photoIndex = photoIndex + 1;
			}

			var tween=photo.get('tween',{property:'opacity',duration:500});
			tween.start(0).chain(
				function(){
					photo.setProperty('src',photos[photoIndex][0]);
					$('photo').position({relativeTo: $('container'), position: 'center'});
					this.start(1);
				}
			);
		};
 	</script>
</head>

<body>
	<?php 
		/*include_once "subpages/header.php";
		include_once $_SERVER['DOCUMENT_ROOT']."/classes/CURL.v1_0_0.php";
		use BurkardBinary\Library\CURL\v1_0_0 as BBS_CURL;

		class EnumFlickrSize {
			const SMALL_SQUARE_75 = 'url_s';
			const LARGE_SQUARE_150 = 'url_l';
			const THUMBNAIL_100 = 'url_t';
			const SMALL_240 = 'url_m';
			const SMALL_320 = 'url_n';
			const MEDIUM_500 = 'url';
			const MEDIUM_640 = 'url_z';
			const MEDIUM_800 = 'url_c';
			const LARGE_1024 = 'url_b';
			const ORIGINAL = 'url_o';
		}
		
		class EnumFlickrREST {
			const REST_URL = 'https://api.flickr.com/services/rest/';
			const GET_PHOTOS = 'flickr.photosets.getPhotos';
			const GET_TREE = 'flickr.collections.getTree';
			const GET_SIZES = 'flickr.photos.getSizes';
		}
		//$flickrCollection = BBS_Photo\CollectionFactory::CreateCollection("PhotoGallery");

		//$url = EnumFlickrREST::REST_URL.'?method='.EnumFlickrREST::GET_TREE.'&api_key=8de16aa893fd4cd2644f9fe2343588a5&user_id=90031102@N03';
		$url = EnumFlickrREST::REST_URL.'?method='.EnumFlickrREST::GET_PHOTOS.'&api_key=8de16aa893fd4cd2644f9fe2343588a5&user_id=90031102@N03';
		$url = $url.'&extras='.EnumFlickrSize::MEDIUM_640.',description&photoset_id=72157632002728289';	
		$photosResponse = BBS_CURL\CURLFunctions::MakeCURLCall($url);
		$photos = $photosResponse->photoset;*/
	?>
	
	<div class="content">
		<div id="container" class="container">
			<img id="photo" class="slidePhoto" src="#"/>
		</div>
		<div class="finally"></div>
	</div>
</body>