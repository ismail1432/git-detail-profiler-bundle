<?php

namespace Eniams\Bundle\GitProfilerBundle\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class GitProfilerExtension
 *
 * @package Eniams\DependencyInjection
 */
class GitProfilerExtension extends Extension
{
    /**
     * Load services configuration
     *
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $projectDir = $container->getParameter('kernel.project_dir');
        if (file_exists($projectDir.'/.git')) {
            $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
            $loader->load('git_profiler.yaml');
        }
    }
}
