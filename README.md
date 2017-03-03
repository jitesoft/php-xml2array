# XML2Array
Simple package to handle Xml to array conversion for people who don't enjoy working with xml!  


### Usage:
Get the package, create a parser object and parse some xml!  
```php
$parser = new Jitesoft\XML\Parser();
$out    = $parser->parse(file_get_contents('myxmlfile.xml'));
```
