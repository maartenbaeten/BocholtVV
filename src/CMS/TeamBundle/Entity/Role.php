<?php

namespace CMS\TeamBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 */
class Role
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $roleName;

    /**
     * @var integer
     */
    private $number;

    /**
     * @var boolean
     */
    private $staff;

    /**
     * @var Collection
     */
    private $members;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set roleName
     *
     * @param string $roleName
     * @return Role
     */
    public function setRoleName($roleName)
    {
        $this->roleName = $roleName;

        return $this;
    }

    /**
     * Get roleName
     *
     * @return string 
     */
    public function getRoleName()
    {
        return $this->roleName;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return boolean
     */
    public function isStaff()
    {
        return $this->staff;
    }

    /**
     * @param boolean $staff
     */
    public function setStaff($staff)
    {
        $this->staff = $staff;
    }

    /**
     * @return Collection
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param Collection $members
     */
    public function setMembers($members)
    {
        $this->members = $members;
    }

    /**
     * @param TeamMember $member
     * @return $this
     */
    public function addMember(TeamMember $member) {
        $member->setRole($this);
        $this->members[] = $member;

        return $this;
    }

    public function removeMember(TeamMember $member) {
        $this->members->removeElement($member);
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get staff
     *
     * @return boolean 
     */
    public function getStaff()
    {
        return $this->staff;
    }
    /**
     * @var string
     */
    private $singularRoleName;


    /**
     * Set singularRoleName
     *
     * @param string $singularRoleName
     * @return Role
     */
    public function setSingularRoleName($singularRoleName)
    {
        $this->singularRoleName = $singularRoleName;

        return $this;
    }

    /**
     * Get singularRoleName
     *
     * @return string 
     */
    public function getSingularRoleName()
    {
        return $this->singularRoleName;
    }
}
