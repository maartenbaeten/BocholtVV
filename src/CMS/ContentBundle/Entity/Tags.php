<?php
// src/CMS/Bundle/ContentBundle/Entity/Categories.php
namespace CMS\ContentBundle\Entity;

use CMS\ContentBundle\Entity\Content;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="CMS_content_tags")
 * @ORM\Entity(repositoryClass="CMS\ContentBundle\Entity\TagsRepository")
 */
class Tags
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
	/**
     * @ORM\Column(name="tagName", type="text")
     */
    private $tagName;

	/**
     * @ORM\ManyToMany(targetEntity="ContentKey", mappedBy="tags")
	 * @ORM\JoinColumn(name="contentKey_id", referencedColumnName="id")
     **/
	 private $contentItems;
    /**
     * Constructor
     */
    public function __construct()
    {
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
     * Set tagName
     *
     * @param string $tagName
     * @return Tags
     */
    public function setTagName($tagName)
    {
        $this->tagName = $tagName;

        return $this;
    }

    /**
     * Get tagName
     *
     * @return string 
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * Add contentItems
     *
     * @param \CMS\ContentBundle\Entity\Content $contentItems
     * @return Tags
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
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getTagName();
    }
}
