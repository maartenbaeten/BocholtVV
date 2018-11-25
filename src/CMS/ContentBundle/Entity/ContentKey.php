<?php
// src/CMS/Bundle/ContentBundle/Entity/Content.php
namespace CMS\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CMS\ContentBundle\Entity\Categories;
use CMS\ContentBundle\Entity\Types;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="CMS_content_key")
 * @ORM\Entity(repositoryClass="CMS\ContentBundle\Entity\ContentRepository")
 */
class ContentKey
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
	/**
     * @ORM\Column(name="creationDate", type="date")
     */
    private $creationDate;
	
	/**
     * @ORM\OneToMany(targetEntity="ContentPosition", mappedBy="contentKey")
     */
    private $positions;
	
	/**
     * @ORM\OneToMany(targetEntity="Content", mappedBy="contentKey")
     **/
    private $contentItems;
    
	/**
     * @ORM\ManyToOne(targetEntity="Types")
     * @ORM\JoinColumn(name="content_type_id", referencedColumnName="id")
     **/
    private $contentType;

    /**
     * @ORM\ManyToMany(targetEntity="Tags", inversedBy="contentItems")
     * @ORM\JoinTable(name="content_tags")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     **/
    private $tags;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->positions = new ArrayCollection();
        $this->contentItems = new ArrayCollection();
    }
    
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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return ContentKey
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    
        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Add positions
     *
     * @param \CMS\ContentBundle\Entity\ContentPosition $positions
     * @return ContentKey
     */
    public function addPosition(ContentPosition $positions)
    {
        $this->positions[] = $positions;
    
        return $this;
    }

    /**
     * Remove positions
     *
     * @param \CMS\ContentBundle\Entity\ContentPosition $positions
     */
    public function removePosition(ContentPosition $positions)
    {
        $this->positions->removeElement($positions);
    }

    /**
     * Get positions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPositions()
    {
        return $this->positions;
    }

    /**
     * Add contentItems
     *
     * @param \CMS\ContentBundle\Entity\Content $contentItems
     * @return ContentKey
     */
    public function addContentItem(Content $contentItems)
    {
        $this->contentItems[] = $contentItems;
    
        return $this;
    }

    /**
     * Remove contentItems
     *
     * @param \CMS\ContentBundle\Entity\Content $contentItems
     */
    public function removeContentItem(Content $contentItems)
    {
        $this->contentItems->removeElement($contentItems);
    }

    /**
     * Get contentItems
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContentItems()
    {
        return $this->contentItems;
    }

    /**
     * Set contentType
     *
     * @param \CMS\ContentBundle\Entity\Types $contentType
     * @return ContentKey
     */
    public function setContentType(Types $contentType = null)
    {
        $this->contentType = $contentType;
    
        return $this;
    }

    /**
     * Get contentType
     *
     * @return \CMS\ContentBundle\Entity\Types
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Add tags
     *
     * @param \CMS\ContentBundle\Entity\Tags $tags
     * @return ContentKey
     */
    public function addTag(Tags $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \CMS\ContentBundle\Entity\Tags $tags
     */
    public function removeTag(Tags $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    public function getContentAlias($locale){
        foreach($this->getContentItems() as $translation){
            if($translation->getLanguage() == $locale){
                return $translation->getContentAlias();
            }
        }
        return $this->getContentItems()->get(0)->getContentAlias();
    }
}
