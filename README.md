[![Build Status](https://img.shields.io/travis/jitesoft/php-xml2array/master.svg?label=master)](https://travis-ci.org/jitesoft/php-xml2array)  

[![Build Status](https://img.shields.io/travis/jitesoft/php-xml2array/develop.svg?label=develop)](https://travis-ci.org/jitesoft/php-xml2array)

[![Dependency Status](https://gemnasium.com/badges/github.com/jitesoft/php-xml2array.svg)](https://gemnasium.com/github.com/jitesoft/php-xml2array)

# XML2Struct

XML can be a pain... Sometimes its a lot more handy to work with a tree structure or even json or an array!  
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
$json   = json_encode($out);
// Array!
$array  = $out->toArray();
```

The object returned is a `Jitesoft\XML\Node` structure.  
The structure have the following fields defined:

```
name:       string                  default: ""
content:    string                  default: ""
attributes: array ([key => value])  default: []
children:   array ([Node])          default: []
```

When converting a node to an array or a json string, the properties or keys will be named the same as 
the Nodes fields.
The keys will always be there, even if there is no value from the xml, if no parsed value, it will be the default value stated above..  

## Changes

### 2.0

* Removed `OutType` on parsing. Instead use `Node::toArray` and `json_encode($node)`.
* Removed `Node::toString` override. Dump as json if wanted as json.

## Issues and PR's.

Any issues found should be reported in this repository issue tracker, issues will be fixed when possible.  
Pull requests will be accepted, but please try to follow the general code-style! 

## License

```text
MIT License

Copyright (c) 2017 Jitesoft

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```
