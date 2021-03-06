<?php
// src/Twig/AppExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('translateURI', [$this, 'translateURI']),
        ];
    }

    public function translateURI($uri, $domain)
    {
        $p = parse_url($uri);
        $h = $p['host'];
        $e = explode('.', $h);
        $e[count($e) - 2] = $domain;

        $uri = $p['scheme'] . '://' . join('.', $e) . $p['path'];

        if(preg_match('/jigsaw-puzzles/', $uri)) {
            $uri = preg_replace('/vos-puzzles/', 'your-puzzles', $uri);
            $uri = preg_replace('/creer-un-puzzle/', 'create-a-puzzle', $uri);
        }
        if(preg_match('/jeu-de-puzzle/', $uri)) {
            $uri = preg_replace('/your-puzzles/', 'vos-puzzles', $uri);
            $uri = preg_replace('/create-a-puzzle/', 'creer-un-puzzle', $uri);
        }

        if(isset($p['query'])) {
            $uri .= '?' . $p['query'];
        }
        if(isset($p['fragment'])) {
            $uri .= '#' . $p['fragment'];
        }
        return $uri;
    }
}