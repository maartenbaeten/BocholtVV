<?php

namespace CMS\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
/**
 * User
 */
class User extends BaseUser
{
    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */

    protected $phoneNumber;

    protected $userRole;

    protected $contributes;

    protected $notes;

    protected $actions;

    public function __construct()
    {
        parent::__construct();
        $this->created = new \DateTime();
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    protected $customer;

    /**
     * @return string
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param string $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return User
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setfirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getfirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setlastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getlastName()
    {
        return $this->lastName;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param mixed $note
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }


    /**
     * @return mixed
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param mixed $actions
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
    }


}
