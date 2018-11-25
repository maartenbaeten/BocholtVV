<?php
// src/CMS/Bundle/ContentBundle/Entity/Content.php
namespace CMS\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CMS\ContentBundle\Entity\Menus;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="CMS_menu_items")
 * @ORM\Entity(repositoryClass="CMS\ContentBundle\Entity\MenuItemsRepository")
 */
class MenuItems
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string")
     */
    private $title;
	
	/**
     * @ORM\Column(name="alias", type="string", nullable=true)
     */
    private $alias;

    /**
     * @ORM\Column(name="status", type="integer")
     */
    private $status;
	
	/**
     * @ORM\Column(name="columns", type="string")
     */
    private $columns;
	
	/**
     * @ORM\Column(name="target", type="text", nullable=true)
     */
    private $target;
	
	/**
     * @ORM\Column(name="language", type="text")
     */
    private $language;
	
	/**
     * @ORM\Column(name="params", type="text", nullable=true)
     */
    private $params;
	
	/**
     * @ORM\ManyToOne(targetEntity="Menus", inversedBy="items")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
     **/
	private $menu;

	/**
     * @ORM\ManyToOne(targetEntity="MenuKey", inversedBy="menuItems")
     * @ORM\JoinColumn(name="menu_key", referencedColumnName="id")
     **/
    private $menuKey;

    /**
     * @return string
     */
    public function __toString()
	{
		return (string)$this->title;
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
     * Set title
     *
     * @param string $title
     * @return MenuItems
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
     * Set alias
     *
     * @param string $alias
     * @return MenuItems
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    
        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return MenuItems
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $columns
     * @return $this
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;
    
        return $this;
    }

    /**
     * Get columns
     *
     * @return string 
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param $target
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;
    
        return $this;
    }

    /**
     * Get target
     *
     * @return string 
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param $language
     * @return $this
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
     * @param $params
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;
    
        return $this;
    }

    /**
     * Get params
     *
     * @return string 
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param \CMS\ContentBundle\Entity\Menus|null $menu
     * @return $this
     */
    public function setMenu(Menus $menu = null)
    {
        $this->menu = $menu;
    
        return $this;
    }

    /**
     * Get menu
     *
     * @return \CMS\ContentBundle\Entity\Menus
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set menuKey
     *
     * @param \CMS\ContentBundle\Entity\MenuKey $menuKey
     * @return MenuItems
     */
    public function setMenuKey(MenuKey $menuKey = null)
    {
        $this->menuKey = $menuKey;
    
        return $this;
    }

    /**
     * Get menuKey
     *
     * @return \CMS\ContentBundle\Entity\MenuKey
     */
    public function getMenuKey()
    {
        return $this->menuKey;
    }
}
