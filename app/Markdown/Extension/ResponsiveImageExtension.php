<?php

namespace App\Markdown\Extension;

use App\Markdown\Renderer\ResponsiveImageRenderer;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\ExtensionInterface;

class ResponsiveImageExtension implements ExtensionInterface
{
    /**
     * Register the extension with the Environment
     */
    public function register(EnvironmentBuilderInterface $environment): void
    {
        // Override the default image renderer with our custom renderer
        $environment->addRenderer(Image::class, new ResponsiveImageRenderer, 10);
    }
}
