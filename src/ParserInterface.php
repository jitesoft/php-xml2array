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
    /**
     * @param string $data XML as string.
     * @return string|array|Node
     * @throws InvalidArgumentException
     */
    public function parse(string $data);
}
