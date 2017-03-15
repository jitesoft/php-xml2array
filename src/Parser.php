<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  Parser.php - Part of the php-xml2array project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\XML;

use DOMDocument;
use Exception;
use InvalidArgumentException;

/**
 * Class Parser
 * Implementation of xml parser.
 */
class Parser implements ParserInterface {
    private const OPEN     = "open";
    private const CLOSE    = "close";
    private const COMPLETE = "complete";

    private $current = 0;
    private $values  = [];

    /**
     * @param string $data XML as string.
     * @param string $outType Expected out type (see constants OUT_TYPE_* for valid types).
     * @return string|array|Node
     * @throws InvalidArgumentException
     */
    public static function parseXml(string $data, $outType = self::OUT_TYPE_OBJECT) {
        return (new Parser())->parse($data, $outType);
    }

    /**
     * @param string $data XML as string.
     * @param string $outType Expected out type (see constants OUT_TYPE_* for valid types).
     * @return string|array|Node
     * @throws InvalidArgumentException
     */
    public function parse(string $data, $outType = self::OUT_TYPE_OBJECT) {
        switch ($outType) {
            case self::OUT_TYPE_ARRAY:
            case self::OUT_TYPE_JSON:
            case self::OUT_TYPE_OBJECT:
            break;
            default:
                throw new InvalidArgumentException("The outType \"$outType\" does not exist.");
        }
        $this->innerParse($data);
        // There are three types of tags in the array: open, close and complete.
        // Open is the open tag <>, close is the end tag </> and complete is a tag without children.
        // The data is stored in a single dimensional array, so parsing is pretty straight forward.
        $root = $this->buildNodeTree();
        switch ($outType) {
            case self::OUT_TYPE_ARRAY:
                $root = $root->toArray();
                break;
            case self::OUT_TYPE_JSON:
                $root = $root->toJson();
                break;
            case self::OUT_TYPE_OBJECT:
                break;
            default:
                throw new InvalidArgumentException("The outType \"$outType\" does not exist.");
        }

        return $root;
    }

    /**
     * @param string $data
     * @throws Exception
     */
    private function innerParse(string $data) {
        // Validate the xml file with libxml and the DOMDocument class...
        try {
            (new DOMDocument())->loadXML($data);
        } catch (Exception $ex) {
            throw new Exception("The supplied XML is invalid.",0, $ex);
        }

        $parser = xml_parser_create();
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, $data, $this->values);
        xml_parser_free($parser);
    }

    /**
     * @return Node
     * @throws Exception
     */
    private function buildNodeTree() {
        $root = $this->next();
        if ($root['type'] === self::COMPLETE) {
            return new Node(
                $root['tag']        ?? "",
                $root['value']      ?? "",
                $root['attributes'] ?? []
            );
        }

        if ($root['type'] !== self::OPEN) {
            throw new Exception("Unexpected error");
        }

        $children = [];
        // Iterate the children til next close.

        while ($this->peek()['type'] !== self::CLOSE) {
            // Builds a tree using next node as start.
            array_push($children, $this->buildNodeTree());
        }
        // move the pointer one step forward, cause we don't want to keep the close tag!
        $this->next();
        return new Node(
            $root['tag']        ?? "",
            $root['value']      ?? "",
            $root['attributes'] ?? [],
            $children
        );
    }

    /**
     * Move the pointer to the next value and return it.
     * @return array
     */
    private function next() {
        $out = $this->values[$this->current];
        $this->current++;
        return $out;
    }

    /**
     * Peek on the next value in the array without moving the pointer.
     * @return array
     */
    private function peek() {
        if ($this->current +1 > count($this->values)) {
            return null;
        }
        return $this->values[$this->current];
    }
}
