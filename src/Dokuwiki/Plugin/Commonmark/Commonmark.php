<?php

namespace Dokuwiki\Plugin\Commonmark;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Parser\MarkdownParser;
use Dokuwiki\Plugin\Commonmark\Extension\CommonmarkToDokuwikiExtension;
use Dokuwiki\Plugin\Commonmark\Extension\FootnoteToDokuwikiExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use Dokuwiki\Plugin\Commonmark\Extension\TableExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;

class Commonmark {
    public static function RendtoDW($markdown, $frontmatter_tag = 'off'): array {
        // heading info
        $headingInfo = [];

        // create environment
        $environment = self::createDWEnvironment();
        
        // create parser
        $parser = new MarkdownParser($environment);
        // create Dokuwiki Renderer
        $DWRenderer = new DWRenderer($environment);

        # separate frontmatter and main text
        $FMresult = self::ExtractFrontmatter($markdown);
        $frontmatter = $FMresult->getFrontMatter();
        $markdownOnly = $FMresult->getContent();
        $tagStr = ''; # initialize tag string
        //print_r($frontmatter);

        # extract tags only
        if(!empty($frontmatter) && gettype($frontmatter) == "array") { // frontmatter must be array if valid
            if (array_key_exists('tags', $frontmatter)) {
                $tags = $frontmatter['tags'];
                $tagStr = "{{tag>";
                foreach ($tags as $tag) {
                    $tagStr = $tagStr. "\"". $tag. "\" ";
                }
                $tagStr = $tagStr. "}}";
            }
        }

        // pre-processing: convert slash inside wikilink to colon & image wikilinks
        $markdownOnly = self::ParseDokuwikiWikilinks($markdownOnly);
        $document = $parser->parse($markdownOnly);
        $renderResult = $DWRenderer->renderNode($document);
        // debug
        foreach ($document->iterator() as $node) {
            // if(strpos(get_class($node),'Block') == true) {
            //    echo 'Current node: ' . get_class($node) . '(startline: ' . $node->getStartLine() . ', endline: ' . $node->getEndLine() . ") \n";
            // }
            // else {
            //    echo 'Current node: ' . get_class($node) . "\n";
            // }
            if(get_class($node) == 'League\CommonMark\Extension\CommonMark\Node\Block\Heading') {
                $headingInfo[$node->firstChild()->getLiteral()] = array(
                    'level' => $node->getLevel(),
                    'startline' => $node->getStartLine(),
                    'endline' => $node->getEndLine()
                );
            }
        }

        if($frontmatter_tag == 'off') {
            return array('text'=>$renderResult, 'heading'=>$headingInfo);
        } elseif($frontmatter_tag == 'upper') {
            return array('text'=>$tagStr."\n\n".$renderResult, 'heading'=>$headingInfo);
            //return $tagStr."\n\n".$renderResult;
        } else {
            return array('text'=>$renderResult."\n\n".$tagStr, 'heading'=>$headingInfo);
            //return $renderResult."\n\n".$tagStr;
        }
    }

    // Temporary implementation: separate method for frontmatter extraction
    // Since some parsed frontmatter info must be included in main text, it should be merged
    public static function ExtractFrontmatter($markdown) {
        $frontMatterExtension = new FrontMatterExtension();
        $result = $frontMatterExtension->getFrontMatterParser()->parse($markdown);

        return $result;
    }

    // replace slash in MD wikilink to colon to match DW syntax
    public static function ParseDokuwikiWikilinks($text) {
        $pattern = "/(?:\[\[\b|(?!^)\G)[^\/|\]]*\K\/+/";
        $result = preg_replace($pattern, ":", $text);
        $pattern = "/!\[\[(.*)\]\]/";
        $result = preg_replace($pattern, '{{$1}}', $result);
        return $result;
    }

    public static function createDWEnvironment(): Environment {
        $config = [];
        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkToDokuWikiExtension());
        $environment->addExtension(new FootnoteToDokuwikiExtension());
        $environment->addExtension(new StrikethroughExtension());
        $environment->addExtension(new TableExtension());
        $environment->addExtension(new FrontMatterExtension());

        $environment->mergeConfig([
            'html_input' => 'allow',
        ]);

        return $environment;
    }
}

?>