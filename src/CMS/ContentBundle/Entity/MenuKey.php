<?php

namespace CMS\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CMS\ContentBundle\Entity\Menus;
use Doctrine\Common\Collections\ArrayCollection;

class MenuKey
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
     * @ORM\ManyToOne(targetEntity="MenuKey", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    private $parentItem;
	
	/**
     * @ORM\OneToMany(targetEntity="MenuKey", mappedBy="parentItem")
     */
    private $children;
	
	/**
     * @ORM\OneToMany(targetEntity="MenuItems", mappedBy="menuKey"))
     **/
    private $menuItems;
	
	/**
     * @ORM\OneToMany(targetEntity="ContentPosition", mappedBy="menuKey")
     */
    private $positions;
	
	/**
     * @ORM\Column(name="ordering", type="integer")
     */
    private $ordering;

    /**
     * @ORM\Column(name="parentOrdering", type="integer", nullable=true)
     */
    private $parentOrdering;
	
	/**
     * @ORM\Column(name="defaultKey", type="integer")
     */
    private $defaultKey;

    /**
     * @return string
     */
    public function __toString()
	{
		return (string)$this->id;
	}
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->menuItems = new ArrayCollection();
        $this->positions = new ArrayCollection();
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
     * @return MenuKey
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
     * Set ordering
     *
     * @param integer $ordering
     * @return MenuKey
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
     * Set defaultKey
     *
     * @param integer $defaultKey
     * @return MenuKey
     */
    public function setDefaultKey($defaultKey)
    {
        $this->defaultKey = $defaultKey;
    
        return $this;
    }

    /**
     * Get defaultKey
     *
     * @return integer 
     */
    public function getDefaultKey()
    {
        return $this->defaultKey;
    }

    public function getAliasForLocale($locale)
    {
        foreach($this->menuItems as $menuItem){
            if($menuItem->getLanguage() == $locale){
                return $menuItem->getAlias();
            }
        }
        return $this->menuItems->get(0)->getAlias();
    }

    /**
     * Set parentItem
     *
     * @param \CMS\ContentBundle\Entity\MenuKey $parentItem
     * @return MenuKey
     */
    public function setParentItem(MenuKey $parentItem = null)
    {
        $this->parentItem = $parentItem;
    
        return $this;
    }

    /**
     * Get parentItem
     *
     * @return \CMS\ContentBundle\Entity\MenuKey
     */
    public function getParentItem()
    {
        return $this->parentItem;
    }

    /**
     * Add children
     *
     * @param \CMS\ContentBundle\Entity\MenuKey $children
     * @return MenuKey
     */
    public function addChildren(MenuKey $children)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param \CMS\ContentBundle\Entity\MenuKey $children
     */
    public function removeChildren(MenuKey $children)
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
     * Add menuItems
     *
     * @param \CMS\ContentBundle\Entity\MenuItems $menuItems
     * @return MenuKey
     */
    public function addMenuitem(MenuItems $menuItems)
    {
        $this->menuItems[] = $menuItems;
    
        return $this;
    }

    /**
     * Remove menuItems
     *
     * @param \CMS\ContentBundle\Entity\MenuItems $menuItems
     */
    public function removeMenuitem(MenuItems $menuItems)
    {
        $this->menuItems->removeElement($menuItems);
    }

    /**
     * Get menuItems
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMenuItems()
    {
        return $this->menuItems;
    }

    /**
     * Add positions
     *
     * @param \CMS\ContentBundle\Entity\ContentPosition $positions
     * @return MenuKey
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
     * Set parentOrdering
     *
     * @param integer $parentOrdering
     * @return MenuKey
     */
    public function setParentOrdering($parentOrdering)
    {
        $this->parentOrdering = $parentOrdering;
    
        return $this;
    }

    /**
     * Get parentOrdering
     *
     * @return integer 
     */
    public function getParentOrdering()
    {
        return $this->parentOrdering;
    }

    /**
     * Add children
     *
     * @param \CMS\ContentBundle\Entity\MenuKey $children
     * @return MenuKey
     */
    public function addChild(\CMS\ContentBundle\Entity\MenuKey $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \CMS\ContentBundle\Entity\MenuKey $children
     */
    public function removeChild(\CMS\ContentBundle\Entity\MenuKey $children)
    {
        $this->children->removeElement($children);
    }
}
