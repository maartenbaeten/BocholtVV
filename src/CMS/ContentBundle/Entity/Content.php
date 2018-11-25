<?php
// src/CMS/Bundle/ContentBundle/Entity/Content.php
namespace CMS\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CMS\ContentBundle\Entity\Categories;
use CMS\ContentBundle\Entity\Types;
use CMS\ContentBundle\Entity\ContentKey;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="CMS_content")
 * @ORM\Entity(repositoryClass="CMS\ContentBundle\Entity\ContentRepository")
 */
class Content
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
	/**
     * @ORM\ManyToOne(targetEntity="ContentKey", inversedBy="contentItems")
     * @ORM\JoinColumn(name="content_key", referencedColumnName="id")
     **/
    private $contentKey;

    /**
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(name="author", type="string", length=200)
     */
    private $author;

    /**
     * @ORM\Column(name="contentTitle", type="string", nullable=true)
     */
    private $contentTitle;

    /**
     * @ORM\Column(name="content", type="text", length=500)
     */
    private $content;

    /**
     * @ORM\Column(name="published", type="integer", options={"default" = 1})
     */
    private $published;
	
	/**
     * @ORM\Column(name="contentLink", type="text", length=100, nullable=true)
     */
    private $contentLink;
	
	/**
     * @ORM\Column(name="contentImage", type="text", length=100, nullable=true)
     */
    private $contentImage;
	
	/**
     * @ORM\Column(name="language", type="text", length=10)
     */
    private $language;

	 /**
     * @ORM\ManyToMany(targetEntity="Categories", inversedBy="contentItems")
	 * @ORM\JoinTable(name="content_categories")
	 * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     **/
    private $categories;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set $contentTitle
     *
     * @param string $contentTitle
     * @return Content
     */
    public function setContentTitle($contentTitle)
    {
        $this->contentTitle = $contentTitle;
        $alias = strtolower($contentTitle);
        $alias = preg_replace('/[^A-Za-z0-9\- ]/','',$alias);
        $alias = str_replace(' ','-',$alias);
        $alias = str_replace('---','-',$alias);
        $alias = str_replace('--','-',$alias);
        $this->contentAlias = $alias.'-'.$this->getId();

        return $this;
    }

    /**
     * Get $contentTitle
     *
     * @return string 
     */
    public function getContentTitle()
    {
        return $this->contentTitle;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Content
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
        $shortContent = substr($this->content,0, 70);
        if(strlen($shortContent) == 70){
            $shortContent.='...';
        }
        return $shortContent;
    }

    /**
     * Set contentLink
     *
     * @param string $contentLink
     * @return Content
     */
    public function setContentLink($contentLink)
    {
        $this->contentLink = $contentLink;
    
        return $this;
    }

    /**
     * Get contentLink
     *
     * @return string 
     */
    public function getContentLink()
    {
        return $this->contentLink;
    }

    /**
     * Set contentimage
     *
     * @param string $contentImage
     * @return Content
     */
    public function setContentimage($contentImage)
    {
        $this->contentImage = $contentImage;
    
        return $this;
    }

    /**
     * Get contentImage
     *
     * @return string 
     */
    public function getContentImage()
    {
        return $this->contentImage;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return Content
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    
        return $this;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set contentKey
     *
     * @param \CMS\ContentBundle\Entity\ContentKey $contentKey
     * @return Content
     */
    public function setContentKey(\CMS\ContentBundle\Entity\ContentKey $contentKey = null)
    {
        $this->contentKey = $contentKey;
    
        return $this;
    }

    /**
     * Get contentKey
     *
     * @return \CMS\ContentBundle\Entity\ContentKey
     */
    public function getContentKey()
    {
        return $this->contentKey;
    }

    /**
     * Add categories
     *
     * @param \CMS\ContentBundle\Entity\Categories $categories
     * @return Content
     */
    public function addCategories(\CMS\ContentBundle\Entity\Categories $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param \CMS\ContentBundle\Entity\Categories $categories
     */
    public function removeCategories(\CMS\ContentBundle\Entity\Categories $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set published
     *
     * @param integer $published
     * @return Content
     */
    public function setPublished($published)
    {
        $this->published = $published;
    
        return $this;
    }

    /**
     * Get published
     *
     * @return integer 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Content
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
     * Set author
     *
     * @param string $author
     * @return Content
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Add categories
     *
     * @param \CMS\ContentBundle\Entity\Categories $categories
     * @return Content
     */
    public function addCategory(\CMS\ContentBundle\Entity\Categories $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \CMS\ContentBundle\Entity\Categories $categories
     */
    public function removeCategory(\CMS\ContentBundle\Entity\Categories $categories)
    {
        $this->categories->removeElement($categories);
    }
    /**
     * @var string
     */
    private $contentAlias;


    /**
     * Set contentAlias
     *
     * @param string $contentAlias
     * @return Content
     */
    public function setContentAlias($contentAlias)
    {
        $this->contentAlias = $contentAlias;

        return $this;
    }

    /**
     * Get contentAlias
     *
     * @return string 
     */
    public function getContentAlias()
    {
        return $this->contentAlias;
    }
}
