<?php

namespace OCA\Diary\Tests\Integration\Controller;

use OCA\Diary\Controller\PageController;
use OCA\Diary\Db\Entry;
use OCA\Diary\Db\EntryMapper;
use OCP\AppFramework\App;
use OCP\AppFramework\Http\DataResponse;
use PHPUnit\Framework\TestCase;


class EntriesManipulationTest extends TestCase
{

    private $userId = 'john';
    private $controller;
    private $mapper;

    public function setUp(): void
    {
        parent::setUp();
        $app = new App('diary');
        $container = $app->getContainer();

        $container->registerService('UserId', function ($c) {
            return $this->userId;
        });

        $this->controller = $container->query(PageController::class);
        $this->mapper = $container->query(EntryMapper::class);
    }

    public function testGetExistingEntry()
    {
        $date = '2022-01-01';
        $content = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam';
        $entry = new Entry();
        $entry->setId($this->userId . $date);
        $entry->setUid($this->userId);
        $entry->setEntryDate($date);
        $entry->setEntryContent($content);

        $this->mapper->insert($entry);

        /** @var DataResponse $response */
        $response = $this->controller->getEntry($date);
        $this->assertEquals(200, $response->getStatus());
        /** @var Entry $data */
        $data = $response->getData()->jsonSerialize();
        $this->assertEquals($date, $data['entryDate']);
        $this->assertEquals($content, $data['entryContent']);
        $this->assertEquals($this->userId, $data['uid']);
        $this->assertEquals($this->userId . $date, $data['id']);

        $this->mapper->delete($entry);


    }

}
