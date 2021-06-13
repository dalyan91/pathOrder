<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"category"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Serializer\Groups({"category"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     * @Serializer\Groups({"category.product"})
     */
    private $product;

    /**
     * @ORM\OneToMany(targetEntity="Discount", mappedBy="category")
     * @Serializer\Groups({"category.discount"})
     */
    private $discount;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->discount = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Category
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
     * Add product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Category
     */
    public function addProduct(\AppBundle\Entity\Product $product)
    {
        $this->product[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \AppBundle\Entity\Product $product
     */
    public function removeProduct(\AppBundle\Entity\Product $product)
    {
        $this->product->removeElement($product);
    }

    /**
     * Get product
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Add discount
     *
     * @param \AppBundle\Entity\Discount $discount
     *
     * @return Category
     */
    public function addDiscount(\AppBundle\Entity\Discount $discount)
    {
        $this->discount[] = $discount;

        return $this;
    }

    /**
     * Remove discount
     *
     * @param \AppBundle\Entity\Discount $discount
     */
    public function removeDiscount(\AppBundle\Entity\Discount $discount)
    {
        $this->discount->removeElement($discount);
    }

    /**
     * Get discount
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDiscount()
    {
        return $this->discount;
    }
}
