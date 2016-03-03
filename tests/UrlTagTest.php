<?php

use Dero\BBCodes\BBCodes;

class UrlTagsTest extends PHPUnit_Framework_TestCase
{
    public function provideUrlTests()
    {
        return [
            ['[url]http://example.com[/url]','<a href="http://example.com" target="_blank">example.com</a>'],
            ['[url=http://example.com]some text[/url]','<a href="http://example.com" target="_blank">some text</a>'],
            ['[urls]block of text http://example.com containing many raw http://example.com urls[/urls]','block of text <a href="http://example.com">example.com</a> containing many raw <a href="http://example.com">example.com</a> urls']
        ];
    }

    /**
     * Validates that tags are changes when flag is passed
     *
     * @dataProvider provideUrlTests
     */
    public function testUrlOn($input, $output)
    {
        $this->assertEquals(
            $output,
            (new BBCodes())->clean($input, BBCodes::OPT_URLS)
        );
    }

    /**
     * Validates that tags are unchanged when flag is not passed
     *
     * @dataProvider provideUrlTests
     */
    public function testUrlOff($input, $output)
    {
        $this->assertEquals(
            $input,
            (new BBCodes())->clean($input,0)
        );
    }
}