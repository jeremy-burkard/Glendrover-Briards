<?php
	namespace BurkardBinary\Library\Photo\v1_1_0;
	include_once $_SERVER['DOCUMENT_ROOT']."/classes/Configuration.v2_0_0.php";
	use BurkardBinary\Library\Configuration\v2_0_0 as BBS_Configuration;
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
		const REST_URL = 'http://api.flickr.com/services/rest/';
		const GET_PHOTOS = 'flickr.photosets.getPhotos';
		const GET_TREE = 'flickr.collections.getTree';
		const GET_SIZES = 'flickr.photos.getSizes';
	}
	
	class FlickrAccountFactory {
		public static function CreateFlickrAccount($flickrAccountId){
			$flickrAccountConfiguration = new FlickrAccountConfiguration('flickrAccount',$flickrAccountId);
			return new FlickrAccount($flickrAccountConfiguration);
		}	
	}
	
	class FlickrAccountConfiguration extends BBS_Configuration\ResourceConfiguration {
		public function getApiKey(){return $this->getProperty('apiKey');}
		public function getUserId(){return $this->getProperty('userId');}
	}
	
	class FlickrAccount {
		protected $collectionTree;
		protected $flickrAccountConfiguration;
		
		function __construct($flickrAccountConfiguration){
			$this->flickrAccountConfiguration = $flickrAccountConfiguration;
		}
		
		public function getCollectionTree(){
			if ($this->collectionTree == null){
				$url = EnumFlickrREST::REST_URL.'?method='.EnumFlickrREST::GET_TREE.'&api_key='.$this->flickrAccountConfiguration->getProperty('apiKey').'&user_id='.$this->flickrAccountConfiguration->getProperty('userId');
				$this->collectionTree = BBS_CURL\CURLFunctions::MakeCURLCall($url);
			}
			return $this->collectionTree;
		}
	}
	
	class CollectionFactory {
		public static function CreateCollection($collectionId){
			$config = BBS_Configuration\ConfigurationFactory::CreateResourceConfiguration('collection',$collectionId);
			$source = $config->getSource();
			switch($source){
				case 'FlickrCollection':
					$collectionConfiguration = new FlickrCollectionConfiguration('collection',$collectionId);
					$flickrAccountId = $collectionConfiguration->getProperty('flickrAccountId');
					$flickrAccount = FlickrAccountFactory::CreateFlickrAccount($flickrAccountId);
					return new FlickrCollection($collectionConfiguration);
					break;
			}
		}
	}
	
	class SetFactory {
		public static function CreateSet($setId){
			$config = BBS_Configuration\ConfigurationFactory::CreateResourceConfiguration('set',$setId);
			$source = $config->getSource();
			switch($source){
				case 'FlickrSet':
					$setConfiguration = new FlickrSetConfiguration('set',$setId);
					$flickrAccountId = $setConfiguration->getProperty('flickrAccountId');
					$flickrAccount = FlickrAccountFactory::CreateFlickrAccount($flickrAccountId);
					return new FlickrSet($setConfiguration);
					break;
			}
		}
	}

	abstract class Collection {
		protected $collectionConfiguration;
		protected $sets;
		
		function __construct($collectionConfiguration){
			$this->collectionConfiguration = $collectionConfiguration;
		}
		
		abstract function getSets();
	}
	
	abstract class Set {
		protected $setConfiguration;
		protected $photos;
		
		function __construct($setConfiguration){
			$this->setConfiguration = $setConfiguration;
		}
		
		abstract function getPhotos();
	}
	
	class FlickrCollection {
		private $flickrAccount;
		
		function __construct($resourceType,$resourceId,$flickrAccount){
			$this->flickrAccount = $flickrAccount;
			parent::__construct($resourceType,$resourceId);
		}
		
		public function getSets(){
			$tree = $this->flickrAccount->getTree();
			$collection = $tree->collections->xpath($this->collectionConfiguration->getXPath());
			$sets = $collection->sets[];
			//@TODO implement getSets
		}
	}
	
	class FlickrSet {
		private $flickrAccount;
		
		function __construct($resourceType,$resourceId,$flickrAccount){
			$this->flickrAccount = $flickrAccount;
			parent::__construct($resourceType,$resourceId);
		}
		
		public function getPhotos(){
			//@TODO Implement getPhotos
		}
	}
	
	class FlickrCollectionConfiguration extends BBS_Configuration\ResourceConfiguration {		
		public function getXPath(){return $this->getProperty('xPath');}
	}
	
	class FlickrSetConfiguration extends BBS_Configuration\ResourceConfiguration {
		public function getXPath(){return $this->getProperty('xPath');}
	}
?>
