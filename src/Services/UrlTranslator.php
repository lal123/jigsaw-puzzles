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
            //var_dump($request->attributes);die();
            $params = $request->get('_route_params');
            //$params['query'] = $request->get('query');
            $params['_locale'] = $locale;
            $locale_versions[$locale] = ['url'=> $request->getScheme() . '://' . $settings['domain_name'] . $urlGenerator->generate($request->attributes->get('_route'), $params), 'label'=> $settings['label']];
        }
        return $locale_versions;
    }
}