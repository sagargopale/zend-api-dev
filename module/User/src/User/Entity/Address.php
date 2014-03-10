<?php

namespace Address\Entity;
use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;

/** 
 *  @SWG\Model(id="address",
 *  description="Address details of a user") 
 *  @ORM\Entity
 *  */
class Address implements \JsonSerializable {
	/**
	 * @SWG\Property(name="id",type="integer",description="Unique identifier for address")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	private $id;
	
	/** @ORM\Column(type="string") */
	private $streetAddress;
	
	/** @ORM\Column(type="string") */
	private $city;
	
	/** @ORM\Column(type="string") */
	private $state;
	
	/** @ORM\Column(type="string") */
	private $country;
	
	/** @ORM\Column(type="string") */
	private $postalCode;
	
	/**
	 *  @ORM\OneToMany(targetEntity="User", inversedBy="addressList")
	 * */
	private $user;
		
	public function getId() {
		return $this->id;
	}
	
	public function setId($value) {
		$this->id = $value;
	}
    
	public function getStreetAddress() {
		return $this->streetAddress;
	}
	
	public function setStreetAddress($value) {
		$this->streetAddress = $value;
	}
	
	public function getCity() {
		return $this->city;
	}
	
	public function setCity($value) {
		$this->city = $value;
	}
	
	public function getState() {
		return $this->state;
	}
	
	public function setState($value) {
		$this->state = $value;
	}
    
    public function getCountry() {
		return $this->country;
	}
	
	public function setCountry($value) {
		$this->country = $value;
	}
	
	public function getPostalCode() {
		return $this->postalCode;
	}
	
	public function setPostalCode($value) {
		$this->postalCode = $value;
	}
		
	public function jsonSerialize() {
		return [
		'user' => $this->user,
		'id' => $this->id,
		'streetAddress' => $this->getStreetAddress(),
		'city' => $this->getCity(),
		'state' => $this->getState(),
		'country' => $this->getCountry(),
		'postalCode'=>$this->getPostalCode()
		];
	}
	
	public function setAddress($data) {
	   $this->setStreetAddress($data['streetAddress']);
	   $this->setCity($data['city']);
	   $this->setState($data['state']);
	   $this->setCountry($data['country']);
	   $this->setPostalCode($data['postalCode']);
	}
	
}