<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use AppBundle\Entity\Wod;
use AppBundle\Entity\Strength;

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
     * Many User have Many WODs.
     * @ManyToMany(targetEntity="Wod")
     * @JoinTable(name="users_wods",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="wod_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $wods;

    /**
     * Many User have Many Stengths.
     * @ManyToMany(targetEntity="Strength")
     * @JoinTable(name="users_strengths",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="strength_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $strengths;

    /**
     * Many Users have Many Sessions.
     * @ManyToMany(targetEntity="Session", inversedBy="users")
     * @JoinTable(name="users_sessions")
     */
    private $sessions;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->sessions = new ArrayCollection();
        $this->wods = new ArrayCollection();
        $this->strengths = new ArrayCollection();
        $this->hoursByWeek = 3;
        $this->firstName = '';
        $this->lastName = '';
        $this->phone = '';
    }

    /**
     * Add wod
     * @param Wod $wod
     */
    public function addWod(Wod $wod)
    {
        $this->wods[] = $wod;
    }

    /**
     * Remove Wod
     * @param Wod $wod
     */
    public function removeWod(Wod $wod)
    {
        $this->wods->removeElement($wod);
    }

    /**
     * @return ArrayCollection
     */
    public function getWods()
    {
        return $this->wods->getValues();
    }

    /**
     * Add strengths
     * @param Strength $strength
     */
    public function addStrength(Strength $strength)
    {
        $this->strengths[] = $strength;
    }

    /**
     * Remove Strength
     * @param Strength $wod
     */
    public function removeStrength(Strength $strength)
    {
        $this->strengths->removeElement($strength);
    }

    /**
     * @return ArrayCollection
     */
    public function getStrengths()
    {
        return $this->strengths->getValues();
    }

    /**
     * Add session
     * @param Session $session
     */
    public function addSession(Session $session)
    {
        $this->sessions[] = $session;
        $this->hoursByWeek--;
    }

    /**
     * Remove session
     * @param Session $session
     */
    public function removeSession(Session $session)
    {
        $this->sessions->removeElement($session);
        $this->hoursByWeek++;
    }

    /**
     * @return bool
     */
    public function isFullSubscription(): bool
    {
        return ($this->getHoursByWeek() === 0);
    }

    /**
     * @return int
     */
    public function getHoursByWeek(): int
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
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone)
    {
        $this->phone = $phone;
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
