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
     * @var string $score
     *
     * @ORM\Column(name="score", type="string", nullable=false)
     */
    private $score;

    /**
     * @var string $comment
     *
     * @ORM\Column(name="comment", type="string", nullable=false)
     */
    private $comment;

    /**
     * @var int $type
     *
     * @ORM\Column(name="type", type="integer", nullable=false)
     */
    private $type;

    /**
     * Many Wod have Many Movements.
     * @ManyToMany(targetEntity="Movement", cascade={"persist"})
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
     * @return string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }


    /**
     * Add movement
     * @param Movement $movement
     */
    public function addMovement(Movement $movement)
    {
        $this->movements->add($movement);
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
    public function getType(): ?int
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
     * @return string
     */
    public function getScore(): ?string
    {
        return $this->score;
    }

    /**
     * @param string $score
     */
    public function setScore(string $score)
    {
        $this->score = $score;
    }



    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function intToType(): string
    {
        switch ($this->type) {
            default:
            case Wod::EMOM:
                $string = 'app.emom';
            break;
            case Wod::FORTIME:
                $string = 'app.for_time';
            break;
            case Wod::TABATA:
                $string = 'app.tabata';
            break;
            case Wod::AMRAP:
                $string = 'app.amrap';
            break;
        }

        return $string;
    }
}
