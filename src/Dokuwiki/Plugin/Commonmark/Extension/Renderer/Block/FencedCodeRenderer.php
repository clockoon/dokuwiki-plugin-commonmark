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
use League\CommonMark\Util\Xml;
use League\CommonMark\Block\Renderer\BlockRendererInterface;

final class FencedCodeRenderer implements BlockRendererInterface
{
    /**
     * @param FencedCode               $block
     * @param ElementRendererInterface $DWRenderer
     * @param bool                     $inTightList
     *
     * @return string
     */
    public function render(AbstractBlock $block, ElementRendererInterface $DWRenderer, bool $inTightList = false)
    {
        if (!($block instanceof FencedCode)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . \get_class($block));
        }

        $attrs = $block->getData('attributes', []);

        $infoWords = $block->getInfoWords();

        # for default value not specifying infoword
        $entertag = 'code';
        $exittag = 'code';
        
        if (\count($infoWords) !== 0 && \strlen($infoWords[0]) !== 0) {
            switch($infoWords[0]) {
                case 'html':
                    # only supports html block; it is not possible for express html inline span in Commonmark syntax
                    $entertag = 'HTML';
                    $exittag = 'HTML';    
                    break;
                case 'nowiki':
                    # DW <nowiki> syntax
                    $entertag = $infoWords[0];
                    $exittag = $infoWords[0];
                    break;
                case 'dokuwiki':
                    # passing DW codes (e.g. tag, struct, etc.)
                    $entertag = '';
                    $exittag = '';
                    break;
                default:
                    $entertag = 'code ' . $infoWords[0];
                    $exittag = 'code';
            }
        }

        # Do not escape code block; BELIEVE DOKUWIKI!
        #$result = Xml::escape($block->getStringContent());
        $result = $block->getStringContent();
        if ($entertag):
            $result = '<' . $entertag . ">\n" . $result . "</" . $exittag . ">";
        endif;
        return $result;
    }
}
