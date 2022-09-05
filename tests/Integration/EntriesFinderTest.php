<?php

namespace OCA\Diary\Tests\Integration\Controller;

use OCA\Diary\Db\Entry;
use OCA\Diary\Db\EntryMapper;
use OCP\AppFramework\App;
use PHPUnit\Framework\TestCase;

class EntriesFinderTest extends TestCase
{
    private $userId = 'john';
    /** @var EntryMapper */
    private $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $app = new App('diary');
        $container = $app->getContainer();

        $container->registerService('UserId', function ($c) {
            return $this->userId;
        });

        $this->mapper = $container->query(EntryMapper::class);
    }

    public function testGetExistingEntry()
    {
        $date = '2022-01-01';
        $content = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam';
        $entry = new Entry();
        $entry->setId($this->userId.$date);
        $entry->setUid($this->userId);
        $entry->setEntryDate($date);
        $entry->setEntryContent($content);

        $this->mapper->insert($entry);

        $date = '2022-01-01';
        $content2 = 'Same day, different user';
        $entry2 = new Entry();
        $uid2 = 'dave';
        $entry2->setId($uid2.$date);
        $entry2->setUid($uid2);
        $entry2->setEntryDate($date);
        $entry2->setEntryContent($content2);

        $this->mapper->insert($entry2);

        $insertedEntry = $this->mapper->find($this->userId, $date);
        $data = $insertedEntry->jsonSerialize();
        $this->assertEquals($date, $data['entryDate']);
        $this->assertEquals($content, $data['entryContent']);
        $this->assertEquals($this->userId, $data['uid']);
        $this->assertEquals($this->userId.$date, $data['id']);

        $insertedEntry = $this->mapper->find($uid2, $date);
        $data = $insertedEntry->jsonSerialize();
        $this->assertEquals($date, $data['entryDate']);
        $this->assertEquals($content2, $data['entryContent']);
        $this->assertEquals($uid2, $data['uid']);
        $this->assertEquals($uid2.$date, $data['id']);

        $this->mapper->delete($entry);
        $this->mapper->delete($entry2);
    }

    public function testGetExistingEntries()
    {
        $date = '2022-02-01';
        $content = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam';
        $entry = new Entry();
        $entry->setId($this->userId.$date);
        $entry->setUid($this->userId);
        $entry->setEntryDate($date);
        $entry->setEntryContent($content);

        $this->mapper->insert($entry);

        $content2 = 'Same day, different user';
        $entry2 = new Entry();
        $uid2 = 'dave';
        $entry2->setId($uid2.$date);
        $entry2->setUid($uid2);
        $entry2->setEntryDate($date);
        $entry2->setEntryContent($content2);

        $this->mapper->insert($entry2);

        $date2 = '2022-01-02';
        $content3 = 'Same user, different day, earlier';
        $entry3 = new Entry();
        $entry3->setId($this->userId.$date2);
        $entry3->setUid($this->userId);
        $entry3->setEntryDate($date2);
        $entry3->setEntryContent($content3);

        $this->mapper->insert($entry3);

        $date3 = '2022-02-02';
        $content4 = 'Same user, different day, later';
        $entry4 = new Entry();
        $entry4->setId($this->userId.$date3);
        $entry4->setUid($this->userId);
        $entry4->setEntryDate($date3);
        $entry4->setEntryContent($content4);

        $this->mapper->insert($entry4);

        $insertedEntries = $this->mapper->findAll($this->userId);
        $this->assertCount(3, $insertedEntries);
        $insertedEntry = array_shift($insertedEntries);
        $data = $insertedEntry->jsonSerialize();
        $this->assertEquals($date2, $data['entryDate']);
        $this->assertEquals($content3, $data['entryContent']);
        $this->assertEquals($this->userId, $data['uid']);
        $this->assertEquals($this->userId.$date2, $data['id']);
        $insertedEntry = array_shift($insertedEntries);
        $data = $insertedEntry->jsonSerialize();
        $this->assertEquals($date, $data['entryDate']);
        $this->assertEquals($content, $data['entryContent']);
        $this->assertEquals($this->userId, $data['uid']);
        $this->assertEquals($this->userId.$date, $data['id']);
        $insertedEntry = array_shift($insertedEntries);
        $data = $insertedEntry->jsonSerialize();
        $this->assertEquals($date3, $data['entryDate']);
        $this->assertEquals($content4, $data['entryContent']);
        $this->assertEquals($this->userId, $data['uid']);
        $this->assertEquals($this->userId.$date3, $data['id']);
        
        $lastInsert = $insertedEntries = $this->mapper->findLast($this->userId,1);
        $this->assertEquals($entry4->getEntryDate(), $lastInsert[0]->getEntryDate());

        $lastThreeInserts = $insertedEntries = $this->mapper->findLast($this->userId,3);
        $this->assertCount(3, $lastThreeInserts);
        $this->assertEquals($entry4->getEntryDate(), $lastThreeInserts[0]->getEntryDate());
        $this->assertEquals($entry->getEntryDate(), $lastThreeInserts[1]->getEntryDate());
        $this->assertEquals($entry3->getEntryDate(), $lastThreeInserts[2]->getEntryDate());

        $this->mapper->delete($entry);
        $this->mapper->delete($entry2);
        $this->mapper->delete($entry3);
        $this->mapper->delete($entry4);
    }
}
