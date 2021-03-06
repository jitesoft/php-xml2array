<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  NodeTest.php - Part of the php-xml2array project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\XML\Tests;

use InvalidArgumentException;
use Jitesoft\XML\Node;
use Jitesoft\XML\Parser;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase {

    /** @var Node */
    private $parsed;

    public function setUp() {
        parent::setUp();

        $xml = <<< XML
<?xml version="1.0"?>
<complex>
    <object id="abc123" name="testname">
        <childlist>
            <child id="1">-</child>
            <othername>Test</othername>
            <child id="2">!</child>
            <child id="3">Test.</child>
        </childlist>
    </object>
    <object>Text.</object>
    <object id="321cba">
        <childlist>
            <child id="1" name="test">
                <subchild att="abc">
                <![CDATA[Hi.]]>
                </subchild>
            </child>
            <child id="2">Test!</child>
        </childlist>    
    </object>
</complex>
XML;
        $this->parsed = Parser::parseXml($xml);
    }

    public function testHasAttributes() {
        $this->assertEquals('complex', $this->parsed->getName());
        $child = $this->parsed->getChild(0);
        $this->assertTrue($child->hasAttributes());
        $this->assertFalse($child->getChild(0)->hasAttributes());
    }

    public function testHasChildren() {
        $this->assertEquals('complex', $this->parsed->getName());
        $this->assertTrue($this->parsed->hasChildren());
        $this->assertFalse($this->parsed->getChild(1)->hasChildren());
    }

    public function testGetAttributes() {
        $this->assertEquals('complex', $this->parsed->getName());
        $attributes = $this->parsed->getChild(0)->getAttributes();
        $this->assertCount(2, $attributes);
        $this->assertEquals("abc123", $attributes["id"]);
        $this->assertEquals("testname", $attributes["name"]);
        $attributes = $this->parsed->getAttributes();
        $this->assertEmpty($attributes);
    }

    public function testGetChildren() {
        $this->assertEquals('complex', $this->parsed->getName());
        $child = $this->parsed->getChild(0)->getChild(0);
        $this->assertCount(4, $child->getChildren());
        $this->assertCount(3, $child->getChildren("child"));
        $this->assertCount(0, $this->parsed->getChild(1)->getChildren());
    }

    public function testGetChildrenWithPreservedKeys() {

        $this->assertEquals('complex', $this->parsed->getName());
        $child = $this->parsed->getChild(0)->getChild(0);
        $children = $child->getChildren("child", true);
        $this->assertCount(3, $children);

        $this->assertTrue(array_key_exists(0, $children));
        $this->assertFalse(array_key_exists(1, $children));
        $this->assertTrue(array_key_exists(2, $children));
        $this->assertTrue(array_key_exists(3, $children));

    }

    public function testGetChildrenWithoutPreservedKeys() {
        $this->assertEquals('complex', $this->parsed->getName());
        $child = $this->parsed->getChild(0)->getChild(0);
        $children = $child->getChildren("child", false);
        $this->assertCount(3, $children);

        $this->assertTrue(array_key_exists(0, $children));
        $this->assertTrue(array_key_exists(1, $children));
        $this->assertTrue(array_key_exists(2, $children));
    }

    public function testChildCount() {
        $this->assertEquals('complex', $this->parsed->getName());
        $child = $this->parsed->getChild(0)->getChild(0);
        $this->assertEquals(4, $child->childCount());
        $this->assertEquals(3, $child->childCount("child"));
        $this->assertEquals(0, $this->parsed->getChild(1)->childCount());
    }

    public function testGetAttributeCount() {
        $this->assertEquals('complex', $this->parsed->getName());
        $this->assertEquals(2, $this->parsed->getChild(0)->attributeCount());
        $this->assertEquals(0, $this->parsed->attributeCount());
    }

    public function testHasAttribute() {
        $this->assertEquals('complex', $this->parsed->getName());
        $this->assertTrue($this->parsed->getChild(0)->hasAttribute("id"));
        $this->assertTrue($this->parsed->getChild(0)->hasAttribute("name"));
        $this->assertFalse($this->parsed->getChild(0)->hasAttribute("value"));
    }

    public function testGetChild() {
        $this->assertEquals('complex', $this->parsed->getName());
        $child = $this->parsed->getChild(0);
        $this->assertEquals("object", $child->getName());
        $child = $child->getChild(0);
        $this->assertEquals("childlist", $child->getName());
        $child = $child->getChild(1);
        $this->assertEquals("othername", $child->getName());
    }

    public function testGetChildByName() {
        $this->assertEquals('complex', $this->parsed->getName());
        $child = $this->parsed->getChildByName("object");
        $this->assertEquals("abc123", $child->getAttribute("id"));
        $child = $child->getChildByName("childlist");
        $this->assertEquals("childlist", $child->getName());
        $otherchild = $child->getChildByName("othername");
        $child = $child->getChildByName("child");
        $this->assertEquals("othername", $otherchild->getName());
        $this->assertEquals("1", $child->getAttribute("id"));
        $this->assertEquals("-", $child->getContent());
        $this->assertEquals("Test", $otherchild->getContent());
    }

    public function testGetInvalidAttribute() {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Attribute with key \"invalid\" does not exist.");
        $this->parsed->getAttribute("invalid");
    }

    public function testGetChildOutOfBounds() {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage("Child with index 50 does not exist. Index was out of bounds.");
        $child = $this->parsed->getChildByName('object');
        $child->getChild(50);
    }

    public function testGetChildByInvalidName() {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Child with name "invalid" does not exist.');
        $this->parsed->getChildByName("invalid");
    }
}
