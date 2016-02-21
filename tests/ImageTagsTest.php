<?php

use Dero\BBCodes\BBCodes;

class ImageTagsTest extends PHPUnit_Framework_TestCase
{
    public function provideImageTests()
    {
        return [
            ['[img]Test[/img]', '<img src="Test" alt="" title="" />'],
            ['[img=Test]Title[/img]', '<img src="Test" alt="Title" title="Title" />']
        ];
    }

    /**
     * Validates that tags are changes when flag is passed
     *
     * @dataProvider provideImageTests
     */
    public function testImageOn($input, $output)
    {
        $this->assertEquals(
            $output,
            (new BBCodes())->clean($input, BBCodes::OPT_IMG)
        );
    }

    /**
     * Validates that tags are unchanged when flag is not passed
     *
     * @dataProvider provideImageTests
     */
    public function testImageOff($input, $output)
    {
        $this->assertEquals(
            $input,
            (new BBCodes())->clean($input,0)
        );
    }
}