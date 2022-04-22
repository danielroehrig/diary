<?php

namespace OCA\Diary\Controller;

use OCA\Diary\Db\EntryMapper;
use OCA\Diary\Service\ConversionService;
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
     * @var ConversionService
     */
    private $exportService;

    public function __construct($AppName, IRequest $request, $UserId, EntryMapper $mapper, ConversionService $exportService)
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
        $markdownString = $this->exportService->entriesToMarkdown($entries);
        return new DataDownloadResponse($markdownString, 'diary.md', 'text/plain');
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

        $pdfString = $this->exportService->entriesToPdf($entries);
        return new DataDownloadResponse($pdfString, 'diary.pdf', 'text/plain');
    }
}
