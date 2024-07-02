<?php

declare(strict_types=1);

namespace System\Core\Template;

use System\Core\Helpers\Config;
use System\Core\Session\SessionInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigFactory
{
    public function __construct(
        private readonly string $pathViews,
        private $pathCacheViews,
        private SessionInterface $session
    )
    {

    }

    public function create() :Environment
   {
       $cache = false;
       $debug = false;

       if (Config::get('SWITCH_CACHE')) {
           $cache = BASE_DIR.$this->pathCacheViews;
       }
       if (Config::get('DEBUG_VIEWS')) {
           $debug = true;
       }

       $twigConfig = [
           'debug' => $debug,
           'cache' => $cache
       ];

       $loader = new FilesystemLoader($this->pathViews);
       $twig = new Environment($loader, $twigConfig);

       $twig->addExtension(new DebugExtension());
       $twig->addFunction(new TwigFunction('session', [$this, 'getSession']));

        return  $twig;
   }

   public function getSession(): SessionInterface
   {
        return $this->session;
   }

}