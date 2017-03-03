<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  ParserInterface.php - Part of the php-xml2array project.

  © - Jitesoft 2017
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
namespace Jitesoft\XML;

use InvalidArgumentException;

/**
 * Interface ParserInterface
 * @package Jitesoft\XMLs
 */
interface ParserInterface
{
    /** A tree structure with Node objects where the Root is the returned Node. */
    public const OUT_TYPE_OBJECT = "object";
    /** A json object of the whole xml tree. */
    public const OUT_TYPE_JSON   = "json";
    /** A tree structure using arrays instead of Nodes. */
    public const OUT_TYPE_ARRAY  = "array";

    /**
     * @param string $data XML as string.
     * @param string $outType Expected out type (see constants OUT_TYPE_* for valid types).
     * @return string|array|Node
     * @throws InvalidArgumentException
     */
    public function parse(string $data, $outType = ParserInterface::OUT_TYPE_OBJECT);
}
