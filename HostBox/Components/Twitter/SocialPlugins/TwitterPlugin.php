<?php

namespace HostBox\Components\Twitter\SocialPlugins;

use HostBox\Components\SocialPluginComponent;

/**
 * Class TwitterPlugin
 * @package HostBox\Components\Twitter\SocialPlugins
 */
abstract class TwitterPlugin extends SocialPluginComponent {

    const
        SIZE_MEDIUM = 'medium',
        SIZE_LARGE = 'large';

    const
        COUNT_NONE = 'none',
        COUNT_HORIZONTAL = 'horizontal',
        COUNT_VERTICAL = 'vertical';

    /**
     * @param Config $config
     */
    public function __construct(Config $config) {
        $this->config = $config;
    }

    /**
     * @param array $settings
     */
    public function render($settings = array()) {
        $this->template->config = $this->config;
        parent::render($settings);
    }

    protected function putDistinctionIntoTemplate() {
        parent::putDistinctionIntoTemplate();
        $reflection = $this->getReflection();
        $href = $reflection->getAnnotation('href');
        if ($href === NULL) {
            throw new \Exception('Annotation @href is not set');
        }

        if ($mResult = preg_match_all('/\$[a-zA-Z]+/', $href, $matches) > 0) {
            foreach ($matches[0] as $match) {
                $rMatch = substr($match, 1, strlen($match) - 1);
                if ($reflection->hasProperty($rMatch) === TRUE && $reflection->getProperty($rMatch)->isPublic()) {
                    $href = str_replace($match, $this->$rMatch, $href);
                }
            }
        }

        if (preg_match_all('/\$[a-zA-Z]+/', $href, $matches) > 0)
            throw new \Exception('Annotation @href is not set correctly');

        $this->template->href = $href;
    }

} 