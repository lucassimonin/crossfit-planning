<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 04/07/2017
 * Time: 13:20
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SessionRepository")
 * @ORM\Table(name="session_crossfit")
 */
class Session
{
    const MONDAY = 1,
        TUESDAY = 2,
        WEDNESDAY = 3,
        THURSDAY = 4,
        FRIDAY = 5,
        SATURDAY = 6,
        SUNDAY = 0,
        USER_BY_SESSION = 8;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @var int $day
     *
     * @ORM\Column(name="day", type="integer", nullable=false)
     */
    private $day;
    /**
     * @var int $startTime
     *
     * @ORM\Column(name="start_time", type="integer", nullable=false)
     */
    private $startTime;
    /**
     * @var int $endTime
     *
     * @ORM\Column(name="end_time", type="integer", nullable=false)
     */
    private $endTime;

    /**
     * Many Sessions have Many Users.
     * @ManyToMany(targetEntity="User", mappedBy="sessions")
     */
    private $users;

    public function addUser(User $user)
    {
        $user->addSession($this);
        $this->users[] = $user;
    }

    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
        $user->removeSession($this);
    }

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->day = self::MONDAY;
        $this->endTime = 0;
        $this->startTime = 0;
    }

    /**
     * @return int
     */
    public function getDay(): int
    {
        return $this->day;
    }

    /**
     * @param int $day
     */
    public function setDay(int $day)
    {
        $this->day = $day;
    }

    /**
     * @return int
     */
    public function getStartTime(): int
    {
        return $this->startTime;
    }

    /**
     * @param int $startTime
     */
    public function setStartTime(int $startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * @return int
     */
    public function getEndTime(): int
    {
        return $this->endTime;
    }



    /**
     * @param int $endTime
     */
    public function setEndTime(int $endTime)
    {
        $this->endTime = $endTime;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users->getValues();
    }

    /**
     * @param mixed $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }



    public function intToDay(): string
    {
        $string = '';
        switch ($this->day) {
            default:
            case Session::MONDAY:
                $string = 'app.monday';
                break;
            case Session::TUESDAY:
                $string = 'app.tuesday';
                break;
            case Session::WEDNESDAY:
                $string = 'app.wednesday';
                break;
            case Session::THURSDAY:
                $string = 'app.thursday';
                break;
            case Session::FRIDAY:
                $string = 'app.friday';
                break;
            case Session::SATURDAY:
                $string = 'app.saturday';
                break;
            case Session::SUNDAY:
                $string = 'app.sunday';
                break;
        }
        return $string;
    }
}
