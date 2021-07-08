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
use League\CommonMark\Block\Element\ListBlock;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Block\Renderer\BlockRendererInterface;

final class ListBlockRenderer implements BlockRendererInterface
{
    /**
     * @param ListBlock                $block
     * @param ElementRendererInterface $DWRenderer
     * @param bool                     $inTightList
     *
     * @return string
     */
    public function render(AbstractBlock $block, ElementRendererInterface $DWRenderer, bool $inTightList = false)
    {
        if (!($block instanceof ListBlock)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . \get_class($block));
        }

        $listData = $block->getListData();

        $tag = $listData->type === ListBlock::TYPE_BULLET ? "* " : "- ";

        $attrs = $block->getData('attributes', []);

        if ($listData->start !== null && $listData->start !== 1) {
            $attrs['start'] = (string) $listData->start;
        }

        $result = 
                $DWRenderer->renderBlocks(
                    $block->children(),
                    $block->isTight()
                );

        $result = preg_replace("/\n/", "\n  ", $result); # add two-space indentation
        $result = preg_replace("/\n(\s\s)+\n/", "\n", $result); # remove unwanted newline
        $result = preg_replace("/<li>/", $tag, $result); # add DW list bullet
        return "  " . $result;

    }
}
