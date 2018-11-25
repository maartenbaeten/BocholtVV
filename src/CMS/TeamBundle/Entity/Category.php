<?php

namespace CMS\TeamBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Category
 */
class Category
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Collection
     */
    private $teams;


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
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Collection
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @param Collection $teams
     */
    public function setTeams($teams)
    {
        $this->teams = $teams;
    }

    /**
     * @param Team $team
     * @return $this
     */
    public function addTeam(Team $team) {
        $team->setCategory($this);
        $this->teams[] = $team;

        return $this;
    }

    /**
     * @param Team $team
     */
    public function removeTeam(Team $team) {
        $this->teams->removeElement($team);
    }

}
