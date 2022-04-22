<?php

namespace OCA\Diary\Service;

use Dompdf\Dompdf;
use iio\libmergepdf\Merger;
use League\CommonMark\CommonMarkConverter;
use OCA\Diary\Db\Entry;

class ConversionService
{
    /**
     * @param array|Entry[] $entries
     * @return void
     */
    public function entriesToPdf(array $entries): string
    {
        $pdfMerger = new Merger();
        /** @var Entry $entry */
        foreach ($entries as $entry) {
            $pdfMerger->addRaw($this->entryToPDF($entry));
        }
        return $pdfMerger->merge();
    }

    public function entryToPDF(Entry $entry): string
    {
        $data = $this->entryToMarkdown($entry);
        $data = $this->markdownToHTML($data);
        return $this->htmlToPDF($data);
    }

    public function entriesToMarkdown(array $entries): string
    {
        $data = '';
        /** @var Entry $entry */
        foreach ($entries as $entry) {
            $data .= $this->entryToMarkdown($entry);
        }
        return $data;
    }

    /**
     * @param Entry $entry
     * @return string
     */
    public function entryToMarkdown(Entry $entry): string
    {
        $serialized = $entry->jsonSerialize();
        $data = '# ' . $serialized['entryDate'];
        $data .= sprintf("\r\n\r\n%s", $serialized['entryContent']);
        return $data;
    }

    /**
     * @param string $data
     * @return string
     */
    public function markdownToHTML(string $data): string
    {
        $converter = new CommonMarkConverter();
        $data = $converter->convertToHtml($data);
        return $data;
    }

    /**
     * @param string $data
     * @return string|null
     */
    public function htmlToPDF(string $data): ?string
    {
        $pdf = new Dompdf();
        $pdf->setPaper('A4', 'portrait');
        $pdf->loadHtml($data);
        $pdf->render();
        return $pdf->output();
    }
}