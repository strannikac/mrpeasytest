<?php 

/* it doesn't work with namespace, 
I tried different ways, but didn't find solution */
namespace tests;

use PHPUnit\Framework\TestCase;

class TagTestCase extends TestCase
{
    /**
     * @param string $text input value for getTags() function
     * @param array $tags expected result from getTags($text) function
     *
     * @dataProvider provideTestData
     */
    public function testGet(string $text, array $tags)
    {
        $result = getTags($text);
        $this->assertSame($tags, $result);
    }

    /**
     * Data provider for testGet()
     * @return array[]
     * where key is a name of the test data
     * value is array of arguments for test case
     * @see testGet()
     */
    public function provideTestData()
    {
        return [
            'test #1' => [
                ' asasf asfasf [a:some link description]some link data[/a] fasfasf', 
                [[
                    'key' => 'a', 
                    'value' => [
                        'description' => 'some link description', 
                        'data' => 'some link data'
                    ]
                ]]
            ],

            'test #2' => [
                '[span:some span description]some span data[/span]',
                [[
                    'key' => 'span',
                    'value' => [
                        'description' => 'some span description',
                        'data' => 'some span data'
                    ]
                ]]
            ],

            'test #3' => [
                ' fafaf [div:some div description]some div data[/div]',
                [[
                    'key' => 'div',
                    'value' => [
                        'description' => 'some div description',
                        'data' => 'some div data'
                    ]
                ]]
            ],

            'test #4' => [
                '[span:some span description]some span data[/span] fafaf [div:some div description]some div data[/div] fafa',
                [[
                    'key' => 'span',
                    'value' => [
                        'description' => 'some span description',
                        'data' => 'some span data'
                    ]
                ], [
                    'key' => 'div',
                    'value' => [
                        'description' => 'some div description',
                        'data' => 'some div data'
                    ]
                ]]
            ],

            'test #5' => [
                'asfas asfasfas safasf[a:some link description]some link data[/a][span:some span description]some span data[/span] afaf fafaf [div:some div description]some div data[/div] afasf - afas, afasf!',
                [[
                    'key' => 'a',
                    'value' => [
                        'description' => 'some link description',
                        'data' => 'some link data'
                    ]
                ],[
                    'key' => 'span',
                    'value' => [
                        'description' => 'some span description',
                        'data' => 'some span data'
                    ]
                ], [
                    'key' => 'div',
                    'value' => [
                        'description' => 'some div description',
                        'data' => 'some div data'
                    ]
                ]]
            ]
        ];
    }
}

?>
