<?php
/**
* This file is part of SopinetAboutmagicBundle.
*
* (c) 2013 by Fernando Hidalgo - Sopinet
*/

namespace Sopinet\AboutmagicBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
* SopinetBootstrapExtendExtension
*
* @codeCoverageIgnore
*/
class SopinetAboutmagicExtension extends Extension implements PrependExtensionInterface
{
    /**
* {@inheritDoc}
*/
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);
		$loader = new Loader\YamlFileLoader(
			$container,
			new FileLocator(__DIR__.'/../Resources/config')
		);
		$loader->load('services.yml');
    }
    
    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
    	$bundles = $container->getParameter('kernel.bundles');
    
    	$configs = $container->getExtensionConfig($this->getAlias());
    	$config = $this->processConfiguration(new Configuration(), $configs);
    	
    	// Add anything?
    }    
    
    public function getAlias()
    {
        return 'sopinet_aboutmagic';
    }
}