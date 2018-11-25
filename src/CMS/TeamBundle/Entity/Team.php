<?php

namespace CMS\TeamBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Team
 */
class Team
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
    private $teamName;

    /**
     * @var string
     */
    private $teamAlias;

    /**
     * @var string
     */
    private $teamDescription;

    /**
     * @var string
     */
    private $teamImage;

    /**
     * @var boolean
     */
    private $showRanking;

    /**
     * @Assert\File(maxSize="6000000")
     * @var UploadedFile
     */
    private $file;

    /**
     * @var Collection
     */
    private $members;

    /**
     * @var Collection
     */
    private $news;

    /**
     * @var Category
     */
    private $category;

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
     * @return Team
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
     * @return Team
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
     * Set teamName
     *
     * @param string $teamName
     * @return Team
     */
    public function setTeamName($teamName)
    {
        $this->teamName = $teamName;

        return $this;
    }

    /**
     * Get teamName
     *
     * @return string 
     */
    public function getTeamName()
    {
        return $this->teamName;
    }

    /**
     * Set teamAlias
     *
     * @param string $teamAlias
     * @return Team
     */
    public function setTeamAlias($teamAlias)
    {
        $this->teamAlias = $teamAlias;

        return $this;
    }

    /**
     * Get teamAlias
     *
     * @return string 
     */
    public function getTeamAlias()
    {
        return $this->teamAlias;
    }

    /**
     *
     */
    public function generateTeamAlias()
    {
        $alias = strtolower($this->getTeamName());
        $alias = preg_replace('/[^A-Za-z0-9\- ]/','',$alias);
        $alias = str_replace(' ','-',$alias);
        $alias = str_replace('---','-',$alias);
        $alias = str_replace('--','-',$alias);

        $this->teamAlias = $alias;
    }

    /**
     * Set teamDescription
     *
     * @param string $teamDescription
     * @return Team
     */
    public function setTeamDescription($teamDescription)
    {
        $this->teamDescription = $teamDescription;

        return $this;
    }

    /**
     * Get teamDescription
     *
     * @return string 
     */
    public function getTeamDescription()
    {
        return $this->teamDescription;
    }

    /**
     * Set teamImage
     *
     * @param string $teamImage
     * @return Team
     */
    public function setTeamImage($teamImage)
    {
        $this->teamImage = $teamImage;

        return $this;
    }

    /**
     * Get teamImage
     *
     * @return string 
     */
    public function getTeamImage()
    {
        return $this->teamImage;
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
        $member->setTeam($this);
        $this->members[] = $member;

        return $this;
    }

    public function removeMember(TeamMember $member) {
        $this->members->removeElement($member);
    }

    /**
     * @return Collection
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @param Collection $news
     */
    public function setNews($news)
    {
        $this->news = $news;
    }

    /**
     * @param News $news
     * @return $this
     */
    public function addNews(News $news) {
        $news->setTeam($this);
        $this->news[] = $news;

        return $this;
    }

    /**
     * @param News $news
     * @return $this
     */
    public function removeNews(News $news) {
        $this->news->removeElement($news);

        return $this;
    }

    public function getAbsolutePath()
    {
        return null === $this->teamImage
            ? null
            : $this->getUploadRootDir().'/'.$this->teamImage;
    }

    public function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved

        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    public function getWebPath()
    {
        return null === $this->teamImage
            ? null
            : $this->getUploadDir().'/'.$this->teamImage;
    }

    public function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/team-images';
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
        if (isset($this->teamImage)) {
            // store the old name to delete after the update
            $this->temp = $this->teamImage;
            $this->teamImage = null;
        } else {
            $this->teamImage = 'initial';
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
            $this->teamImage = $filename.'.'.$this->getFile()->guessExtension();
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
        $this->getFile()->move($this->getUploadRootDir(), $this->teamImage);

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
     * Constructor
     */
    public function __construct()
    {
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @var array
     */
    private $classification;


    /**
     * Set classification
     *
     * @param array $classification
     * @return Team
     */
    public function setClassification($classification)
    {
        $this->classification = $classification;

        return $this;
    }

    /**
     * Get classification
     *
     * @return array 
     */
    public function getClassification()
    {
        return $this->classification;
    }
    /**
     * @var integer
     */
    private $ordering;


    /**
     * Set ordering
     *
     * @param integer $ordering
     * @return Team
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
     * @var string
     */
    private $teamCode;


    /**
     * Set teamCode
     *
     * @param string $teamCode
     * @return Team
     */
    public function setTeamCode($teamCode)
    {
        $this->teamCode = $teamCode;

        return $this;
    }

    /**
     * Get teamCode
     *
     * @return string 
     */
    public function getTeamCode()
    {
        return $this->teamCode;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return boolean
     */
    public function isShowRanking()
    {
        return $this->showRanking;
    }

    /**
     * @param boolean $showRanking
     */
    public function setShowRanking($showRanking)
    {
        $this->showRanking = $showRanking;
    }



}
