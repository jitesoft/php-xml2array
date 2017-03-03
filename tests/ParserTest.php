<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ParserTest.php - Part of the php-xml2array project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\XML\Tests;

use InvalidArgumentException;
use Jitesoft\XML\Node;
use Jitesoft\XML\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase {

    private static $valid;
    private static $invalid;
    private static $complex;


    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();

        self::$valid = <<< XML
<?xml version="1.0"?>
<catalog>
   <book>
      <author>Gambardella, Matthew</author>
      <title>XML Developer's Guide</title>
      <genre>Computer</genre>
      <price>44.95</price>
      <publish_date>2000-10-01</publish_date>
      <description>An in-depth look at creating applications 
      with XML.</description>
   </book>
</catalog>
XML;

        self::$invalid = <<< XML
<?xml version="1.0"?>
<catalog>
   <boo
      <author>Gambardella, Matthew</author>
      <title>XML Developer's Guide</title>
      <genre>Computer</genre>
      <price>44.95</price>
      <publish_date>2000-10-01</publish_date>
      <description>An in-depth look at creating applications 
      with XML.</description>
   </book>
</catalog>
XML;

        self::$complex = <<< XML
<?xml version="1.0"?>
<complex>
    <object id="abc123">
        <childlist>
            <child id="1">-</child>
            <child id="2">!</child>
            <child id="3">Test.</child>
        </childlist>
    </object>
    <object id="321cba">
        <childlist>
            <child id="1" name="test">
                <subchild att="abc">
                <![CDATA[
                    Hi.
                ]]>
                </subchild>
            </child>
            <child id="2">Test!</child>
        </childlist>    
    </object>
</complex>
XML;
    }


    public function testParseInvalidXml() {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("The supplied XML is invalid.");
        $parser = new Parser();
        $parser->parse(self::$invalid);

    }

    public function testParseValidXml() {
        $parser = new Parser();
        $out = $parser->parse(self::$valid);
        $this->assertInstanceOf(Node::class, $out);
        $this->assertEquals("catalog", $out->getName());
        $this->assertEquals(1, $out->childCount());
    }

    public function testParseValidWithInvalidOutType() {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The outType \"test\" does not exist.");
        $parser = new Parser();
        $parser->parse(self::$valid, "test");
    }

    public function testParseToJson() {
        $parser = new Parser();
        $out = $parser->parse(self::$valid, Parser::OUT_TYPE_JSON);
        $this->assertJsonStringEqualsJsonString('{"name":"catalog","content":"","attributes":[],"children":[{"name":"book","content":"","attributes":[],"children":[{"name":"author","content":"Gambardella, Matthew","attributes":[],"children":[]},{"name":"title","content":"XML Developer\'s Guide","attributes":[],"children":[]},{"name":"genre","content":"Computer","attributes":[],"children":[]},{"name":"price","content":"44.95","attributes":[],"children":[]},{"name":"publish_date","content":"2000-10-01","attributes":[],"children":[]},{"name":"description","content":"An in-depth look at creating applications \n      with XML.","attributes":[],"children":[]}]}]}', $out);
    }

    public function testParseToArray() {
        $parser = new Parser();
        $out = $parser->parse(self::$valid, Parser::OUT_TYPE_ARRAY);
        $this->assertTrue(is_array($out));

        $this->assertEquals($out,
            [
                "name" => "catalog",
                "content" => "",
                "attributes" => [],
                "children" => [
                    [
                        "name" => "book",
                        "content" => "",
                        "attributes" => [],
                        "children" => [
                            [ "name" => "author", "content" => "Gambardella, Matthew", "attributes" => [], "children" => [] ],
                            [ "name" => "title", "content" => "XML Developer's Guide", "attributes" => [], "children" => [] ],
                            [ "name" => "genre", "content" => "Computer", "attributes" => [], "children" => [] ],
                            [ "name" => "price", "content" => "44.95", "attributes" => [], "children" => [] ],
                            [ "name" => "publish_date", "content" => "2000-10-01", "attributes" => [], "children" => [] ],
                            [ "name" => "description", "content" => "An in-depth look at creating applications \n      with XML.", "attributes" => [], "children" => [] ]
                        ]
                    ]
                ]
            ], true);
    }

    public function testParseToObject() {
        $parser = new Parser();
        $out = $parser->parse(self::$valid, Parser::OUT_TYPE_OBJECT);

        $this->assertEquals(
            new Node(
                "catalog",
                "",
                [],
                array(
                    new Node("book", "", [], [
                        new Node("author", "Gambardella, Matthew", []),
                        new Node("title", "XML Developer's Guide", []),
                        new Node("genre", "Computer", []),
                        new Node("price", "44.95", []),
                        new Node("publish_date", "2000-10-01", []),
                        new Node("description", "An in-depth look at creating applications \n      with XML.", []),
                    ])
                )
            ),
            $out
        );
    }

    public function testComplex() {
        $parser = new Parser();
        $out = $parser->parse(self::$complex);
        $this->assertInstanceOf(Node::class, $out);

        $this->assertEquals(
            new Node("complex", "", [], [
                new Node("object", "", ["id" => "abc123"], [
                    new Node("childlist", "", [], [
                        new Node("child", "-", ["id" => "1"]),
                        new Node("child", "!", ["id" => "2"]),
                        new Node("child", "Test.", ["id" => "3"])
                    ])
                ]),
                new Node("object", "", ["id" => "321cba"], [
                    new Node("childlist", "", [], [
                        new Node("child", "", ["id" => "1", "name" => "test"], [
                            new Node("subchild", "\r\n                    Hi.\r\n                ", ["att" => "abc"])
                        ]),
                        new Node("child", "Test!", ["id" => "2"])
                    ])
                ])
            ])
        ,$out);
    }

}
