<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * OrderItem
 *
 * @ORM\Table(name="order_item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderItemRepository")
 */
class OrderItem
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"orderItem"})
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     * @Serializer\Groups({"orderItem"})
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="unitPrice", type="float")
     * @Serializer\Groups({"orderItem"})
     */
    private $unitPrice;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     * @Serializer\Groups({"orderItem"})
     */
    private $total;

    /**
     * @ORM\ManyToOne(targetEntity="Ordering", inversedBy="orderItem", cascade={"persist"})
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", onDelete="CASCADE")
     * @Serializer\Groups({"orderItem.order"})
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="orderItem")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="SET NULL")
     * @Serializer\Groups({"orderItem.product"})
     */
    private $productId;

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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return OrderItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set unitPrice
     *
     * @param float $unitPrice
     *
     * @return OrderItem
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get unitPrice
     *
     * @return float
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return OrderItem
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set order
     *
     * @param \AppBundle\Entity\Ordering $order
     *
     * @return OrderItem
     */
    public function setOrder(\AppBundle\Entity\Ordering $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \AppBundle\Entity\Ordering
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set productId
     *
     * @param \AppBundle\Entity\Product $productId
     *
     * @return OrderItem
     */
    public function setProductId(\AppBundle\Entity\Product $productId = null)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return \AppBundle\Entity\Product
     */
    public function getProductId()
    {
        return $this->productId;
    }
}
