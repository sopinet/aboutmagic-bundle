<?php

namespace Sopinet\AboutmagicBundle\Twig;

use Symfony\Component\Locale\Locale;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
    
    private function getArray($file) {
    	return json_decode($this->getFile($file), true);
    }
    
    // Get file
    private function getFile($file) {
    	$string = file_get_contents($file);
    	return $string;
    }
    
    private function post_to_url($url, $data) {
    	$fields = '';
    	foreach($data as $key => $value) {
    		$fields .= $key . '=' . $value . '&';
    	}
    	rtrim($fields, '&');
    	$post = curl_init();
    	curl_setopt($post, CURLOPT_URL, $url);
    	curl_setopt($post, CURLOPT_POST, count($data));
    	curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
    	curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
    	$result = curl_exec($post);
    	curl_close($post);
    
    	return $result;
    }
    
    private function processProfile($profile, $ops) {
    	$url_data = "https://api.about.me/api/v2/json/user/view/".$profile;
    	$dir = $this->container->get('kernel')->getRootDir() . "/../web/profiles/";
    	if (!file_exists($dir)) mkdir($dir);
    	$file = $dir . md5($url_data);
    
    	if (!file_exists($file) || (time() - filemtime($file) > ($ops['cache_time']  + rand(0,1000)))) {
    		$data = array(
    				"extended" => "true",
    				"client_id" => $ops['about_key']
    		);
    
    		$ret = $this->post_to_url($url_data, $data);
    		$fp = fopen($file, 'w');
    		fwrite($fp, $ret);
    		fclose($fp);
    	}
    	return $this->getArray($file);
    }
    
    function saveFileURL($file, $url) {
    	// TODO: Se puede activar... file_put_contents($file, file_get_contents($url));
    	$ch = curl_init($url);
    	$fp = fopen($file, 'wb');
    	curl_setopt($ch, CURLOPT_FILE, $fp);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	curl_exec($ch);
    	curl_close($ch);
    	fclose($fp);
    }
    
    function proccessIMG($url, $ops) {
    	// ORIGINAL:			return $url;
    
    	// TODO: Hacer configurable desde fuente
    
    	$dir = $this->get('kernel')->getRootDir() . "/../web/profiles/";
    	if (!file_exists($dir)) mkdir($dir);
    	$file = $dir . md5($url) . ".jpg";
    	if (!file_exists($file) || (time() - filemtime($file) > ($ops['cache_time']  + rand(0,1000)))) {
    		$this->saveFileURL($file, $url);
    
    		// WEB INTERESANTE: http://www.rpublica.net/imagemagick/artisticas.html#sepia-tone
    
    		$file_efx = $dir . md5($url). "_ex.jpg";
    		/* pencil  $command_thumb = "convert -define jpeg:size=300x300 ".$file." -thumbnail 600x400^ -colorspace Gray -negate -edge 1 -negate -blur 0x.5 -gravity center -extent 600x400 ".$file_efx;
    			// sepia:   $command_thumb = "convert -define jpeg:size=300x300 ".$file." -monochrome -thumbnail 600x400^ -sepia-tone 80% -gravity center -extent 600x400 ".$file_efx;
    		// azul + sepia: $command_thumb = "convert -define jpeg:size=300x300 ".$file." -thumbnail 300x220^ -sepia-tone 70% -fill blue -tint 80% -gravity center -extent 300x220 ".$file_efx;
    		// azul: $command_thumb = "convert -define jpeg:size=300x300 ".$file." -thumbnail 300x220^ -fill blue -tint 60% -gravity center -extent 300x220 ".$file_efx;
    		/* sketch: $command_thumb = "convert -define jpeg:size=300x300 ".$file." -thumbnail 600x400^ -sketch 0x20+120 -gravity center -extent 600x400 ".$file_efx;
    		// solarize: $command_thumb = "convert -define jpeg:size=300x300 ".$file." -thumbnail 300x220^ -solarize 55 -gravity center -extent 300x220 ".$file_efx;
    		*
    		*/
    		$command_thumb = "convert -define jpeg:size=600x600 ".$file." -thumbnail 600x400^ -colorspace gray -gravity center -extent 600x400 ".$file_efx;
    		/*echo $command_thumb;
    			exit();*/
    		exec($command_thumb, $output);
    	}
    	$ret = "profiles/".md5($url). "_ex.jpg";
    	return $ret;
    }
    
    public function getAvatar($profile, $ops) {
    	if ($profile['avatar'] == "") $url = $profile["thumbnail_291x187"];
    	else $url = $profile["avatar"];
    	return $this->proccessIMG($url, $ops);
    }    
	
	public function renderAbout($nicknames)
	{
		//echo $this->container->parameters['sopinet_aboutmagic.key'];
		//exit();
		$ops['cache_time'] = 3600;
		$profiles = explode(",",$nicknames);
		$i = 0;
		foreach($profiles as $pro) {
			$profiles_data[$i] = $this->processProfile($pro, $ops);
			$profiles_data[$i]['avatarOK'] = $this->getAvatar($profiles_data[$i], $ops);
			$i++;
		}
		return $profiles_data;		
		/*
		$em = $this->container->get('doctrine')->getManager();
		$reR = $em->getRepository('QuestformeBaseBundle:Rules');
		$rule = $reR->findOneBy(array('answer' => $answer, 'suggest' => $suggest));
		
		if ($rule == null) return "";
		else return $rule->getPower();
		*/
	}
    
    public function getName()
    {
        return 'aboutmagic_extension';
    }
}