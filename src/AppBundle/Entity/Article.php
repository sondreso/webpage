<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\ArticleRepository")
 * @ORM\Table(name="article")
 * @ORM\HasLifecycleCallbacks
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="text")
     */
    protected $article;

    /**
     * @ORM\Column(type="string")
     */
    protected $imageLarge;

    /**
     * @ORM\Column(type="string")
     */
    protected $imageMedium;

    /**
     * @ORM\Column(type="string")
     */
    protected $imageSmall;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $sticky;

    /**
     * @ORM\ManyToMany(targetEntity="Department")
     * @ORM\JoinTable(name="articles_departments",
     *      joinColumns={@ORM\JoinColumn(name="article_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="department_id", referencedColumnName="id")}
     *      )
     **/
    protected $departments; // Unidirectional, may change

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id"))
     */
    protected $author; // Unidirectional, may change

    public function __construct()
    {
        $this->departments = new ArrayCollection();
        $this->setCreated(new \DateTime());
        $this->setUpdated(new \DateTime());
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
        $this->setUpdated(new \DateTime());
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
     * @return Article
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
     * Set article
     *
     * @param string $article
     * @return Article
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return string 
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set imageLarge
     *
     * @param string $imageLarge
     * @return Article
     */
    public function setImageLarge($imageLarge)
    {
        $this->imageLarge = $imageLarge;

        return $this;
    }

    /**
     * Get imageLarge
     *
     * @return string 
     */
    public function getImageLarge()
    {
        return $this->imageLarge;
    }

    /**
     * Set imageMedium
     *
     * @param string $imageMedium
     * @return Article
     */
    public function setImageMedium($imageMedium)
    {
        $this->imageMedium = $imageMedium;

        return $this;
    }

    /**
     * Get imageMedium
     *
     * @return string 
     */
    public function getImageMedium()
    {
        return $this->imageMedium;
    }

    /**
     * Set imageSmall
     *
     * @param string $imageSmall
     * @return Article
     */
    public function setImageSmall($imageSmall)
    {
        $this->imageSmall = $imageSmall;

        return $this;
    }

    /**
     * Get imageSmall
     *
     * @return string 
     */
    public function getImageSmall()
    {
        return $this->imageSmall;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Article
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
     * Set updated
     *
     * @param \DateTime $updated
     * @return Article
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Add departments
     *
     * @param \AppBundle\Entity\Department $departments
     * @return Article
     */
    public function addDepartment(\AppBundle\Entity\Department $departments)
    {
        $this->departments[] = $departments;

        return $this;
    }

    /**
     * Remove departments
     *
     * @param \AppBundle\Entity\Department $departments
     */
    public function removeDepartment(\AppBundle\Entity\Department $departments)
    {
        $this->departments->removeElement($departments);
    }

    /**
     * Get departments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDepartments()
    {
        return $this->departments;
    }

    /**
     * Set author
     *
     * @param \AppBundle\Entity\User $author
     * @return Article
     */
    public function setAuthor(\AppBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \AppBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set sticky
     *
     * @param boolean $sticky
     * @return Article
     */
    public function setSticky($sticky)
    {
        $this->sticky = $sticky;

        return $this;
    }

    /**
     * Get sticky
     *
     * @return boolean 
     */
    public function getSticky()
    {
        return $this->sticky;
    }
}
