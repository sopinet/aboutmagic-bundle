Sopinet: Aboutmagic-Bundle
==========================

What it is?
-----------

Integrate easy and fast, about.me profiles in your Symfony2 project

But... what it is?
------------------

Aboutmagic show so the about.me profiles information

![Preview of list](https://github.com/sopinet/aboutmagic-bundle/raw/master/doc/screenshot.png)

Pre-requisites
--------------

- CurlInit: You must have curl_init function enabled in your server


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



