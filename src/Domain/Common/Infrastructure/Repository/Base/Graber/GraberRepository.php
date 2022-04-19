<?php

namespace App\Domain\Common\Infrastructure\Repository\Base\Graber;

use Throwable;
use Doctrine\DBAL\Connection;
use App\Domain\Common\Infrastructure\Repository\Base\Repository;

/**
 * @psalm-template T
 * @template-extends Repository<T>
 */
abstract class GraberRepository extends Repository
{
    /**
     * @param int|null $frontId
     * @return mixed
     *
     * @psalm-return T|null
     */
    public function findOneByFrontId(?int $frontId)
    {
        if (null === $frontId) {
            return null;
        }

        try {
            return $this->createQueryBuilder('a')
                ->andWhere('a.frontId = :frontId')
                ->setParameter('frontId', $frontId)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (Throwable $e) {
            /** @var string $e */
            $this->logger->error($e);

            return null;
        }
    }

    /**
     * @param int[] $ids
     * @return array
     *
     * @psalm-return list<T>
     * @psalm-param list<int> $ids
     */
    public function findByFrontIds(array $ids): array
    {
        return $this->createQueryBuilder('a')
            ->where("a.frontId IN (:ids)")
            ->setParameter('ids', $ids, Connection::PARAM_INT_ARRAY)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int|null $parserId
     * @return mixed
     *
     * @psalm-return T|null
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function findOneByParserId(?int $parserId)
    {
        if (null === $parserId) {
            return null;
        }

        try {
            return $this->createQueryBuilder('a')
                ->andWhere('a.parserId = :parserId')
                ->setParameter('parserId', $parserId)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (Throwable $e) {
            /** @var string $e */
            $this->logger->error($e);

            return null;
        }
    }

    /**
     * @param int[] $ids
     * @return array
     *
     * @psalm-return list<T>
     * @psalm-param list<int> $ids
     */
    public function findByParserIds(array $ids): array
    {
        return $this->createQueryBuilder('a')
            ->where("a.parserId IN (:ids)")
            ->setParameter('ids', $ids, Connection::PARAM_INT_ARRAY)
            ->getQuery()
            ->getResult();
    }
}