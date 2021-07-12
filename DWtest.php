<?php

require_once __DIR__.'/src/bootstrap.php';

use Dokuwiki\Plugin\Commonmark\Commonmark;

//$environment = Environment::createCommonMarkEnvironment();

//$parser = new DocParser($environment);
//$htmlRenderer = new HtmlRenderer($environment);

$test1 = '# Hello World!
> Blockquote Test!
> test continues
>
> TEST!
>> Indented test';

$test2 = '## List test
### Unordered List
- item 1
- item 2
    - item 2.1
- item 3

### Ordered List
1. item 1
2. item 2
    1. item 2.1
3. item 3';

$test3 = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque a iaculis augue. Donec condimentum velit elit, et suscipit sem mattis ac. Duis consequat, velit a lobortis tempor, lorem elit accumsan sapien, sed consectetur mauris neque non erat. Aliquam erat volutpat. Nam posuere et sapien eu lobortis. Praesent fringilla ipsum non velit vulputate, ac pulvinar velit ultrices. Etiam neque massa, venenatis in placerat id, iaculis eu turpis. Sed interdum gravida odio quis porttitor. Nunc vestibulum facilisis ultrices. Ut ultricies, tortor a bibendum sodales, diam mi commodo nibh, non tincidunt nibh erat eget leo. Proin ac lorem eget libero semper consectetur. Phasellus bibendum neque erat, ac egestas nunc varius at. Integer eu ante tristique, semper erat eget, faucibus eros. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam imperdiet sollicitudin urna non maximus. Pellentesque tortor erat, pulvinar in mauris non, luctus ullamcorper nibh.

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque a iaculis augue. Donec condimentum velit elit, et suscipit sem mattis ac. Duis consequat, velit a lobortis tempor, lorem elit accumsan sapien, sed consectetur mauris neque non erat. Aliquam erat volutpat. Nam posuere et sapien eu lobortis. Praesent fringilla ipsum non velit vulputate, ac pulvinar velit ultrices. 
Etiam neque massa, venenatis in placerat id, iaculis eu turpis. Sed interdum gravida odio quis porttitor. Nunc vestibulum facilisis ultrices. Ut ultricies, tortor a bibendum sodales, diam mi commodo nibh, non tincidunt nibh erat eget leo. Proin ac lorem eget libero semper consectetur. Phasellus bibendum neque erat, ac egestas nunc varius at. Integer eu ante tristique, semper erat eget, faucibus eros. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam imperdiet sollicitudin urna non maximus. Pellentesque tortor erat, pulvinar in mauris non, luctus ullamcorper nibh.';

$test4 = '```ruby
def foo(x)
  return 3
end
```

    foo
bar
***
```html
<html>
<head>
dddd
</head>
</html>
```';

$test5 = '`foo`

*ITALIC* **BOLD** ***BOLDITALIC***  
HARD break [yahoo](yahoo.com) <p>hello</p> <a>hello</a> ![](test.jpg)';

$test6 = 'hello, its footnote and [link] test [^1] [^2].

[link]: google.com
[nolink]: facebook.com
[^1]: footnote.com
[^2]: secondfootnote.com
[^3]: this is an anonymous footnote.';

$test = $test6;
echo $test . "\n\n=========================\n\n";
echo Commonmark::RendtoDW($test);

?>