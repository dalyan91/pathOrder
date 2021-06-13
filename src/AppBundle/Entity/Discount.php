<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Discount
 *
 * @ORM\Table(name="discount")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DiscountRepository")
 */
class Discount
{
    const OPERATION_TYPE_TOTAL = 'total';
    const OPERATION_TYPE_UNIT = 'unit';
    const DISCOUNT_TYPE_TOTAL= 'total';
    const DISCOUNT_TYPE_UNIT = 'unit';
    const DISCOUNT_TYPE_PERCENT = 'percent';


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"discount"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Serializer\Groups({"discount"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     * @Serializer\Groups({"discount","discount.code"})
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="operationType", type="string", length=20)
     * @Serializer\Groups({"discount"})
     */
    private $operationType;

    /**
     * @var int
     *
     * @ORM\Column(name="operationUnit", type="integer")
     * @Serializer\Groups({"discount"})
     */
    private $operationUnit;

    /**
     * @var bool
     *
     * @ORM\Column(name="lowestProduct", type="boolean")
     * @Serializer\Groups({"discount"})
     */
    private $lowestProduct = false;

    /**
     * @var string
     *
     * @ORM\Column(name="discountType", type="string", length=20)
     * @Serializer\Groups({"discount"})
     */
    private $discountType;

    /**
     * @var float
     *
     * @ORM\Column(name="discount", type="float")
     * @Serializer\Groups({"discount"})
     */
    private $discount;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="discount")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     * @Serializer\Groups({"discount.category"})
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="OrderDiscount", mappedBy="discount")
     * @Serializer\Groups({"discount.orderDiscount"})
     */
    private $orderDiscount;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orderDiscount = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Discount
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
     * Set code
     *
     * @param string $code
     *
     * @return Discount
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set operationType
     *
     * @param string $operationType
     *
     * @return Discount
     */
    public function setOperationType($operationType)
    {
        $this->operationType = $operationType;

        return $this;
    }

    /**
     * Get operationType
     *
     * @return string
     */
    public function getOperationType()
    {
        return $this->operationType;
    }

    /**
     * Set operationUnit
     *
     * @param integer $operationUnit
     *
     * @return Discount
     */
    public function setOperationUnit($operationUnit)
    {
        $this->operationUnit = $operationUnit;

        return $this;
    }

    /**
     * Get operationUnit
     *
     * @return integer
     */
    public function getOperationUnit()
    {
        return $this->operationUnit;
    }

    /**
     * Set lowestProduct
     *
     * @param boolean $lowestProduct
     *
     * @return Discount
     */
    public function setLowestProduct($lowestProduct)
    {
        $this->lowestProduct = $lowestProduct;

        return $this;
    }

    /**
     * Get lowestProduct
     *
     * @return boolean
     */
    public function getLowestProduct()
    {
        return $this->lowestProduct;
    }

    /**
     * Set discountType
     *
     * @param string $discountType
     *
     * @return Discount
     */
    public function setDiscountType($discountType)
    {
        $this->discountType = $discountType;

        return $this;
    }

    /**
     * Get discountType
     *
     * @return string
     */
    public function getDiscountType()
    {
        return $this->discountType;
    }

    /**
     * Set discount
     *
     * @param float $discount
     *
     * @return Discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Discount
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add orderDiscount
     *
     * @param \AppBundle\Entity\OrderDiscount $orderDiscount
     *
     * @return Discount
     */
    public function addOrderDiscount(\AppBundle\Entity\OrderDiscount $orderDiscount)
    {
        $this->orderDiscount[] = $orderDiscount;

        return $this;
    }

    /**
     * Remove orderDiscount
     *
     * @param \AppBundle\Entity\OrderDiscount $orderDiscount
     */
    public function removeOrderDiscount(\AppBundle\Entity\OrderDiscount $orderDiscount)
    {
        $this->orderDiscount->removeElement($orderDiscount);
    }

    /**
     * Get orderDiscount
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderDiscount()
    {
        return $this->orderDiscount;
    }
}
