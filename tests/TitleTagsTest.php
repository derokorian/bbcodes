<?php

use Dero\BBCodes\BBCodes;

class TitleTagsTest extends PHPUnit_Framework_TestCase
{
    public function provideTitleTests()
    {
        return [
            ['[title]Test[/title]', '<h2>Test</h2>'],
            ['[subtitle]Test[/subtitle]', '<h3>Test</h3>']
        ];
    }

    /**
     * Validates that tags are changes when flag is passed
     *
     * @dataProvider provideTitleTests
     */
    public function testTitleOn($input, $output)
    {
        $this->assertEquals(
            $output,
            (new BBCodes())->clean($input, BBCodes::OPT_TITLE)
        );
    }

    /**
     * Validates that tags are unchanged when flag is not passed
     *
     * @dataProvider provideTitleTests
     */
    public function testTitleOff($input, $output)
    {
        $this->assertEquals(
            $input,
            (new BBCodes())->clean($input,0)
        );
    }
}