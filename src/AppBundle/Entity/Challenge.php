<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Challenge
 *
 * @ORM\Table(name="challenge")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChallengeRepository")
 */
class Challenge
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="Exercise", mappedBy="exerciseId")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="exercise", type="string", length=255)
     */
    private $exercise;

    /**
     * @var string
     *
     * @ORM\Column(name="time", type="date")
     */
    private $time;


    /**
     * @ORM\Column(type="integer")
     */
    private $done;



    /**
     * @var string
     * @ORM\Column(name="userid", type="string", length=255)
     * @ORM\ManyToOne(targetEntity="user", inversedBy="id")
     */
    private $userid;


    public function __construct(){
        $this->done = 0;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Challenge
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param string $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * Set exercise
     *
     * @param string $exercise
     *
     * @return Challenge
     */
    public function setExercise($exercise)
    {
        $this->exercise = $exercise;

        return $this;
    }

    /**
     * Get exercise
     *
     * @return string
     */
    public function getExercise()
    {
        return $this->exercise;
    }

    /**
     * Set userid
     *
     * @param string $userid
     *
     * @return Challenge
     */
    public function setUserId($userid)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->userid;
    }

    /**
     * @return mixed
     */
    public function getDone()
    {
        return $this->done;
    }

    /**
     * @param mixed $done
     */
    public function setDone($done)
    {
        $this->done = $done;
    }
}
