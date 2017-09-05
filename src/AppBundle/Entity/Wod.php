<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 04/07/2017
 * Time: 13:20
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Movement;


/**
 * @ORM\Entity
 * @ORM\Table(name="wod_crossfit")
 */
class Wod
{
    const EMOM = 1,
        FORTIME = 2,
        TABATA = 3,
        AMRAP = 4;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @var \DateTime $day
     *
     * @ORM\Column(name="day", type="date", nullable=false)
     */
    private $date;

    /**
     * @var int $type
     *
     * @ORM\Column(name="type", type="integer", nullable=false)
     */
    private $type;

    /**
     * Many Wod have Many Movements.
     * @ManyToMany(targetEntity="Movement")
     * @JoinTable(name="wods_movements",
     *      joinColumns={@JoinColumn(name="wod_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="movement_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $movements;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movements = new ArrayCollection();
    }

    /**
     * Add movement
     * @param Movement $movement
     */
    public function addMovement(Movement $movement)
    {
        $this->movements[] = $movement;
    }

    /**
     * Remove Movement
     * @param Movement $movement
     */
    public function removeMovement(Movement $movement)
    {
        $this->movements->removeElement($movement);
    }

    /**
     * @return ArrayCollection
     */
    public function getMovements()
    {
        return $this->movements->getValues();
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
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
}
