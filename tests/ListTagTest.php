<?php

use Dero\BBCodes\BBCodes;

class ListTagsTest extends PHPUnit_Framework_TestCase
{
    public function provideListTests()
    {
        return [
            // items not in a list aren't changed
            ['[item]Test[/item]', '[item]Test[/item]'],

            // list without items are replaced with contents
            ['[list]Test[/list]', 'Test'],

            // list with items, and nested lists
            ['[list][item]Test[/item][/list]', '<ul><li>Test</li></ul>'],
            ['[list][item]Test[/item][item]Test[/item][/list]', '<ul><li>Test</li><li>Test</li></ul>'],
            ['[list][item]Test[/item][item][list][item]Test[/item][/list][/item][/list]', '<ul><li>Test</li><li><ul><li>Test</li></ul></li></ul>'],
        ];
    }

    /**
     * Validates that tags are changes when flag is passed
     *
     * @dataProvider provideListTests
     */
    public function testListOn($input, $output)
    {
        $this->assertEquals(
            $output,
            (new BBCodes())->clean($input, BBCodes::OPT_LIST)
        );
    }

    /**
     * Validates that tags are unchanged when flag is not passed
     *
     * @dataProvider provideListTests
     */
    public function testListOff($input, $output)
    {
        $this->assertEquals(
            $input,
            (new BBCodes())->clean($input, 0)
        );
    }
}