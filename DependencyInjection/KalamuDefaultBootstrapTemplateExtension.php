<?php

/*
 * This file is part of the kalamu/default-bootstrap-template-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\DefaultBootstrapTemplateBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

class KalamuDefaultBootstrapTemplateExtension extends Extension implements PrependExtensionInterface
{

    protected $templates = [
        'page' => [
            'template' => '@KalamuDefaultBootstrapTemplate/Page/page.html.twig'

        ],
        'post' => [
            'template' => '@KalamuDefaultBootstrapTemplate/Post/post.html.twig',
            'template_index' => '@KalamuDefaultBootstrapTemplate/Post/list.html.twig',
        ],
        'term' => [
            'template' => '@KalamuDefaultBootstrapTemplate/Term/term.html.twig',
        ]
    ];

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * Prepend the configuration kalamu cms to set the templates associated to the content types
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container) {
        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration(new Configuration(), $configs);
        $bundles = $container->getParameter('kernel.bundles');

        // KalamuCmsCoreBundle configuration
        if (isset($bundles['KalamuCmsCoreBundle'])) {
            $activatedTypes = [];
            $cmsConfigs = $container->getExtensionConfig('kalamu_cms_core');
            foreach($cmsConfigs as $cmsConfig){
                if(!array_key_exists('types', $cmsConfig)){
                    continue;
                }

                $activatedTypes += $cmsConfig['types'];
            }
            $templateConfig = array_intersect_key($this->templates, $activatedTypes);
            $this->prependConfig($container, 'kalamu_cms_core', ['types' => $templateConfig]);
        }

    }

    /**
     * Update the configuration for a bundle
     *
     * @param ContainerBuilder $container
     * @param string $config_root
     * @param array $config
     */
    protected function prependConfig(ContainerBuilder $container, $config_root, $config){
        foreach ($container->getExtensions() as $name => $extension) {
            if ($name == $config_root) {
                $container->prependExtensionConfig($config_root, $config);
            }
        }
    }
}
