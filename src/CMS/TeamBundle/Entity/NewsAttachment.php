<?php

namespace CMS\TeamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * NewsAttachment
 */
class NewsAttachment
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var string
     */
    private $originalName;

    // mime types
    // https://www.sitepoint.com/web-foundations/mime-types-summary-list/
// @Assert\NotBlank
    /**
     * @Assert\File(
     *      maxSize="6000000",
     *      mimeTypes = {
     *          "application/pdf",
     *          "application/x-pdf",
     *          "application/msword",
     *          "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
     *      },
     *     mimeTypesMessage = "Only pdf, doc and docx types are allowed"
     * )
     */
    private $file;

    /**
     * @var News
     */
    private $news;


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
     * Set filePath
     *
     * @param string $filePath
     * @return NewsAttachment
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get filePath
     *
     * @return string 
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Set originalName
     *
     * @param string $originalName
     * @return NewsAttachment
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * Get originalName
     *
     * @return string 
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }


    /**
     * @return News
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @param News $news
     */
    public function setNews($news)
    {
        $this->news = $news;
    }

    public function getAbsolutePath()
    {
        return null === $this->filePath
            ? null
            : $this->getUploadRootDir().'/'.$this->filePath;
    }

    public function getWebPath()
    {
        return null === $this->filePath
            ? null
            : $this->getUploadDir().'/'.$this->filePath;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/news-attachments';
    }

    private $temp;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        $oldName = $file->getClientOriginalName();
        $this->setOriginalName($oldName);
        // check if we have an old image path
        if (isset($this->filePath)) {
            // store the old name to delete after the update
            $this->temp = $this->filePath;
            $this->filePath = null;

        } else {
            $this->filePath = 'initial';
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


    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->filePath = $filename.'.'.$this->getFile()->guessExtension();
        }
    }


    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->filePath);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }


    public function removeUpload()
    {
        $file = $this->getAbsolutePath();
        if ($file) {
            unlink($file);
        }
    }

}
