<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/User for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Swagger\Annotations as SWG;
use Zend\View\Model\JsonModel;
use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractRestfulController;
/**
 *
 * @package
 *
 * @category
 *
 * @subpackage @SWG\Resource(
 *             apiVersion="1.0.0",
 *             basePath="http://crayvit/v1/api",
 *             resourcePath="/user",
 *             description="Operations about users",
 *             produces="['application/json','text/plain','text/html']"
 *             )
 */
class UserController extends AbstractRestfulController
{

    public function indexAction()
    {
        return array();
    }

    /**
     * @SWG\Api(
         * path="/pet/create",
         * @SWG\Operation(
             * method="POST",
             * summary="Create test user",
             * type="User",
             * nickname="createTestUser",
             * @SWG\Parameter(
                 * name="userData",
                 * description="Data of the user to be created",
                 * required=true,
                 * type="User",
                 * format="int64",
                 * paramType="body"
             * ),
             * @SWG\ResponseMessage(code=400, message="Invalid parameters passed"),
             * @SWG\ResponseMessage(code=404, message="User not found")
         * )
     * )
     */
    public function viewAction()
    {
        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $users = $om->getRepository('User\Entity\User')->findAll();
        return new JsonModel($users);
    }
    
    public function addAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $user = new \User\Entity\User();
        if ($this->getRequest()->isPut()) {
            $body = $this->getRequest()->getContent();
            $content = Json::decode($body, Json::TYPE_OBJECT);
            $user->setFirstName($content->firstName);
            $user->setLastName($content->lastName);
            $user->setEmail($content->email);
            $user->setPassword($content->password);
            $em->persist($user); 
            $em->flush();   
        }
        return new JsonModel($user);
    }
    
    public function getAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $id = $this->getEvent()->getRouteMatch()->getParam('id');
    	$query = $em->createQuery('SELECT u FROM User\Entity\User u WHERE u.id = ?1');
    	$query->setParameter(1, $id);
    	//$user = Doctrine_Core::getTable('User')->find($id);
    	$user = $query->getResult();
    	return new JsonModel($user);
    }
    
    public function updateAction($id, $data)
    {
    	$om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	$user = $om->find('User\Entity\User', $id);
    	return new JsonModel($user);
    }
    
    public function deleteAction($id)
    {
    	$om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	$user = $om->find('User\Entity\User', $id);
    	return new JsonModel($user);
    }
}
