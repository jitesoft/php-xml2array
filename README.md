# XML2Struct
Simple package to handle Xml - to tree structure, array or json - conversion for people who don't enjoy working with xml!  


### Usage:
Get the package, create a parser object and parse some xml!  
```php
$parser = new Jitesoft\XML\Parser();
// Node tree!
$out    = $parser->parse(file_get_contents('myxmlfile.xml'));
// Json string!
$out    = $parser->parse(file_get_contents('myxmlfile.xml'), Parser::OUT_TYPE_JSON);
// Array!
$out    = $parser->parse(file_get_contents('myxmlfile.xml'), Parser::OUT_TYPE_ARRAY);
```
