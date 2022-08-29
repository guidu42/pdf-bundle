<?php

namespace Drosalys\PdfBundle\Twig\Runtime;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\WebpackEncoreBundle\Asset\TagRenderer;
use Twig\Extension\RuntimeExtensionInterface;

class CssInlinerRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private TagRenderer $tagRenderer,
        private RequestStack $request,
        private Http $httpClient,
    )
    {
    }

    public function foo(string $body, string $entryName, string $packageName = null, string $entrypointName = '_default', array $attributes = [])
    {
        $linkTags = $this->tagRenderer->renderWebpackLinkTags($entryName, $packageName, $entrypointName, $attributes);
        $hrefArray = $this->getHrefFromLinkTag($linkTags);

        $absoluteHrefArray = $this->generateAbsoluteUrl($hrefArray);

        $this->httpClient->request()->get($array);
        // get content


        $contentArray = [];

        /**
         * @see \Twig\Extra\CssInliner\CssInlinerExtension
         */
        return $inliner->convert($body, implode("\n", $contentArray));
    }

    private function generateAbsoluteUrl(array $hrefArray): array
    {
        $baseUrl = "https://127.0.0.1:8003";
        $newHrefArray = [];
        foreach ($hrefArray as $href){
            if(!str_contains($href, 'http')){
                 $newHrefArray[] = $baseUrl . $href;
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