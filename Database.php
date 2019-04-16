<?php
	class Database
	{
		protected $pdo;

	  public function __construct($host="localhost", $dbname="shop_items", $username="root", $password="")
	  {
			try {
				$this->pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password);
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				echo "Connection problem: ".$e->getMessage();
			}
	  }

		public function getItem($query, $params = [])
	  {
      $item = $this->pdo->prepare($query);
      $item->execute($params);
      return $item->fetch();
	  }

		public function getItems($query, $params = [])
	  {
      $item = $this->pdo->prepare($query);
      $item->execute($params);
      return $item->fetchAll();
	  }

		public function saveItem($query, $params = [])
	  {
      $item = $this->pdo->prepare($query);
      $item->execute($params);
      return TRUE;
	  }

		public function deleteItem($query, $params = [])
	  {
	  	$this->saveItem($query, $params);
	  }
	}