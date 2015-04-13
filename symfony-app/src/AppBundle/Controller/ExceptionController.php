<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use \Symfony\Bundle\TwigBundle\Controller\ExceptionController as BaseExceptionController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

/**
 * Class ExceptionController
 * Controller for custom error pages.
 *
 * @package AppBundle\Controller
 */
class ExceptionController extends BaseExceptionController
{
    public function findTemplate(Request $request, $format, $code, $showException)
    {
        if ($code != 404) {
            return parent::findTemplate($request, $format, $code, $showException);
        }



        return new TemplateReference('AppBundle', 'Exception', 'error404', 'html', 'twig');
    }
}
