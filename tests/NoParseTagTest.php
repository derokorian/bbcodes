<?php

use Dero\BBCodes\BBCodes;

class NoParseTagsTest extends PHPUnit_Framework_TestCase
{
    public function provideNoParseTests()
    {
        return [
            ['[noparse]text [b]not[/b] to [code]be parsed[/code] for [i]other[/i] BBCodes[/noparse]', 'text [b]not[/b] to [code]be parsed[/code] for [i]other[/i] BBCodes'],
            ['[noparse]text [b]not[/b] to[/noparse] [i]be parsed[/i] [noparse]for [code]other[/code] BBCodes[/noparse]', 'text [b]not[/b] to <i>be parsed</i> for [code]other[/code] BBCodes']
        ];
    }

    /**
     * Validates that tags are changes when flag is passed
     *
     * @dataProvider provideNoParseTests
     */
    public function testImageOn($input, $output)
    {
        $this->assertEquals(
            $output,
            (new BBCodes())->fullClean($input)
        );
    }

    /**
     * Validates that tags are unchanged when flag is not passed
     *
     * @dataProvider provideNoParseTests
     */
    public function testImageOff($input, $output)
    {
        $this->assertEquals(
            $input,
            (new BBCodes())->clean($input,0)
        );
    }
}