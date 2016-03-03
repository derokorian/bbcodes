<?php

use Dero\BBCodes\BBCodes;

class CodeTagsTest extends PHPUnit_Framework_TestCase
{
    public function provideCodeTests()
    {
        return [
            ['[code]Test[/code]', '<div class="bbcodes_code_wrapper"><span class="bbcodes_code_language">Code:</span>'."\n".'<div class="bbcodes_code"><pre>Test</pre></div></div>'],
            ['[code=javascript](function(){alert("hello world")})()[/code]', '<div class="bbcodes_code_wrapper"><span class="bbcodes_code_language">javascript Code:</span>'."\n".'<div class="bbcodes_code"><pre>(function(){alert("hello world")})()</pre></div></div>'],
        ];
    }

    /**
     * Validates that tags are changes when flag is passed
     *
     * @dataProvider provideCodeTests
     */
    public function testImageOn($input, $output)
    {
        $this->assertEquals(
            $output,
            (new BBCodes())->clean($input, BBCodes::OPT_CODE)
        );
    }

    /**
     * Validates that tags are unchanged when flag is not passed
     *
     * @dataProvider provideCodeTests
     */
    public function testImageOff($input, $output)
    {
        $this->assertEquals(
            $input,
            (new BBCodes())->clean($input,0)
        );
    }
}