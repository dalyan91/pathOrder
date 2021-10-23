<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"product"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Serializer\Groups({"product"})
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     * @Serializer\Groups({"product"})
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="product")
     * @Serializer\Groups({"product.order"})
     */
    private $ordering;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ordering = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Add ordering
     *
     * @param \AppBundle\Entity\Order $ordering
     *
     * @return Product
     */
    public function addOrdering(\AppBundle\Entity\Order $ordering)
    {
        $this->ordering[] = $ordering;

        return $this;
    }

    /**
     * Remove ordering
     *
     * @param \AppBundle\Entity\Order $ordering
     */
    public function removeOrdering(\AppBundle\Entity\Order $ordering)
    {
        $this->ordering->removeElement($ordering);
    }

    /**
     * Get ordering
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrdering()
    {
        return $this->ordering;
    }
}
