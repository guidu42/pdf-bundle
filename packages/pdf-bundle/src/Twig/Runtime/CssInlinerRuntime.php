<?php

namespace Drosalys\PdfBundle\Twig\Runtime;

use Symfony\WebpackEncoreBundle\Asset\TagRenderer;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use Twig\Extension\RuntimeExtensionInterface;

class CssInlinerRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private TagRenderer $tagRenderer,
        private string $assetOutPutPath
    )
    {}

    public function getCssInline(string $body, array $entryPoints): string
    {
        $linkTags = [];
        /** @var \EntryPoint $entryPoint */
        foreach ($entryPoints as $entryPoint) {
            $linkTags[] = $this->tagRenderer->renderWebpackLinkTags($entryPoint->getName(), $entryPoint->getPackageName(), $entryPoint->getEntrypointName(), $entryPoint->getAttributes());
        }
        $hrefArray = $this->getHrefFromLinkTag($linkTags);

        $absoluteHrefArray = $this->generateAbsoluteUrl($hrefArray);

        static $inliner;
        if (null === $inliner) {
            $inliner = new CssToInlineStyles();
        }

        $contents = '';
        foreach ($absoluteHrefArray as $item) {
            $contents .= file_get_contents($item);
        }

        return $inliner->convert($body, $contents);
    }

    private function generateAbsoluteUrl(array $hrefArray): array
    {
        $newHrefArray = [];
        foreach ($hrefArray as $href){
            if(!str_contains($href, 'http')){
                 $newHrefArray[] = $this->assetOutPutPath . $href;
            }else{
                $newHrefArray[] = $href;
            }
        }
        return $newHrefArray;
    }

    private function getHrefFromLinkTag(String|array $tags): array
    {
        $arrayHref = [];
        $pattern = '/<link.*(?:href="(.*))"/';
        if(gettype($tags) === 'string'){
            preg_match_all($pattern, $tags, $matches);
            $arrayHref[] = $matches[1][0] ?? null;
        }else{
            foreach ($tags as $tag) {
                preg_match_all($pattern, $tag, $matches);
                $arrayHref[] = $matches[1][0] ?? null;
            }
        }
        return array_filter($arrayHref);
    }
}
