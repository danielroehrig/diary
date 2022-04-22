<?php

namespace OCA\Diary\Service;

use Dompdf\Dompdf;
use League\CommonMark\CommonMarkConverter;
use OCA\Diary\Db\Entry;

class ExportService
{
    public function entryToPDF(Entry $entry): string
    {
        $pdf = new Dompdf();
        $pdf->setPaper('A4', 'portrait');
        $serialized = $entry->jsonSerialize();
        $data = '# ' . $serialized['entryDate'];
        $data .= sprintf("\r\n\r\n%s", $serialized['entryContent']);
        $converter = new CommonMarkConverter();
        $data = $converter->convertToHtml($data);
        $pdf->loadHtml($data);
        $pdf->render();
        return $pdf->output();
    }
}