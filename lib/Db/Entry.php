<?php

namespace OCA\Diary\Db;

use JsonSerializable;
use OCP\AppFramework\Db\Entity;

class Entry extends Entity implements JsonSerializable
{

    protected $entry_date;
    protected $uid;
    protected $entry_content;

    public function __construct()
    {
        $this->addType('id', 'string');
        $this->addType('uid', 'string');
        $this->addType('entry_date', 'string');
        $this->addType('entry_content', 'string');
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'uid' => $this->uid,
            'entry_date' => $this->entry_date,
            'entry_content' => $this->entry_content,
        ];
    }
}