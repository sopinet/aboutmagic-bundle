<?php

namespace Sopinet\AboutmagicBundle\Twig;

use Symfony\Component\Locale\Locale;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sopinet\Aboutmagic\AboutMagicService;

/**
 * Twig Extension - AboutmagicBundle
 * Has a dependency to the apache intl module
 */
class AboutExtension extends \Twig_Extension implements ContainerAwareInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Class constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container the service container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }
    
    public function getFilters()
    {
        return array(
       		'renderAbout' => new \Twig_Filter_Method($this, 'renderAbout')
        );
    }      
	
	public function renderAbout($nicknames)
	{
		$ops['about_key'] = $this->container->getParameter('sopinet_aboutmagic.key');
		$ops['cache_time'] = 14400;
		$ops['dir'] = $this->container->get('kernel')->getRootDir() . "/../web/profiles/";
		$ops['out_images'] = "profiles/";
		$ops['fx'] = "gray";
		
		$aboutmagicservice = new AboutMagicService();
		return $aboutmagicservice->getProfiles($nicknames, $ops);
	}
    
    public function getName()
    {
        return 'aboutmagic_extension';
    }
}