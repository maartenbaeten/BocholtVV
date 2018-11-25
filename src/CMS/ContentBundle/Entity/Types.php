<?php

namespace CMS\ContentBundle\Entity;

use CMS\ContentBundle\Entity\Content;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="CMS_content_types")
 * @ORM\Entity()
 */
class Types
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
	/**
     * @ORM\Column(name="typeName", type="text")
     */
    private $typeName;
	
	/**
     * @ORM\Column(name="viewLink", type="text")
     */
    private $viewLink;
	
	/**
     * @ORM\Column(name="imagePath", type="text")
     */
    private $imagePath;

    /**
     * @ORM\Column(name="classification", type="integer")
     */
    private $classification;

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
     * Set typeName
     *
     * @param string $typeName
     * @return Types
     */
    public function setTypeName($typeName)
    {
        $this->typeName = $typeName;
    
        return $this;
    }

    /**
     * Get typeName
     *
     * @return string 
     */
    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * Set viewLink
     *
     * @param string $viewLink
     * @return Types
     */
    public function setViewLink($viewLink)
    {
        $this->viewLink = $viewLink;
    
        return $this;
    }

    /**
     * Get viewLink
     *
     * @return string 
     */
    public function getViewLink()
    {
        return $this->viewLink;
    }

    /**
     * Set imagePath
     *
     * @param string $imagePath
     * @return Types
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;
    
        return $this;
    }

    /**
     * Get imagePath
     *
     * @return string 
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * Set classification
     *
     * @param integer $classification
     * @return Types
     */
    public function setClassification($classification)
    {
        $this->classification = $classification;

        return $this;
    }

    /**
     * Get classification
     *
     * @return integer 
     */
    public function getClassification()
    {
        return $this->classification;
    }
}
