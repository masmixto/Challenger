<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Relationships
 *
 * @ORM\Table(name="relationships",uniqueConstraints={@UniqueConstraint(name="Relationships", columns={"user_one_id", "user_two_id"})}))
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RelationshipsRepository")
 */
class Relationships
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="user_one_id", type="integer")
     */
    private $userOneId;

    /**
     * @var int
     * @ORM\Column(name="user_two_id", type="integer")
     */
    private $userTwoId;

    /**
     * Determines the status of the relationship
     *
     * 0 - Pending
     * 1 - Accepted
     * 2 - Declined
     * 3 - Blocked
     *
     * By default the status is set to 0
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="action_user_id", type="integer")
     */
    private $actionUserId;


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
     * Set userOneId
     *
     * @param integer $userOneId
     *
     * @return Relationships
     */
    public function setUserOneId($userOneId)
    {
        $this->userOneId = $userOneId;

        return $this;
    }

    /**
     * Get userOneId
     *
     * @return int
     */
    public function getUserOneId()
    {
        return $this->userOneId;
    }

    /**
     * Set userTwoId
     *
     * @param integer $userTwoId
     *
     * @return Relationships
     */
    public function setUserTwoId($userTwoId)
    {
        $this->userTwoId = $userTwoId;

        return $this;
    }

    /**
     * Get userTwoId
     *
     * @return int
     */
    public function getUserTwoId()
    {
        return $this->userTwoId;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Relationships
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set actionUserId
     *
     * @param integer $actionUserId
     *
     * @return Relationships
     */
    public function setActionUserId($actionUserId)
    {
        $this->actionUserId = $actionUserId;

        return $this;
    }

    /**
     * Get actionUserId
     *
     * @return int
     */
    public function getActionUserId()
    {
        return $this->actionUserId;
    }
}

