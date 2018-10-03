<?php
	namespace BurkardBinary\Library\Configuration\v2_0_0;

	class ConfigurationFactory {
		public static function CreateResourceConfiguration($resourceType,$resourceId){
			return new ResourceConfiguration($resourceType,$resourceId);
		}
		
		public static function CreateDatabaseConfiguration($databaseId){
			return new DatabaseConfiguration($databaseId);
		}
	}
	
	abstract class Configuration {
		protected $domainNode;
		
		public function initialize(){
			$config = simplexml_load_file($_SERVER['DOCUMENT_ROOT'].'/configuration.xml');
			$this->domainNode = $config->{$_SERVER['HTTP_HOST']};
		}
	}
	
	class ResourceConfiguration extends Configuration {
		protected $id;
		protected $type;
		protected $source;
		protected $properties = array();
		
		public function getId(){return $this->id;}
		public function getType(){return $this->type;}
		public function getSource(){return $this->source;}
		public function getProperty($propertyId){return $this->property[$propertyId];}
		
		public function __construct($resourceType,$resourceId){
			$this->initialize();
			$resourceNodes = $this->domainNode->xpath("resources/".$resourceType."[@id='$resourceId']");
			$resourceNode = $resourceNodes[0];
			$this->id = (string)$resourceNode['id'];
			$this->type = (string)$resourceNode->getName();
			$this->source = (string)$resourceNode['source'];
			$this->attributes = $resourceNode->children();
			foreach ($resourceNode->children() as $key=>$property){
				$this->properties[$key] = (string)$property;
			}
		}
	}

	
	class DatabaseConfiguration extends Configuration {
		private $host;
		private $databaseName;
		private $userId;
		private $password;
		
		public function getHost(){return $this->host;}
		public function getDatabaseName(){return $this->databaseName;}
		public function getUserId(){return $this->userId;}
		public function getPassword(){return $this->password;}
		
		public function __construct($databaseId){
			$this->initialize();
			$databaseNodes = $this->domainNode->xpath("databases/database[@id='$databaseId']");
			$databaseNode = $databaseNodes[0];
			$this->host = (string)$databaseNode->host;
			$this->databaseName = (string)$databaseNode->database;
			$this->userId = (string)$databaseNode->user;
			$this->password = (string)$databaseNode->password;
		}
	}