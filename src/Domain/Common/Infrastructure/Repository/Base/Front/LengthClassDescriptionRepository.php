<?php

namespace App\Domain\Common\Infrastructure\Repository\Base\Front;

use Psr\Log\LoggerInterface as Logger;
use Doctrine\Persistence\ManagerRegistry;
use App\Domain\Common\Infrastructure\Repository\Base\Repository;
use App\Domain\Common\Domain\Entity\Base\Front\LengthClassDescription;

/**
 * @method LengthClassDescription[]    findAll()
 * @method LengthClassDescription|null find($id, $lockMode = null, $lockVersion = null)
 * @method LengthClassDescription|null findOneBy(array $criteria, array $orderBy = null)
 * @method LengthClassDescription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @psalm-method list<LengthClassDescription>   findAll()
 * @psalm-method list<LengthClassDescription>   findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @template-extends Repository<LengthClassDescription>
 */
class LengthClassDescriptionRepository extends Repository
{
    /**
     * @param Logger $logger
     * @param ManagerRegistry $registry
     */
    public function __construct(Logger $logger, ManagerRegistry $registry)
    {
        parent::__construct($logger, $registry, LengthClassDescription::class);
    }
}