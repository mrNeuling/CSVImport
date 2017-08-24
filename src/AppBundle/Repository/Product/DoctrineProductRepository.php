<?php

namespace AppBundle\Repository\Product;


use AppBundle\Entity\Product;
use Doctrine\ORM\EntityRepository;

/**
 * Class DoctrineProductRepository
 * @package AppBundle\Repository
 */
class DoctrineProductRepository extends EntityRepository implements ProductRepository
{
    /**
     * @inheritdoc
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function add(Product $product)
    {
        $this->getEntityManager()->persist($product);
    }

    /**
     * @inheritdoc
     */
    public function findByCode(string $code)
    {
        return $this->findOneBy(compact('code'));
    }
}