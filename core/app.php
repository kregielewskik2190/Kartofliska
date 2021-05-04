<?php

class App {
	private $db;

	public function __construct($db) {
		$this->db = $db;
	}

	public function hashPassword($password) {
		return hash('md5', $password);
	}

	public function checkAuth($email, $password) {
		$stmt = $this->db->prepare(
			'SELECT * from uzytkownicy WHERE email = :email and password = :password AND active = 1'
		);
		$stmt->bindValue(':email', $email);
		$stmt->bindValue(':password', $this->hashPassword($password));
		$stmt->execute();
		$getUser = $stmt->fetch();
		return (bool)$getUser;
	}

	public function isEmailTaken($email) {
		$stmt = $this->db->prepare('SELECT * from uzytkownicy WHERE email = :email');
		$stmt->bindValue(':email', $email);
		$stmt->execute();
		$getUser = $stmt->fetch();

		return (bool)$getUser;
	}

	public function destroySession() {
		unset($_SESSION);
		session_unset();
		session_destroy();
		session_start();
	}

	public function createUser($email, $password) {
		$stmt = $this->db->prepare('INSERT INTO uzytkownicy (email, password) VALUES (:email, :password)');
		$stmt->bindValue(':email', $email);
		$stmt->bindValue(':password', $this->hashPassword($password));

		$createUser = $stmt->execute();
		return (bool)$createUser;
	}

	public function isValidEmailHash($email, $hash) {
		$stmt = $this->db->prepare(
			'SELECT * from email_hashes WHERE email = :email and hash = :hash AND expired_at >= :current_time'
		);
		$stmt->bindValue(':email', $email);
		$stmt->bindValue(':hash', $hash);
		$stmt->bindValue(':current_time', time());
		$stmt->execute();
		$getHash = $stmt->fetch();
		return (bool)$getHash;
	}

	public function invalidateHash($hash) {
		$stmt = $this->db->prepare('UPDATE email_hashes SET expired_at = 0 WHERE hash = :hash');
		$stmt->bindValue(':hash', $hash);
		$createUser = $stmt->execute();
	}

	public function createEmailHash($email, $hash) {
		$stmt = $this->db->prepare(
			'INSERT INTO email_hashes (email, hash, expired_at) VALUES (:email, :hash, :expired_at)'
		);
		$stmt->bindValue(':email', $email);
		$stmt->bindValue(':hash', $hash);
		$stmt->bindValue(':expired_at', time() + 3600 * 24 * 2);

		$createHash = $stmt->execute();
		return (bool)$createHash;
	}

	public function generateActivateUrl($email) {
		$hash = hash('sha256', $email . 'secret123123123123' . time());
		$this->createEmailHash($email, $hash);

		return $hash;
	}

	public function activateUser($email) {
		$stmt = $this->db->prepare('UPDATE uzytkownicy SET active = 1 WHERE email = :email');
		$stmt->bindValue(':email', $email);
		$createUser = $stmt->execute();
	}

	public function getUser($email) {
		$stmt = $this->db->prepare('SELECT * from uzytkownicy WHERE email = :email');
		$stmt->bindValue(':email', $email);
		$stmt->execute();
		$getUser = $stmt->fetch();

		return $getUser;
	}

	public function getAllUsers() {
		$stmt = $this->db->prepare('SELECT * from uzytkownicy');
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function removeUser($id) {
		$stmt = $this->db->prepare('DELETE from uzytkownicy WHERE id = :id');
		$stmt->bindValue(':id', $id);
		return $stmt->execute();
	}

	public function getAllStadions() {
		$stmt = $this->db->prepare('SELECT * from stadion ORDER BY miejscowosc');
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function createStadion($miejscowosc, $informacje, $druzyna_id, $szerokosc, $wysokosc) {
		$stmt = $this->db->prepare(
			'INSERT INTO stadion (miejscowosc, informacje, druzyna_id, szerokosc, wysokosc) VALUES (:miejscowosc, :informacje, :druzyna_id, :szerokosc, :wysokosc)'
		);
		$stmt->bindValue(':miejscowosc', $miejscowosc);
		$stmt->bindValue(':informacje', $informacje);
		$stmt->bindValue(':druzyna_id', $druzyna_id);
		$stmt->bindValue(':szerokosc', $szerokosc);
		$stmt->bindValue(':wysokosc', $wysokosc);

		$createStadion = $stmt->execute();
		return (bool)$createStadion;
	}

	public function removeStadion($id) {
		$stmt = $this->db->prepare('DELETE from stadion WHERE id = :id');
		$stmt->bindValue(':id', $id);
		return $stmt->execute();
	}
	public function getStadion($id) {
		$stmt = $this->db->prepare('SELECT * from stadion WHERE id = :id');
		$stmt->bindValue(':id', $id);
		$stmt->execute();
		return $stmt->fetch();
	}

	public function updateStadion($id, $miejscowosc, $informacje, $druzyna_id, $szerokosc, $wysokosc) {
		$stmt = $this->db->prepare(
			'UPDATE stadion SET miejscowosc = :miejscowosc, informacje = :informacje, druzyna_id = :druzyna_id, szerokosc = :szerokosc, wysokosc = :wysokosc WHERE id = :id'
		);

		$stmt->bindValue(':id', $id);
		$stmt->bindValue(':miejscowosc', $miejscowosc);
		$stmt->bindValue(':informacje', $informacje);
		$stmt->bindValue(':druzyna_id', $druzyna_id);
		$stmt->bindValue(':szerokosc', $szerokosc);
		$stmt->bindValue(':wysokosc', $wysokosc);

		return (bool)$stmt->execute();
	}
	public function getAllDruzyna() {
		$stmt = $this->db->prepare('SELECT * from druzyna ORDER BY Nazwa');
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getAllNamesDruzyna() {
		$stmt = $this->db->prepare('SELECT Nazwa from druzyna ORDER BY Nazwa');
		$stmt->execute();
		return $stmt->fetchAll();
	}


	public function createDruzyna($Nazwa, $Skrot, $Herb, $Liga, $Miejscowosc) {
		$stmt = $this->db->prepare(
			'INSERT INTO druzyna (Nazwa, Skrot, Herb, Liga, Miejscowosc) VALUES (:Nazwa, :Skrot, :Herb, :Liga, :Miejscowosc)'
		);
		$stmt->bindValue(':Nazwa', $Nazwa);
		$stmt->bindValue(':Skrot', $Skrot);
		$stmt->bindValue(':Herb', $Herb);
		$stmt->bindValue(':Liga', $Liga);
		$stmt->bindValue(':Miejscowosc', $Miejscowosc);

		$createDruzyna = $stmt->execute();
		return (bool) $createDruzyna;
	}

	public function removeDruzyna($ID) {
		$stmt = $this->db->prepare('DELETE from druzyna WHERE ID = :ID');
		$stmt->bindValue(':ID', $ID);
		return $stmt->execute();
	}
	public function getDruzyna($ID) {
		$stmt = $this->db->prepare('SELECT * from druzyna WHERE ID = :ID');
		$stmt->bindValue(':ID', $ID);
		$stmt->execute();
		return $stmt->fetch();
	}

	public function updateDruzyna($ID, $Nazwa, $Skrot, $Herb, $Liga, $Miejscowosc) {

		if (!empty($Herb)) {
		$stmt = $this->db->prepare(
			'UPDATE druzyna SET Nazwa = :Nazwa, Skrot = :Skrot, Herb = :Herb, Liga = :Liga, Miejscowosc = :Miejscowosc WHERE ID = :ID'
		);

		$stmt->bindValue(':ID', $ID);
		$stmt->bindValue(':Nazwa', $Nazwa);
		$stmt->bindValue(':Skrot', $Skrot);
		$stmt->bindValue(':Herb', $Herb);
		$stmt->bindValue(':Liga', $Liga);
		$stmt->bindValue(':Miejscowosc', $Miejscowosc);

		return (bool)$stmt->execute();
		} else {
		$stmt = $this->db->prepare(
			'UPDATE druzyna SET Nazwa = :Nazwa, Skrot = :Skrot, Liga = :Liga, Miejscowosc = :Miejscowosc WHERE ID = :ID'
		);

		$stmt->bindValue(':ID', $ID);
		$stmt->bindValue(':Nazwa', $Nazwa);
		$stmt->bindValue(':Skrot', $Skrot);
		$stmt->bindValue(':Liga', $Liga);
		$stmt->bindValue(':Miejscowosc', $Miejscowosc);

		return (bool)$stmt->execute();
		}

	}

	function getAllSpotkania() {

		$stmt =  $this->db->prepare('SELECT * FROM `spotkania`');
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getSpotkania($ID) {
		$stmt = $this->db->prepare('SELECT * from `spotkania` WHERE ID = :ID');
		$stmt->bindValue(':ID', $ID);
		$stmt->execute();
		return $stmt->fetch();
	}

	public function createSpotkania($liga, $druzynaA, $druzynaB, $data, $kolejka) {
		$stmt = $this->db->prepare('INSERT INTO spotkania (liga, druzyna_a, druzyna_b, data, kolejka) VALUES (:Liga, :DA, :DB, :Data, :Kolejka)');

		$stmt->bindValue(':Liga', $liga);
		$stmt->bindValue(':DA', $druzynaA);
		$stmt->bindValue(':DB', $druzynaB);
		$stmt->bindValue(':Data', $data);
		$stmt->bindValue(':Kolejka', $kolejka);

		return (bool)$stmt->execute();
	}

	public function updateSpotkanie($ID, $druzynaA, $druzynaB, $data, $bramkiA, $bramkiB, $liga, $kolejka) {
		$stmt = $this->db->prepare('UPDATE spotkania SET druzyna_a = :druzynaA, druzyna_b = :druzynaB, data = :data, bramkiA = :bramkiA, bramkiB = :bramkiB, liga = :liga, kolejka = :kolejka WHERE ID = :ID');

		$stmt->bindValue(':ID', $ID);
		$stmt->bindValue(':liga', $liga);
		$stmt->bindValue(':druzynaA', $druzynaA);
		$stmt->bindValue(':druzynaB', $druzynaB);
		$stmt->bindValue(':data', $data);
		$stmt->bindValue(':bramkiA', $bramkiA);
		$stmt->bindValue(':bramkiB', $bramkiB);
		$stmt->bindValue(':kolejka', $kolejka);

		return (bool)$stmt->execute();
	}

		public function removeSpotkania($ID) {
		$stmt = $this->db->prepare('DELETE from spotkania WHERE ID = :ID');
		$stmt->bindValue(':ID', $ID);
		return $stmt->execute();
	}
}
