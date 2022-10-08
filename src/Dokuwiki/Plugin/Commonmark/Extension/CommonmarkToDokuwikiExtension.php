<?php 
/*
 * This file is part of the clockoon/dokuwiki-commonmark-plugin package.
 *
 * (c) Sungbin Jeon <clockoon@gmail.com>
 *
 * Original code based on the followings:
 * - CommonMark JS reference parser (https://bitly.com/commonmark-js) (c) John MacFarlane
 * - league/commonmark (https://github.com/thephpleague/commonmark) (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DokuWiki\Plugin\Commonmark\Extension;

use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Block as BlockElement;
use League\CommonMark\Extension\CommonMark\Parser\Block as BlockParser;
use Dokuwiki\Plugin\Commonmark\Extension\Renderer\Block as BlockRenderer;
use League\CommonMark\Extension\CommonMark\Node\Inline as InlineElement;
use League\CommonMark\Extension\CommonMark\Parser\Inline as InlineParser;
use Dokuwiki\Plugin\Commonmark\Extension\Renderer\Inline as InlineRenderer;
use League\CommonMark\Util\ConfigurationInterface;
use League\CommonMark\Delimiter\Processor\EmphasisDelimiterProcessor;

final class CommonMarkToDokuWikiExtension implements ExtensionInterface {
    public function register(EnvironmentBuilderInterface $environment): void {
        $environment
            ->addBlockStartParser(new BlockParser\BlockQuoteStartParser(),      70)
            ->addBlockStartParser(new BlockParser\HeadingStartParser(),      60)
            ->addBlockStartParser(new BlockParser\FencedCodeStartParser(),      50)
            //->addBlockStartParser(new BlockParser\HtmlBlockParser(),       40) # No raw HTML processing on Commonmarkside
            //->addBlockStartParser(new BlockParser\SetExtHeadingParser(),   30)
            ->addBlockStartParser(new BlockParser\ThematicBreakStartParser(),   20)
            ->addBlockStartParser(new BlockParser\ListBlockStartParser(),            10)
            ->addBlockStartParser(new BlockParser\IndentedCodeStartParser(),  -100)
            //->addBlockStartParser(new BlockParser\LazyParagraphParser(), -200)

            //->addInlineParser(new InlineParser\NewlineParser(),     200)
            ->addInlineParser(new InlineParser\BacktickParser(),    150)
            ->addInlineParser(new InlineParser\EscapableParser(),    80)
            ->addInlineParser(new InlineParser\EntityParser(),       70)
            ->addInlineParser(new InlineParser\AutolinkParser(),     50)
            ->addInlineParser(new InlineParser\HtmlInlineParser(),   40)
            ->addInlineParser(new InlineParser\CloseBracketParser(), 30)
            ->addInlineParser(new InlineParser\OpenBracketParser(),  20)
            ->addInlineParser(new InlineParser\BangParser(),         10)

            ->addRenderer(BlockElement\BlockQuote::class,    new BlockRenderer\BlockQuoteRenderer(),    0)
            ->addRenderer(BlockElement\Document::class,      new BlockRenderer\DocumentRenderer(),      0)
            ->addRenderer(BlockElement\FencedCode::class,    new BlockRenderer\FencedCodeRenderer(),    0)
            ->addRenderer(BlockElement\Heading::class,       new BlockRenderer\HeadingRenderer(),       0)
            //->addRenderer(BlockElement\HtmlBlock::class,     new BlockRenderer\HtmlBlockRenderer(),     0) # No raw HTML processing on Commonmarkside
            ->addRenderer(BlockElement\IndentedCode::class,  new BlockRenderer\IndentedCodeRenderer(),  0)
            ->addRenderer(BlockElement\ListBlock::class,     new BlockRenderer\ListBlockRenderer(),     0)
            ->addRenderer(BlockElement\ListItem::class,      new BlockRenderer\ListItemRenderer(),      0)
            ->addRenderer(BlockElement\Paragraph::class,     new BlockRenderer\ParagraphRenderer(),     0)
            ->addRenderer(BlockElement\ThematicBreak::class, new BlockRenderer\ThematicBreakRenderer(), 0)

            ->addRenderer(InlineElement\Code::class,       new InlineRenderer\CodeRenderer(),       0)
            ->addRenderer(InlineElement\Emphasis::class,   new InlineRenderer\EmphasisRenderer(),   0)
            ->addRenderer(InlineElement\HtmlInline::class, new InlineRenderer\HtmlInlineRenderer(), 0)
            ->addRenderer(InlineElement\Image::class,      new InlineRenderer\ImageRenderer(),      0)
            ->addRenderer(InlineElement\Link::class,       new InlineRenderer\LinkRenderer(),       0)
            ->addRenderer(InlineElement\Newline::class,    new InlineRenderer\NewlineRenderer(),    0)
            ->addRenderer(InlineElement\Strong::class,     new InlineRenderer\StrongRenderer(),     0)
            ->addRenderer(InlineElement\Text::class,       new InlineRenderer\TextRenderer(),       0)
        ;

        $deprecatedUseAsterisk = $environment->getConfig('use_asterisk', ConfigurationInterface::MISSING);
        if ($deprecatedUseAsterisk !== ConfigurationInterface::MISSING) {
            @\trigger_error('The "use_asterisk" configuration option is deprecated in league/commonmark 1.6 and will be replaced with "commonmark > use_asterisk" in 2.0', \E_USER_DEPRECATED);
        } else {
            $deprecatedUseAsterisk = true;
        }

        if ($environment->getConfig('commonmark/use_asterisk', $deprecatedUseAsterisk)) {
            $environment->addDelimiterProcessor(new EmphasisDelimiterProcessor('*'));
        }

        $deprecatedUseUnderscore = $environment->getConfig('use_underscore', ConfigurationInterface::MISSING);
        if ($deprecatedUseUnderscore !== ConfigurationInterface::MISSING) {
            @\trigger_error('The "use_underscore" configuration option is deprecated in league/commonmark 1.6 and will be replaced with "commonmark > use_underscore" in 2.0', \E_USER_DEPRECATED);
        } else {
            $deprecatedUseUnderscore = true;
        }

        if ($environment->getConfig('commonmark/use_underscore', $deprecatedUseUnderscore)) {
            $environment->addDelimiterProcessor(new EmphasisDelimiterProcessor('_'));
        }
    }    
}
