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

namespace DokuWiki\Plugin\Commonmark\Extension\Renderer\Block;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\FencedCode;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Util\Xml;
use League\CommonMark\Block\Renderer\BlockRendererInterface;

final class FencedCodeRenderer implements BlockRendererInterface
{
    /**
     * @param FencedCode               $block
     * @param ElementRendererInterface $htmlRenderer
     * @param bool                     $inTightList
     *
     * @return HtmlElement
     */
    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, bool $inTightList = false)
    {
        if (!($block instanceof FencedCode)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . \get_class($block));
        }

        $attrs = $block->getData('attributes', []);

        $infoWords = $block->getInfoWords();

        if (\count($infoWords) !== 0 && \strlen($infoWords[0]) !== 0) {
            if ($infoWords[0] == 'html') { 
                # only supports html block; it is not possible for express html inline span in Commonmark syntax
                $entertag = 'HTML';
                $exittag = 'HTML';
            }
            elseif ($infoWords[0] == 'nowiki' || $infoWords[0] == 'dokuwiki' ) { 
                # support DW <nowiki> syntax & passing DW codes (e.g. tag, struct, etc.)
                $entertag = $infoWords[0];
                $exittag = $infoWords[0];
            }
            else {
                $entertag = 'code ' . $infoWords[0];
                $exittag = 'code';
            }
        }

        $result = '<' . $entertag . ">\n" . 
        Xml::escape($block->getStringContent()) . "</" . $exittag . ">";
        return $result;

    }
}
