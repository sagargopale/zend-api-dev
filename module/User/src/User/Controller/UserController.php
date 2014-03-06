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
    public function getList()
    {
        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $users = $om->getRepository('User\Entity\User')->findAll();
        return new JsonModel($users);
    }
    
    public function create($data)
    {
         $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $user = new \User\Entity\User();
        if ($this->getRequest()->isPost()) {
            $user->setData($data);
            $em->persist($user); 
            $em->flush();
        }
        $response = $this->getResponse()->setContent(Json::encode($user));
        return $response;
    }
    
    public function get($id)
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	$query = $em->createQuery('SELECT u FROM User\Entity\User u WHERE u.id = ?1');
    	$query->setParameter(1, $id);
    	//$user = Doctrine_Core::getTable('User')->find($id);
    	$user = $query->getResult();
    	return new JsonModel($user);
    }
    
    public function update($id, $data)
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');        
        $userData = $em->find('User\Model\User', $id);
        if ($this->getRequest()->isPut()) {
        	$userData->setData($data);
        	$em->persist($user);
        	$em->flush();
        }
        $userData = $em->merge($userData);
        $em->flush();
        
        return new JsonModel(array(
        		'data' => $album->getId(),
        ));
    }
    
    public function delete($id)
    {
        echo $id;
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $query = $em->createQueryBuilder();
        $query->delete("User");
        $query->andWhere($query->expr()->eq("id", $id));
        $em->flush();
        $response = $this->getResponse()->setContent("user deleted");
        return $response;
    }
}
