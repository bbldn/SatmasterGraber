<?php

namespace App\Domain\Common\Infrastructure\Repository\Base\Graber;

use Psr\Log\LoggerInterface as Logger;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\Common\Domain\Entity\Base\Graber\Product;

/**
 * @method Product[]    findAll()
 * @method Product[]    findByFrontIds(int[] $ids)
 * @method Product[]    findByParserIds(int[] $ids)
 * @method Product|null findOneByFrontId(int|null $frontId)
 * @method Product|null findOneByParserId(int|null $parserId)
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @psalm-method list<Product>   findAll()
 * @psalm-method list<Product>   findByBackIds(list<int> $ids)
 * @psalm-method list<Product>   findByFrontIds(list<int> $ids)
 * @psalm-method list<Product>   findByParserIds(list<int> $ids)
 * @psalm-method list<Product>   findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @template-extends GraberRepository<Product>
 */
class ProductRepository extends GraberRepository
{
    /**
     * @param Logger $logger
     * @param ManagerRegistry $registry
     */
    public function __construct(Logger $logger, ManagerRegistry $registry)
    {
        parent::__construct($logger, $registry, Product::class);
    }
}