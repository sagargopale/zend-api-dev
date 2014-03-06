<?php

namespace User\Entity;
use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;

/** 
 *  @SWG\Model(id="user",
 *  description="Defines a user") 
 *  @ORM\Entity
 *  */
class User implements \JsonSerializable {
	/**
	 * @SWG\Property(name="id",type="integer",description="Unique identifier for the User")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/** 
	 * @ORM\Column(type="string") 
	 * */
	private $firstName;

	/** @ORM\Column(type="string") */
	private $lastName;

	/** @ORM\Column(type="string") */
	private $email;

	/** @ORM\Column(type="string") */
	private $password;
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($value) {
		$this->id = $value;
	}

	public function getFirstName() {
		return $this->firstName;
	}
	
	public function setFirstName($value) {
		$this->firstName = $value;
	}
	
	public function getLastName() {
		return $this->lastName;
	}
	
	public function setLastName($value) {
		$this->lastName = $value;
	}
	
	public function getEmail() {
		return $this->email;
	}
	
	public function setEmail($value) {
		$this->email = $value;
	}
	
	public function getPassword() {
		return $this->password;
	}
	
	public function setPassword($value) {
		$this->password = md5($value);
	}
	
	public function jsonSerialize() {
		return [
		'id' => $this->id,
		'firstName' => $this->getFirstName(),
		'lastName' => $this->getLastName(),
		'email' => $this->getEmail(),
		'password' => $this->getPassword(),
		];
	}
	
	public function setData($data) {
	   $this->setFirstName($data['firstName']);
	   $this->setLastName($data['lastName']);
	   $this->setEmail($data['email']);
	   $this->setPassword($data['password']);
	}
	
}