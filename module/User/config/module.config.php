<?php
namespace User;

return array(
    'controllers' => array(
        'invokables' => array(
            'User\Controller\User' => 'User\Controller\UserController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'view' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'verb' => 'get',
                    'route'    => '/user',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'User\Controller',
                        'controller'    => 'User\Controller\User',
                        'action'        => 'view',
                    ),
                ),
                'may_terminate' => true,
                 'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                     'add' => array(
                     		'type'    => 'Method',
                     		'options' => array(
                     		        'verb' => 'put',
                     				'route'    => '/add',
                     				'defaults' => array(
                     						'__NAMESPACE__' => 'User\Controller',
                     						'controller'    => 'User\Controller\User',
                     						'action'        => 'add',
                     				),
                     		),
                     ),
                     'get' => array(
                     		'type'    => 'Segment',
                     		'options' => array(
                     				'route'    => '/get[/:id]',
                     				'constraints' => array(
                     						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                     						'id'     => '[0-9]*',
                     				),
                     				'defaults' => array(
                     						'__NAMESPACE__' => 'User\Controller',
                     						'controller'    => 'User\Controller\User',
                     						'action'        => 'get',
                     				),
                     		),
                     ),
                     'update' => array(
                     		'type'    => 'Method',
                     		'options' => array(
                     		        'verb' => 'put',
                     				'route'    => '/update[/:id]',
                     				'constraints' => array(
                     						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                     						'id'     => '[0-9]*',
                     				),
                     				'defaults' => array(
                     						'__NAMESPACE__' => 'User\Controller',
                     						'controller'    => 'User\Controller\User',
                     						'action'        => 'update',
                     				),
                     		),
                     ),
                     'delete' => array(
                     		'type'    => 'Method',
                     		'options' => array(
                     				'verb' => 'get,delete',
                     				'route'    => '/delete[/:id]',
                     
                     				'constraints' => array(
                     						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                     						'id'     => '[0-9]*',
                     				),
                     				'defaults' => array(
                     						'__NAMESPACE__' => 'User\Controller',
                     						'controller'    => 'User\Controller\User',
                     						'action'        => 'delete',
                     				),
                     		),
                     ),
                ),   
            ),      
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'User' => __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
    		'driver' => array(
    				'User_driver' => array(
    						'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
    						'cache' => 'array',
    						'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__  . '/Entity')
    				),
    				'orm_default' => array(
    						'drivers' => array(
    								__NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
    						)
    				)
    			)
    		),
    );