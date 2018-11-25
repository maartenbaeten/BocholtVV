<?php

namespace CMS\TeamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TeamMember
 */
class TeamMember
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var string
     */
    private $creator;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $playerAlias;

    /**
     * @var string
     */
    private $street;

    /**
     * @var integer
     */
    private $number;

    /**
     * @var string
     */
    private $zip;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $playerImage;

    /**
     * @var string
     */
    private $email;

    /**
     * @var boolean
     */
    private $membershipPaid;

    /**
     * @var Team
     */
    private $team;

    /**
     * @var Role
     */
    private $role;

    /**
     * @Assert\File(maxSize="6000000")
     * @var UploadedFile
     */
    private $file;

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
     * Set created
     *
     * @param \DateTime $created
     * @return TeamMember
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
     * Set creator
     *
     * @param string $creator
     * @return TeamMember
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return string 
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return TeamMember
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return TeamMember
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set playerAlias
     *
     * @param string $playerAlias
     * @return TeamMember
     */
    public function setPlayerAlias($playerAlias)
    {
        $this->playerAlias = $playerAlias;

        return $this;
    }

    /**
     * Get playerAlias
     *
     * @return string 
     */
    public function getPlayerAlias()
    {
        return $this->playerAlias;
    }

    public function generatePlayerAlias() {
        $alias = strtolower($this->getFirstname().' '.$this->getLastname() . ' ' . $this->getId());
        $alias = preg_replace('/[^A-Za-z0-9\- ]/','',$alias);
        $alias = str_replace(' ','-',$alias);
        $alias = str_replace('---','-',$alias);
        $alias = str_replace('--','-',$alias);

        $this->playerAlias = $alias;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return TeamMember
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set number
     *
     * @param integer $number
     * @return TeamMember
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set zip
     *
     * @param string $zip
     * @return TeamMember
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string 
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return TeamMember
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set playerImage
     *
     * @param string $playerImage
     * @return TeamMember
     */
    public function setPlayerImage($playerImage)
    {
        $this->playerImage = $playerImage;

        return $this;
    }

    /**
     * Get playerImage
     *
     * @return string 
     */
    public function getPlayerImage()
    {
        return $this->playerImage;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return TeamMember
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param Team $team
     */
    public function setTeam($team)
    {
        $this->team = $team;
    }

    /**
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param Role $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }


    public function getAbsolutePath()
    {
        return null === $this->playerImage
            ? null
            : $this->getUploadRootDir().'/'.$this->playerImage;
    }

    public function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved

        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    public function getWebPath()
    {
        return null === $this->playerImage
            ? null
            : $this->getUploadDir().'/'.$this->playerImage;
    }

    public function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/player-images';
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->playerImage)) {
            // store the old name to delete after the update
            $this->temp = $this->playerImage;
            $this->playerImage = null;
        } else {
            $this->playerImage = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

//    /**
//     * @ORM\PrePersist()
//     * @ORM\PreUpdate()
//     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->playerImage = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

//    /**
//     * @ORM\PostPersist()
//     * @ORM\PostUpdate()
//     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->playerImage);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

//    /**
//     * @ORM\PostRemove()
//     */
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();
        if ($file) {
            unlink($file);
        }
    }


    /**
     * @var integer
     */
    private $teamNumber;

    /**
     * @var string
     */
    private $nationality;


    /**
     * Set teamNumber
     *
     * @param integer $teamNumber
     * @return TeamMember
     */
    public function setTeamNumber($teamNumber)
    {
        $this->teamNumber = $teamNumber;

        return $this;
    }

    /**
     * Get teamNumber
     *
     * @return integer 
     */
    public function getTeamNumber()
    {
        return $this->teamNumber;
    }

    /**
     * Set nationality
     *
     * @param string $nationality
     * @return TeamMember
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * Get nationality
     *
     * @return string 
     */
    public function getNationality()
    {
        return $this->nationality;
    }
    /**
     * @var \DateTime
     */
    private $birthDate;


    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     * @return TeamMember
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime 
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }
    /**
     * @var integer
     */
    private $ordering;


    /**
     * Set ordering
     *
     * @param integer $ordering
     * @return TeamMember
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * Get ordering
     *
     * @return integer 
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * @return boolean
     */
    public function isMembershipPaid()
    {
        return $this->membershipPaid;
    }

    /**
     * @param boolean $membershipPaid
     */
    public function setMembershipPaid($membershipPaid)
    {
        $this->membershipPaid = $membershipPaid;
    }

}
