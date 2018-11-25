<?php
// src/CMS/Bundle/ContentBundle/Entity/Content.php
namespace CMS\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CMS\ContentBundle\Entity\MenuItems;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="CMS_menu")
 * @ORM\Entity(repositoryClass="CMS\ContentBundle\Entity\MenusRepository")
 */
class Menus
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
     * @ORM\Column(name="description", type="text", length=500)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="MenuItems", mappedBy="menu")
     **/
    private $items;
	
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
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
     * @return Menus
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
     * Set description
     *
     * @param string $description
     * @return Menus
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
     * Add items
     *
     * @param \CMS\ContentBundle\Entity\MenuItems $items
     * @return Menus
     */
    public function addItem(MenuItems $items)
    {
        $this->items[] = $items;
    
        return $this;
    }

    /**
     * Remove items
     *
     * @param \CMS\ContentBundle\Entity\MenuItems $items
     */
    public function removeItem(MenuItems $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return string
     */
    public function __toString()
	{
		return (string)$this->id;
	}
}
