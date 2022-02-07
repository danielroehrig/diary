<?php

namespace OCA\Diary\Db;

use OCP\AppFramework\Db\QBMapper;
use OCP\IDBConnection;

class EntryMapper extends QBMapper
{
    public function __construct(IDBConnection $db)
    {
        parent::__construct($db, 'diary', Entry::class);
    }

    /**
     * @param string $uid
     * @param string $date
     * @return mixed|\OCP\AppFramework\Db\Entity
     * @throws \OCP\AppFramework\Db\DoesNotExistException
     * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
     * @throws \OCP\DB\Exception
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
}