<?php

namespace CMS\TeamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Calendar
 */
class Calendar
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
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $score;

    /**
     * @var boolean
     */
    private $home;

    /**
     * @var string
     */
    private $challengerImage;

    /**
     * @var string
     */
    private $challengerName;

    /**
     * @Assert\File(maxSize="6000000")
     * @var UploadedFile
     */
    private $file;

    /**
     * @var \CMS\TeamBundle\Entity\Team
     */
    private $team;


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
     * @return Calendar
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
     * @return Calendar
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
     * Set date
     *
     * @param \DateTime $date
     * @return Calendar
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set score
     *
     * @param string $score
     * @return Calendar
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return string 
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set home
     *
     * @param boolean $home
     * @return Calendar
     */
    public function setHome($home)
    {
        $this->home = $home;

        return $this;
    }

    /**
     * Get home
     *
     * @return boolean 
     */
    public function getHome()
    {
        return $this->home;
    }

    /**
     * Set challengerImage
     *
     * @param string $challengerImage
     * @return Calendar
     */
    public function setChallengerImage($challengerImage)
    {
        $this->challengerImage = $challengerImage;

        return $this;
    }

    /**
     * Get challengerImage
     *
     * @return string 
     */
    public function getChallengerImage()
    {
        return $this->challengerImage;
    }

    /**
     * Set challengerName
     *
     * @param string $challengerName
     * @return Calendar
     */
    public function setChallengerName($challengerName)
    {
        $this->challengerName = $challengerName;

        return $this;
    }

    /**
     * Get challengerName
     *
     * @return string 
     */
    public function getChallengerName()
    {
        return $this->challengerName;
    }

    /**
     * Set team
     *
     * @param \CMS\TeamBundle\Entity\Team $team
     * @return Calendar
     */
    public function setTeam(\CMS\TeamBundle\Entity\Team $team = null)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get team
     *
     * @return \CMS\TeamBundle\Entity\Team 
     */
    public function getTeam()
    {
        return $this->team;
    }

    public function getAbsolutePath()
    {
        return null === $this->challengerImage
            ? null
            : $this->getUploadRootDir().'/'.$this->challengerImage;
    }

    public function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved

        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    public function getWebPath()
    {
        return null === $this->challengerImage
            ? null
            : $this->getUploadDir().'/'.$this->challengerImage;
    }

    public function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/challenger-images';
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
        if (isset($this->challengerImage)) {
            // store the old name to delete after the update
            $this->temp = $this->challengerImage;
            $this->challengerImage = null;
        } else {
            $this->challengerImage = 'initial';
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
            $this->challengerImage = $filename.'.'.$this->getFile()->guessExtension();
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
        $this->getFile()->move($this->getUploadRootDir(), $this->challengerImage);

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
    
}
