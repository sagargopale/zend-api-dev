<?php
namespace User;

return array(
    'controllers' => array(
        'invokables' => array(
            'User\Controller\User' => 'User\Controller\UserController',
            'User\Controller\Address' => 'User\Controller\AddressController', 
        ),
    ),
    'router' => array(
        'routes' => array(
        'view' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/user[/:id]',
                    'constraints' => array(
                    		'id'     => '[0-9]*',
                    ),
                    'defaults' => array(
                        'controller'    => 'User\Controller\User',
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