[![Build Status](https://img.shields.io/travis/jitesoft/php-xml2array/master.svg?label=master)](https://travis-ci.org/jitesoft/php-datastructures)  

[![Build Status](https://img.shields.io/travis/jitesoft/php-xml2array/develop.svg?label=develop)](https://travis-ci.org/jitesoft/php-datastructures)

[![Dependency Status](https://gemnasium.com/badges/github.com/jitesoft/php-xml2array.svg)](https://gemnasium.com/github.com/jitesoft/php-datastructures)

# XML2Struct

XML can be a pain... Sometimes its a lot more handy to work with a tree structure, json or an array!  
This package was created to ease testing with xml files, so that, instead of going through
a lengthy `DOMElement` test case or even just string-matching the xml right away, one could convert the xml
into a more easily handled structure.  
Thus XML2Struct was born.

## Usage:

Install package through composer:
```
composer require jitesoft/xml2struct
```

The parser is quite simple to use, all that is needed is to create a new parser object and
call its parse method supplying the xml you want parsed as a string:
```php
$parser = new Jitesoft\XML\Parser();
// Node tree!
$out    = $parser->parse(file_get_contents('myxmlfile.xml'));
// Json string!
$out    = $parser->parse(file_get_contents('myxmlfile.xml'), Parser::OUT_TYPE_JSON);
// Array!
$out    = $parser->parse(file_get_contents('myxmlfile.xml'), Parser::OUT_TYPE_ARRAY);
```

The object returned by the default parse out-type is a `Jitesoft\XML\Node` structure.  
The structure have the following fields defined:
```
name:       string                  default: ""
content:    string                  default: ""
attributes: array ([key => value])  default: []
children:   array ([Node])          default: []
```
The structure is also what either of the other out-types will export.
The values will always be set to something, even if there is no value from the xml.  

Its possible to convert the node structure returned from the default out-type, so no need to parse a xml file multiple times
if a new out-type is wanted:

```php
$node->toArray();
$node->toJson();
```

The node also have its `__toString` method to return the node-tree as a json string. 

## Issues and PR's.
Any issues found should be reported in this repository issue tracker, issues will be fixed when possible.  
Pull requests will be accepted, but please try to follow the general code-style! 
