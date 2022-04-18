<?php

namespace OCA\Diary\Controller;

use OCA\Diary\Db\Entry;
use OCA\Diary\Db\EntryMapper;
use OCP\AppFramework\Http\DataDownloadResponse;
use OCP\DB\Exception;
use OCP\IRequest;
use OCP\AppFramework\Controller;

class ExportController extends Controller
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
     * @return DataDownloadResponse
     * @NoAdminRequired
     * @NoCSRFRequired
     * @throws Exception
     */
    public function getMarkdown(): DataDownloadResponse
    {
        $entries = $this->mapper->findAll($this->userId);
        $data = '';
        /** @var Entry $entry */
        foreach ($entries as $entry) {
            $serialized = $entry->jsonSerialize();
            $data .= '#' . $serialized['entryDate'];
            $data .= sprintf("\r\n%s\r\n", $serialized['entryContent']);
        }
        return new DataDownloadResponse($data, 'diary.md', 'text/plain');
    }
}
