<?php

namespace OCA\Diary\Controller;

use iio\libmergepdf\Merger;
use OCA\Diary\Db\Entry;
use OCA\Diary\Db\EntryMapper;
use OCA\Diary\Service\ExportService;
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
    /**
     * @var ExportService
     */
    private $exportService;

    public function __construct($AppName, IRequest $request, $UserId, EntryMapper $mapper, ExportService $exportService)
    {
        parent::__construct($AppName, $request);
        $this->userId = $UserId;
        $this->mapper = $mapper;
        $this->exportService = $exportService;
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
            $data .= '# ' . $serialized['entryDate'];
            $data .= sprintf("\r\n\r\n%s\r\n", $serialized['entryContent']);
        }
        return new DataDownloadResponse($data, 'diary.md', 'text/plain');
    }

    /**
     * @return DataDownloadResponse
     * @NoAdminRequired
     * @NoCSRFRequired
     * @throws Exception
     */
    public function getPdf(): DataDownloadResponse
    {
        $entries = $this->mapper->findAll($this->userId);
        $pdfMerger = new Merger();
        /** @var Entry $entry */
        foreach ($entries as $entry) {
            $pdfMerger->addRaw($this->exportService->entryToPDF($entry));
        }

        return new DataDownloadResponse($pdfMerger->merge(), 'diary.pdf', 'text/plain');
    }
}
