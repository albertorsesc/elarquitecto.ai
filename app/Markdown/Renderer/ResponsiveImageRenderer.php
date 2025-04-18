<?php

namespace App\Markdown\Renderer;

use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

class ResponsiveImageRenderer implements NodeRendererInterface
{
    /**
     * @param  Image  $node
     * @return HtmlElement
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (! ($node instanceof Image)) {
            throw new \InvalidArgumentException('Incompatible node type: '.get_class($node));
        }

        $url = $node->getUrl();
        $alt = $childRenderer->renderNodes($node->children());
        $title = $node->getTitle();

        // Set up image attributes - ensure all values are strings to prevent XML escape errors
        $attributes = [
            'src' => $url ?: '',
            'alt' => $alt ?: '',
            'class' => 'markdown-image',
            'loading' => 'lazy',
        ];

        // Only add the title attribute if it's not empty
        if ($title) {
            $attributes['title'] = $title;
        }

        // Create the wrapper div for proper positioning and sizing
        $imgWrapper = new HtmlElement(
            'div',
            ['class' => 'markdown-image-wrapper']
        );

        // Create the image element with attributes
        $img = new HtmlElement('img', $attributes, '', true);

        // Create a container to center the image and control its max-width
        return new HtmlElement(
            'div',
            ['class' => 'markdown-image-container'],
            $imgWrapper->setContents($img)
        );
    }
}
