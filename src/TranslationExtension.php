<?php

/**
 * @author Coign <coign.br@gmail.com>
 * @copyright 2018 Coign
 * @package Coign\Twig\Extension
 * 
 */
 
namespace Coign\Slim\Twig\Extension;
use Slim\App;
class TranslationExtension extends \Twig_Extension
{
	
  private $translator = null;
  
  public function getName()
  {
    return 'translate';
  }
  public function getFunctions()
  {
    return array(
      new \Twig_SimpleFunction('translate', array($this, 'translate')),
      new \Twig_SimpleFunction('_', array($this, 'translate')),
	  new \Twig_SimpleFunction('trans', array($this, 'transB')),
	  new \Twig_SimpleFunction('trans_choice', [$this, 'transChoice'])
    );
  }
  public function translate($name, $appName = 'default')
  {   
  global $app;
  
    if (!$app->translator) {
      throw new \Exception('No translator class found.');
    }
    if (!method_exists($app->translator, 'trans')) {
      throw new \Exception('No translate method found in translator class.');
    }    
    return $app->translator->trans($name);
  }
  
  public function transChoice($id, $number, array $parameters = [], $domain = 'messages', $locale = null)
  {
	  global $app;
	  if (!$app->translator) {
      throw new \Exception('No translator class found.');
    }
	if (!method_exists($app->translator, 'trans')) {
      throw new \Exception('No translate method found in translator class.');
    }    
	  
    return $app->translator->transChoice($id, $number, $parameters, $domain, $locale);
  }
  
  public function transB($id, array $parameters = [], $domain = 'messages', $locale = null)
  {
	  global $app;
	  if (!$app->translator) {
      throw new \Exception('No translator class found.');
    }
	if (!method_exists($app->translator, 'trans')) {
      throw new \Exception('No translate method found in translator class.');
    }   
	
    return $app->translator->trans($id, $parameters, $domain, $locale);
  }
}