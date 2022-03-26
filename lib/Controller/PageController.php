<?php

namespace OCA\Diary\Controller;

use OCA\Diary\Db\Entry;
use OCA\Diary\Db\EntryMapper;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\DB\Exception;
use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Controller;
use OCP\Util;

class PageController extends Controller
{
    private $userId;
    /**
     * @var EntryMapper
     */
    private $mapper;

    public function __construct($AppName, IRequest $request, $UserId, EntryMapper $mapper)
    {
        parent::__construct($AppName, $request);
        $this->userId = $UserId;
        $this->mapper = $mapper;
    }

    /**
     * CAUTION: the @Stuff turns off security checks; for this page no admin is
     *          required and no CSRF check. If you don't know what CSRF is, read
     *          it up in the docs or you might create a security hole. This is
     *          basically the only required method to add this exemption, don't
     *          add it to any other method if you don't exactly know what it does
     *
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function index(): TemplateResponse
    {
        Util::addScript($this->appName, 'diary-main');
        return new TemplateResponse('diary', 'index');  // templates/index.php
    }

    /**
     * @param string $date
     * @return DataResponse
     * @NoAdminRequired
     * @NoCSRFRequired
     */
    public function getEntry(string $date): DataResponse
    {
        try {
            $entry = $this->mapper->find($this->userId, $date);
        } catch (DoesNotExistException $e) {
            return new DataResponse(["isEmpty" => true]);
        } catch (MultipleObjectsReturnedException|Exception $e) {
            return new DataResponse(['error' => $e->getMessage()], Http::STATUS_INTERNAL_SERVER_ERROR);
        }
        return new DataResponse($entry);
    }

    /**
     * @param string $date ISO date as identifier
     * @param string $content Diary entry to save
     * @return DataResponse
     * @NoAdminRequired
     */
    public function updateEntry(string $date, string $content): DataResponse
    {
        $content = strip_tags($content);
        $entry = new Entry();
        $entry->setId($this->userId . $date);
        $entry->setUid($this->userId);
        $entry->setEntryDate($date);
        $entry->setEntryContent($content);

        try {
            return new DataResponse($this->mapper->insertOrUpdate($entry));
        } catch (Exception $e) {
            return new DataResponse(['error' => $e->getMessage()], Http::STATUS_INTERNAL_SERVER_ERROR);
        }
    }


}
