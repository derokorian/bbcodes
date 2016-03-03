<?php

use Dero\BBCodes\BBCodes;

class QuoteTagsTest extends PHPUnit_Framework_TestCase
{
    public function provideQuoteTests()
    {
        return [
            ['[quote]something attributed to someone else[/quote]','<div class="bbcodes_quote">something attributed to someone else</div>'],
            ['[quote=author]something attributed to someone else[/quote]','<div class="bbcodes_quote"><span class="bbcodes_quote_author">Quote by: <strong>author</strong></span><br />'."\nsomething attributed to someone else</div>"]
        ];
    }

    /**
     * Validates that tags are changes when flag is passed
     *
     * @dataProvider provideQuoteTests
     */
    public function testQuoteOn($input, $output)
    {
        $this->assertEquals(
            $output,
            (new BBCodes())->clean($input, BBCodes::OPT_QUOTE)
        );
    }

    /**
     * Validates that tags are unchanged when flag is not passed
     *
     * @dataProvider provideQuoteTests
     */
    public function testQuoteOff($input, $output)
    {
        $this->assertEquals(
            $input,
            (new BBCodes())->clean($input,0)
        );
    }
}