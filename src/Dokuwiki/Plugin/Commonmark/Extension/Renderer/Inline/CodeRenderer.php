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

namespace DokuWiki\Plugin\Commonmark\Extension\Renderer\Inline;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Element\Code;
use League\CommonMark\Util\Xml;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;

final class CodeRenderer implements InlineRendererInterface
{
    /**
     * @param Code                     $inline
     * @param ElementRendererInterface $DWRenderer
     *
     * @return string
     */
    public function render(AbstractInline $inline, ElementRendererInterface $DWRenderer)
    {
        if (!($inline instanceof Code)) {
            throw new \InvalidArgumentException('Incompatible inline type: ' . \get_class($inline));
        }

        # Do not escape code; BELEIVE DOKUWIKI!
        #return "''" . Xml::escape($inline->getContent()) . "''";
        # add %% between inline content to block additional render
        return "''%%" . $inline->getContent() . "%%''";
    }
}
