<?php

namespace OCA\Diary\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\Entity;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\Exception;
use OCP\IDBConnection;

class EntryMapper extends QBMapper
{
    public function __construct(IDBConnection $db)
    {
        parent::__construct($db, 'diary', Entry::class);
    }

    /**
     * @return mixed|Entity
     *
     * @throws DoesNotExistException
     * @throws MultipleObjectsReturnedException
     * @throws Exception
     */
    public function find(string $uid, string $date)
    {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName())
            ->where(
                $qb->expr()->eq('uid', $qb->createNamedParameter($uid))
            )->andWhere(
                $qb->expr()->eq('entry_date', $qb->createNamedParameter($date))
            );

        return $this->findEntity($qb);
    }

    /**
     * @param string $uid
     *
     * @return array|Entity[]
     *
     * @throws Exception
     */
    public function findAll(string $uid): array
    {
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
            ->from($this->getTableName())
            ->where(
                $qb->expr()->eq('uid', $qb->createNamedParameter($uid))
            )
            ->orderBy('date', 'ASC');

        return $this->findEntities($qb);
    }
}
