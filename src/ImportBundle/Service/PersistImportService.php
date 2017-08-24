<?php

namespace ImportBundle\Service;


use AppBundle\Repository\Product\ProductRepository;
use Doctrine\ORM\EntityManager;
use ImportBundle\Report\ImportReport;

/**
 * Class PersistImportService
 * @package ImportBundle\Service
 */
class PersistImportService extends TestImportService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * PersistImportService constructor.
     * @param ProductRepository $productRepository
     * @param ImportReport $report
     * @param EntityManager $entityManager
     */
    public function __construct(ProductRepository $productRepository, ImportReport $report, EntityManager $entityManager)
    {
        parent::__construct($productRepository, $report);

        $this->entityManager = $entityManager;
    }

    /**
     * @inheritdoc
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function importRow(array $row)
    {
        parent::importRow($row);

        $this->entityManager->flush();
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function importRows(\Iterator $rows)
    {
        try {
            $this->entityManager->beginTransaction();
            parent::importRows($rows);
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}