<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/User for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace User;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), 0);
        $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, array($this, 'onRenderError'), 0);
    }
    
    public function onDispatchError($e)
    {
    	return $this->getJsonModelError($e);
    }
    
    public function onRenderError($e)
    {
    	return $this->getJsonModelError($e);
    }
    
    public function getJsonModelError($e)
    {
    	$error = $e->getError();
    	if (!$error) {
    		return;
    	}
    
    	$response = $e->getResponse();
    	$exception = $e->getParam('exception');
    	$exceptionJson = array();
    	if ($exception) {
    		$exceptionJson = array(
    				'class' => get_class($exception),
    				'file' => $exception->getFile(),
    				'line' => $exception->getLine(),
    				'message' => $exception->getMessage(),
    				'stacktrace' => $exception->getTraceAsString()
    		);
    	}
    
    	$errorJson = array(
    			'message'   => 'An error occurred during execution; please try again later.',
    			'error'     => $error,
    			'exception' => $exceptionJson,
    	);
    	if ($error == 'error-router-no-match') {
    		$errorJson['message'] = 'Resource not found.';
    	}
    
    	$model = new JsonModel(array('errors' => array($errorJson)));
    
    	$e->setResult($model);
    
    	return $model;
    }
}
