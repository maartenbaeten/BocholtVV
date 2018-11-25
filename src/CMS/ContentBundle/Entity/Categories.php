<?php
// src/CMS/Bundle/ContentBundle/Entity/Categories.php
namespace CMS\ContentBundle\Entity;

use CMS\ContentBundle\Entity\Content;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="CMS_content_categories")
 * @ORM\Entity(repositoryClass="CMS\ContentBundle\Entity\CategoriesRepository")
 */
class Categories
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var
     */
    private $categoryName;

    /**
     * @ORM\Column(name="description", type="text", length=500, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Categories", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    private $parentCategory;
	
	/**
     * @ORM\OneToMany(targetEntity="Categories", mappedBy="parentCategory")
     */
    private $children;
	
	/**
     * @ORM\ManyToMany(targetEntity="Content", mappedBy="categories")
	 * @ORM\JoinColumn(name="content_id", referencedColumnName="id")
     **/
	 private $contentItems;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
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
     * @param $categoryName
     * @return $this
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;
    
        return $this;
    }

    /**
     * Get categoryname
     *
     * @return string 
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Categories
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param Categories|null $parentCategory
     * @return $this
     */
    public function setParentCategory(Categories $parentCategory = null)
    {
        $this->parentCategory = $parentCategory;
    
        return $this;
    }

    /**
     * Get parentcategory
     *
     * @return \CMS\ContentBundle\Entity\Categories
     */
    public function getParentCategory()
    {
        return $this->parentCategory;
    }

    /**
     * Add children
     *
     * @param \CMS\ContentBundle\Entity\Categories $children
     * @return Categories
     */
    public function addChildren(Categories $children)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param \CMS\ContentBundle\Entity\Categories $children
     */
    public function removeChildren(Categories $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add contentItems
     *
     * @param \CMS\ContentBundle\Entity\Content $contentItems
     * @return Categories
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
     * Add children
     *
     * @param \CMS\ContentBundle\Entity\Categories $children
     * @return Categories
     */
    public function addChild(\CMS\ContentBundle\Entity\Categories $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \CMS\ContentBundle\Entity\Categories $children
     */
    public function removeChild(\CMS\ContentBundle\Entity\Categories $children)
    {
        $this->children->removeElement($children);
    }
}
