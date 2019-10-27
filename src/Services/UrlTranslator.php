<?php
// src/Services/UrlTranslator.php
namespace App\Services;

class UrlTranslator
{
    private $domain_list;

    public function __construct($domain_list) {
        $this->domain_list = $domain_list;
    }

    public function getDomainList() {
        return $this->domain_list;
    }

    public function setDomainList($domain_list) {
        $this->domain_list = $domain_list;
    }

    public function translate($request, $urlGenerator) {
        $locale_versions = [];
        $domain_list = $this->domain_list;
        foreach ($domain_list as $locale => $settings) {
            $locale_versions[$locale] = ['url'=> $request->getScheme() . '://' . $settings['domain_name'] . $urlGenerator->generate($request->attributes->get('_route'), ['_locale' => $locale]), 'label'=> $settings['label']];
        }
        return $locale_versions;
    }
}