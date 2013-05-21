Sopinet: Aboutmagic-Bundle
==========================

What is it?
-----------

Integrate easy and fast, about.me profiles in your Symfony2 project


Installation via composer
-------------------------

    {
       "require": {
            "sopinet/aboutmagic-bundle": "dev-master"
        }
    }

Add in AppKernel the bundle

    new Sopinet\AboutmagicBundle\SopinetAboutmagicBundle()

Configuration
-------------

You must configure aboutkey in Symfony2 configuration file:

		sopinet_aboutmagic:
	    key: "YourKEYToken"

Usage
-----

You can use in your twig it:

	{% include "SopinetAboutmagicBundle:Bootstrap:simple.html.twig" with {profiles:'hidabe', size:'3'} %}



