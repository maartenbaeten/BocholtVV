<?php

namespace CMS\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CMS\ContentBundle\Entity\MenuItems;
use CMS\ContentBundle\Entity\Menus;
use CMS\ContentBundle\Entity\Content;
use CMS\ContentBundle\Entity\ContentKey;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="CMS_content_menu_items")
 * @ORM\Entity(repositoryClass="CMS\ContentBundle\Entity\ContentPositionRepository")
 */
class ContentPosition
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="MenuKey", inversedBy="positions")
     * @ORM\JoinColumn(name="menu_key_id", referencedColumnName="id")
     **/
    private $menuKey;

    /**
     * @ORM\ManyToOne(targetEntity="ContentKey", inversedBy="positions")
     * @ORM\JoinColumn(name="content_key", referencedColumnName="id")
     **/
    private $contentKey;

    /**
     * @ORM\Column(name="position", type="text")
     */
    private $position;

    /**
     * @ORM\Column(name="ordering", type="integer")
     */
    private $ordering;

    /**
     * @ORM\Column(name="params", type="text", nullable=true)
     */
    private $params;

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
     * Set position
     *
     * @param string $position
     * @return ContentPosition
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set ordering
     *
     * @param integer $ordering
     * @return ContentPosition
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
     * Set params
     *
     * @param string $params
     * @return ContentPosition
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
     * @param MenuKey|null $menuKey
     * @return $this
     */
    public function setMenuKey(MenuKey $menuKey = null)
    {
        $this->menuKey = $menuKey;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMenuKey()
    {
        return $this->menuKey;
    }

    /**
     * Set contentKey
     *
     * @param \CMS\ContentBundle\Entity\ContentKey $contentKey
     * @return ContentPosition
     */
    public function setContentKey(ContentKey $contentKey = null)
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
}
