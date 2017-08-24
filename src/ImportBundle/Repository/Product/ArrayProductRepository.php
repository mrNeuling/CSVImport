<?php

namespace ImportBundle\Repository\Product;


use AppBundle\Entity\Product;
use AppBundle\Repository\Product\ProductRepository;

/**
 * Class ArrayProductRepository
 * @package ImportBundle\Repository\Product
 */
class ArrayProductRepository implements ProductRepository
{
    /**
     * @var Product[]
     */
    private $products;

    /**
     * ArrayProductRepository constructor.
     */
    public function __construct()
    {
        $this->products = [];
    }

    /**
     * @inheritdoc
     */
    public function add(Product $product)
    {
        $this->products[$product->getCode()] = $product;
    }

    /**
     * @inheritdoc
     */
    public function findByCode(string $code)
    {
        foreach ($this->products as $product) {
            if ($product->getCode() === $code) {
                return $product;
            }
        }

        return null;
    }
}