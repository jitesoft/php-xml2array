<?php

// If using composer, its as easy as:
// require_once "vendor/autoload.php";
// Else include the required classes as:
require_once "../src/ParserInterface.php";
require_once "../src/Node.php";
require_once "../src/Parser.php";


$parser = new \Jitesoft\XML\Parser();
$root = null;

// Its always a good idea to make sure that the parser actually manages to parse the
// xml file. If it does not, an exception will be thrown.
try {
    $root = $parser->parse(file_get_contents("Example.xml"));
} catch (Exception $ex) {
    // In this case we just kill the process, if the xml file does not work at this point
    // in the example... Well, then the rest wont do any good either!
    die($ex->getMessage());
}

$asArray = $root->toArray();
$json    = json_encode($root);

// To work through the data, one could for example iterate the children of the root:
// At this point, the root is the <catalog> node in the xml.

for ($i=0;$i<$root->childCount(); $i++) {
    $child = $root->getChild($i);

    $name       = $child->getName();
    $children   = $child->getChildren();
    $attributes = $child->getAttributes();

    // Specific children or attributes can be fetched by name (or in children case, index if really wanted).
    $author = $child->getChildByName("author");
    $id     = $child->getAttribute("id");

    // Or just count them cause its fun!
    $child->attributeCount();
    $child->childCount("author");
}



