<?php

namespace OCA\Diary\Tests\Unit\Controller;

use OCA\Diary\Db\Entry;
use OCA\Diary\Service\ConversionService;
use PHPUnit\Framework\TestCase;

class ConversionServiceTest extends TestCase
{
    /**
     * @var ConversionService
     */
    private $conversionService;

    public function setUp(): void
    {
        $this->conversionService = new ConversionService();
    }

    public function testMarkdownConversion()
    {
        $entry = $this->createMockEntry('2022-04-24', 'testuser', 'This is _content_.');
        $expectedMarkdown = "# 2022-04-24\r\n\r\nThis is _content_.";

        $result = $this->conversionService->entryToMarkdown($entry);

        $this->assertEquals($expectedMarkdown, $result);
    }

    public function testHtmlConversion()
    {
        $markdown = "# 2022-04-24\r\n\r\nThis is _content_.";
        $expectedHtml = "<h1>2022-04-24</h1>\n<p>This is <em>content</em>.</p>\n";
        $result = $this->conversionService->markdownToHTML($markdown);

        $this->assertEquals($expectedHtml, $result);
    }

    /**
     * Create an Entry element.
     */
    private function createMockEntry(string $date, string $userId, string $content): Entry
    {
        $entry = new Entry();
        $entry->setId($userId.$date);
        $entry->setUid($userId);
        $entry->setEntryDate($date);
        $entry->setEntryContent($content);

        return $entry;
    }
}
