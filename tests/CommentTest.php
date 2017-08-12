<?php

namespace Ahc\Json\Test;

use Ahc\Json\Comment;

/** @coversDefaultClass \Ahc\Json\Comment */
class CommentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider theTests
     * @covers \Ahc\Json\Comment::strip
     */
    public function testStrip($json, $expect)
    {
        $this->assertSame($expect, (new Comment)->strip($json));
    }

   /**
     * @dataProvider theTests
     * @covers \Ahc\Json\Comment::decode
     */
    public function testDecode($json)
    {
        $actual = (new Comment)->decode($json, true);

        $this->assertNotEmpty($actual);
        $this->assertArrayHasKey('a', $actual);
        $this->assertArrayHasKey('b', $actual);

        $this->assertSame(JSON_ERROR_NONE, json_last_error());
    }

    public function theTests()
    {
        return [
            'without comment' => [
                'json'   => '{"a":1,"b":2}',
                'expect' => '{"a":1,"b":2}',
            ],
            'single line comment' => [
                'json'   => '{"a":1,
                // comment
                "b":2,
                // comment
                "c":3}',
                'expect' => '{"a":1,
                "b":2,
                "c":3}',
            ],
            'single line comment at end' => [
                'json'   => '{"a":1,
                "b":2,// comment
                "c":3}',
                'expect' => '{"a":1,
                "b":2,
                "c":3}',
            ],
            'real multiline comment' => [
                'json'   => '{"a":1,
                /*
                 * comment
                 */
                "b":2, "c":3}',
                'expect' => '{"a":1,
                ' . '
                "b":2, "c":3}',
            ],
            'inline multiline comment' => [
                'json'   => '{"a":1,
                /* comment */ "b":2, "c":3}',
                'expect' => '{"a":1,
                 "b":2, "c":3}',
            ],
            'inline multiline comment at end' => [
                'json'   => '{"a":1, "b":2, "c":3/* comment */}',
                'expect' => '{"a":1, "b":2, "c":3}',
            ],
            'comment inside string' => [
                'json'   => '{"a": "a//b", "b":"a/* not really comment */b"}',
                'expect' => '{"a": "a//b", "b":"a/* not really comment */b"}',
            ],
        ];
    }
}
