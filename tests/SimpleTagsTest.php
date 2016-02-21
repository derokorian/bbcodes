<?php

use Dero\BBCodes\BBCodes;

class SimpleTagsTest extends PHPUnit_Framework_TestCase
{
    public function provideSimpleTests()
    {
        return [
            ['[b]Test[/b]', '<strong>Test</strong>'],
            ['[i]Test[/i]', '<i>Test</i>'],
            ['[u]Test[/u]', '<u>Test</u>']
        ];
    }

    /**
     * Validates that tags are changes when flag is passed
     *
     * @dataProvider provideSimpleTests
     */
    public function testSimpleOn($input, $output)
    {
        $this->assertEquals(
            $output,
            (new BBCodes())->clean($input, BBCodes::OPT_BASE)
        );
    }

    /**
     * Validates that tags are unchanged when flag is not passed
     *
     * @dataProvider provideSimpleTests
     */
    public function testSimpleOff($input, $output)
    {
        $this->assertEquals(
            $input,
            (new BBCodes())->clean($input, 0)
        );
    }
}