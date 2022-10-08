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

use League\CommonMark\Extension\CommonMark\Node\Block\ListItem;
use League\CommonMark\Extension\TaskList\TaskListItemMarker;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Xml\XmlNodeRendererInterface;

final class ListItemRenderer implements NodeRendererInterface
{
    /**
     * @param ListItem                 $block
     * @param ElementRendererInterface $DWRenderer
     * @param bool                     $inTightList
     *
     * @return string
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
    {
        ListItem::assertInstanceOf($node);

        $result = $DWRenderer->renderNodes($block->children(), $inTightList);
        if (\substr($result, 0, 1) === '<' && !$this->startsTaskListItem($block)) {
            $result = "\n" . $result;
        }
        if (\substr($result, -1, 1) === '>') {
            $result .= "\n";
        }

        $result = preg_replace('/\n\n/', "\n", $result); # remove unwanted newline for DW

        return "<li>" . $result;
    }

    private function startsTaskListItem(Node $node): bool
    {
        $firstChild = $node->firstChild();

        return $firstChild instanceof Paragraph && $firstChild->firstChild() instanceof TaskListItemMarker;
    }
}
