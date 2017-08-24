<?php

namespace AppBundle\Entity;

/**
 * Product
 */
class Product
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $code;

    /**
     * @var \DateTime
     */
    private $addedAt;

    /**
     * @var \DateTime
     */
    private $discontinuedAt;

    /**
     * @var int
     */
    private $timestamp;

    /**
     * @var int
     */
    private $availableCount;

    /**
     * Product price in GBP
     * @var float
     */
    private $price;


    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->addedAt = new \DateTime();
    }

    /**
     * Get id
     *
     * @return int
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
     * Set description
     *
     * @param string $description
     *
     * @return Product
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
     * Set code
     *
     * @param string $code
     *
     * @return Product
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
     * Get addedAt
     *
     * @return \DateTime
     */
    public function getAddedAt()
    {
        return $this->addedAt;
    }

    /**
     * Set discontinued
     *
     * @param \DateTime $discontinuedAt
     *
     * @return Product
     */
    public function setDiscontinuedAt($discontinuedAt)
    {
        $this->discontinuedAt = $discontinuedAt;

        return $this;
    }

    /**
     * Get discontinued
     *
     * @return \DateTime
     */
    public function getDiscontinuedAt()
    {
        return $this->discontinuedAt;
    }

    /**
     * Set timestamp
     *
     * @param integer $timestamp
     *
     * @return Product
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return float|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    /**
     * @return int|null
     */
    public function getAvailableCount()
    {
        return $this->availableCount;
    }

    /**
     * @param int $availableCount
     */
    public function setAvailableCount(int $availableCount)
    {
        $this->availableCount = $availableCount;
    }
}

