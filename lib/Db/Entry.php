<?php

namespace OCA\Diary\Db;

use JsonSerializable;
use OCP\AppFramework\Db\Entity;
use ReturnTypeWillChange;

class Entry extends Entity implements JsonSerializable
{

    protected $entryDate;
    protected $uid;
    protected $entryContent;

    public function __construct()
    {
        $this->addType('id', 'string');
        $this->addType('uid', 'string');
        $this->addType('entryDate', 'string');
        $this->addType('entryContent', 'string');
    }

    /**
     * @inheritDoc
     */
    #[ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'uid' => $this->uid,
            'entryDate' => $this->entryDate,
            'entryContent' => $this->entryContent,
        ];
    }
}