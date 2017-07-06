<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $hoursByWeek;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $phone;

    /**
     * Many Users have Many Sessions.
     * @ManyToMany(targetEntity="Session", inversedBy="users")
     * @JoinTable(name="users_sessions")
     */
    private $sessions;

    public function __construct()
    {
        parent::__construct();
        $this->sessions = new ArrayCollection();
        $this->hoursByWeek = 3;
    }

    public function addSession(Session $session)
    {
        $this->sessions[] = $session;
        $this->hoursByWeek--;
    }

    public function removeSession(Session $session)
    {
        $this->sessions->removeElement($session);
        $this->hoursByWeek++;
    }

    public function isFullSubscription()
    {
        return ($this->getHoursByWeek() === 0);
    }

    /**
     * @return mixed
     */
    public function getHoursByWeek()
    {
        return $this->hoursByWeek;
    }

    /**
     * @param mixed $hoursByWeek
     */
    public function setHoursByWeek($hoursByWeek)
    {
        $this->hoursByWeek = $hoursByWeek;
    }

    /**
     * @return ArrayCollection
     */
    public function getSessions()
    {
        return $this->sessions->getValues();
    }



    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }




}
