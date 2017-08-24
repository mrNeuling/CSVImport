<?php

namespace AppBundle\Repository\Product;


use AppBundle\Entity\Product;

/**
 * ProductRepository
 *
 */
interface ProductRepository
{
    /**
     * Add one product to repo.
     *
     * @param Product $product
     * @return mixed
     */
    public function add(Product $product);

    /**
     * Find one product by unique code.
     *
     * @param string $code
     * @return Product|null
     */
    public function findByCode(string $code);
}
