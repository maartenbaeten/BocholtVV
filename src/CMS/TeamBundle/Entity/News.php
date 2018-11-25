<?php

namespace CMS\TeamBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * News
 */
class News
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $newsImage;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var Team
     */
    private $team;


    /**
     * @var Collection
     */
    private $attachments;

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
     * Set title
     *
     * @param string $title
     * @return News
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return News
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    public function getShortContent()
    {
        $shortContent = substr($this->content,0, 500);
        if(strlen($shortContent) == 500){
            $shortContent.='...';
        }
        return $shortContent;
    }

    /**
     * Set newsImage
     *
     * @param string $newsImage
     * @return News
     */
    public function setNewsImage($newsImage)
    {
        $this->newsImage = $newsImage;

        return $this;
    }

    /**
     * Get newsImage
     *
     * @return string 
     */
    public function getNewsImage()
    {
        return $this->newsImage;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
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

    public function getAbsolutePath()
    {
        return null === $this->newsImage
            ? null
            : $this->getUploadRootDir().'/'.$this->newsImage;
    }

    public function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved

        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    public function getWebPath()
    {
        return null === $this->newsImage
            ? null
            : $this->getUploadDir().'/'.$this->newsImage;
    }

    public function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/news-images';
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
        if (isset($this->newsImage)) {
            // store the old name to delete after the update
            $this->temp = $this->newsImage;
            $this->newsImage = null;
        } else {
            $this->newsImage = 'initial';
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
            $this->newsImage = $filename.'.'.$this->getFile()->guessExtension();
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
        $this->getFile()->move($this->getUploadRootDir(), $this->newsImage);

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
        if ($file && $file != 'initial') {

       unlink($file);
        }
    }

    /**
     * @return Collection
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param Collection $attachments
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
    }


    /**
     * @param NewsAttachment $attachment
     * @return $this
     */
    public function addAttachment(NewsAttachment $attachment) {
        $attachment->setNews($this);
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * @param NewsAttachment $attachment
     * @return $this
     */
    public function removeAttachment(NewsAttachment $attachment) {
        $this->attachments->removeElement($attachment);

        return $this;
    }

}
