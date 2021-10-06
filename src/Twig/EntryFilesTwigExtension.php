<?php

namespace Bechir\ViteBundle\Twig;

use Psr\Container\ContainerInterface;
use Symfony\WebpackEncoreBundle\Asset\TagRenderer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EntryFilesTwigExtension extends AbstractExtension
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('vite_entry_script_tags', [$this, 'renderViteScriptTags'], ['is_safe' => ['html']]),
            new TwigFunction('vite_entry_link_tags', [$this, 'renderViteLinkTags'], ['is_safe' => ['html']]),
        ];
    }

    public function renderViteScriptTags(string $entry, string $entrypointName = '_default'): string
    {
        return $this->getTagRenderer()->renderWebpackScriptTags($entry, null, $entrypointName);
    }

    public function renderViteLinkTags(string $entry, string $entrypointName = '_default'): string
    {
        return $this->getTagRenderer()->renderWebpackLinkTags($entry, null, $entrypointName);
    }

    private function getTagRenderer(): TagRenderer
    {
        return $this->container->get('webpack_encore.tag_renderer');
    }
}
