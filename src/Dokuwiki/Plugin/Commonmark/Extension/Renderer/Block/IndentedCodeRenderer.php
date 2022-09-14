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
use League\CommonMark\Block\Element\IndentedCode;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\Util\Xml;
use League\CommonMark\Block\Renderer\BlockRendererInterface;


final class IndentedCodeRenderer implements BlockRendererInterface
{
    /**
     * @param IndentedCode             $block
     * @param ElementRendererInterface $DWRenderer
     * @param bool                     $inTightList
     *
     * @return string
     */
    public function render(AbstractBlock $block, ElementRendererInterface $DWRenderer, bool $inTightList = false)
    {
        if (!($block instanceof IndentedCode)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . \get_class($block));
        }

        # As in FencedCodeRenderer.php, do not escape code block
        # return "\n  " . Xml::escape($block->getStringContent());
        return "\n  " . preg_replace("/[\n\r]/", "\n  ", $block->getStringContent());
    }
}
