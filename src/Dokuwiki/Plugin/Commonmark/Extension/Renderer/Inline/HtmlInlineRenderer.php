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

use League\CommonMark\Extension\CommonMark\Node\Inline\HtmlInline;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlFilter;
use League\CommonMark\Xml\XmlNodeRendererInterface;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;

final class HtmlInlineRenderer implements NodeRendererInterface, ConfigurationAwareInterface
{
    /**
     * @var ConfigurationInterface
     */
    private ConfigurationInterface $config;

    /**
     * @param HtmlInline               $inline
     * @param ElementRendererInterface $DWRenderer
     *
     * @return string
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        HtmlInline::assertInstanceOf($node);

        if ($this->config->get('html_input') === EnvironmentInterface::HTML_INPUT_STRIP) {
            return '';
        }

        if ($this->config->get('html_input') === EnvironmentInterface::HTML_INPUT_ESCAPE) {
            return \htmlspecialchars($node->getContent(), \ENT_NOQUOTES);
        }

        return $node->getContent();
    }

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->config = $configuration;
    }
}
