Dokuwiki Commonmark Plugin
===========================

## Description
This is another plugin for parsing Commonmark / Markdown document in Dokuwiki.

While there are many Markdown plugins (for example, [https://www.dokuwiki.org/plugin:markdowku](markdownu), [mdpage](https://www.dokuwiki.org/plugin:mdpage)) available, this plugin processes Markdown text in different approach:

1. check that Markdown indicator ('\<!DOCTYPE markdown\>') is included on the document
2. if exists, parses entire document and renders to DW syntax
3. After pre-rendering, passes the result to DW parser and process as usual

## Compatibility
Commonmark plugin aims for complete compatiblity of Markdown in Dokuwiki. Most Markdown syntax have corresponding DW syntax, so it will work without problem; but in some cases, Markdown syntax do not matches DW specification one-by-one, or vice versa. Here is a list of known ambiguities between Commonmark and Dokuwiki, and its implements in the plugin:

- Since DW do not parses raw HTML without `htmlok` config, [https://spec.commonmark.org/0.30/#html-blocks](HTML blocks) is passed.
- When adding `html` as info string in [https://spec.commonmark.org/0.30/#fenced-code-blocks](Fenced code blocks), it parse to DW's [https://www.dokuwiki.org/wiki:syntax#embedding_html_and_php](\<HTML\>) block; In case of `nowiki`, `<nowiki>` syntax will be parsed; if `dokuwiki`, raw DW code will be passed.

Commonmark plugin conflits with other markdown-related plugins, including Mdpage.

## Todo
- add footnote support
- add table support
- add strikethrough