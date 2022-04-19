<?php

namespace App\Domain\Common\Infrastructure\Repository\Base;

use Throwable;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface as Logger;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @psalm-template T
 * @template-extends ServiceEntityRepository<T>
 */
abstract class Repository extends ServiceEntityRepository
{
    protected Logger $logger;

    /**
     * @param Logger $logger
     * @param ManagerRegistry $registry
     * @param string $entityClass
     *
     * @psalm-param class-string<T> $entityClass
     */
    public function __construct(Logger $logger, ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
        $this->logger = $logger;
    }

    /**
     * @param mixed $instance
     * @return void
     *
     * @psalm-param T $instance
     */
    public function persist($instance): void
    {
        try {
            $this->_em->persist($instance);
        } catch (Throwable $e) {
            $this->logger->error((string)$e);
        }
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        try {
            $this->_em->flush();
        } catch (Throwable $e) {
            $this->logger->error((string)$e);
        }
    }

    /**
     * @param mixed $instance
     * @return void
     *
     * @psalm-param T $instance
     *
     * @noinspection PhpUnused
     */
    public function persistAndFlush($instance): void
    {
        try {
            $this->_em->persist($instance);
            $this->_em->flush();
        } catch (Throwable $e) {
            $this->logger->error((string)$e);
        }
    }

    /**
     * @param mixed $id
     * @return void
     *
     * @noinspection PhpUnused
     */
    public function removeById($id): void
    {
        try {
            $identifier = $this->getClassMetadata()->getSingleIdentifierFieldName();

            $this->createQueryBuilder('c')
                ->andWhere("c.$identifier = :id")
                ->setParameter('id', $id)
                ->delete()
                ->getQuery()
                ->execute();
        } catch (Throwable $e) {
            $this->logger->error((string)$e);

            return;
        }
    }

    /**
     * @return void
     */
    public function removeAll(): void
    {
        $this->createQueryBuilder('c')->delete()->getQuery()->execute();
    }

    /**
     * @param mixed $instance
     * @return void
     *
     * @psalm-param T $instance
     */
    public function remove($instance): void
    {
        try {
            $this->_em->remove($instance);
        } catch (Throwable $e) {
            $this->logger->error((string)$e);
        }
    }

    /**
     * @param mixed $instance
     * @return void
     *
     * @psalm-param T $instance
     *
     * @noinspection PhpUnused
     */
    public function removeAndFlush($instance): void
    {
        try {
            $this->_em->remove($instance);
            $this->_em->flush();
        } catch (Throwable $e) {
            $this->logger->error((string)$e);
        }
    }

    /**
     * @return void
     */
    public function resetAutoIncrements(): void
    {
        $this->setAutoIncrements(1);
    }

    /**
     * @param int $value
     * @return void
     */
    public function setAutoIncrements(int $value): void
    {
        /** @noinspection SqlNoDataSourceInspection */
        $sql = "ALTER TABLE `{$this->getTableName()}` AUTO_INCREMENT = $value;";

        try {
            $this->getEntityManager()->getConnection()->prepare($sql)->executeQuery();
        } catch (Throwable $e) {
            $this->logger->error((string)$e);
        }
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->_em->getClassMetadata($this->getEntityName())->getTableName();
    }

    /**
     * @param int[] $ids
     * @return array
     *
     * @psalm-param list<int> $ids
     * @psalm-return list<T>
     */
    public function findByIds(array $ids): array
    {
        try {
            $identifier = $this->getClassMetadata()->getSingleIdentifierFieldName();

            return $this->createQueryBuilder('c')
                ->where("c.$identifier IN (:ids)")
                ->setParameter('ids', $ids, Connection::PARAM_INT_ARRAY)
                ->getQuery()
                ->getResult();
        } catch (Throwable $e) {
            $this->logger->error((string)$e);

            return [];
        }
    }

    /**
     * @return void
     */
    public function truncate(): void
    {
        $sql = sprintf('TRUNCATE TABLE `%s`;', $this->getTableName());

        try {
            $this->getEntityManager()->getConnection()->prepare($sql)->executeQuery();
        } catch (Throwable $e) {
            $this->logger->error((string)$e);
        }
    }
}