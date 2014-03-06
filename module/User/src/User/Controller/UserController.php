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
 *
 * @category
 *
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
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $users = $em->getRepository('User\Entity\User')->findAll();
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
        $user = $query->getResult();
        return new JsonModel($user);
    }

    public function update($id, $data)
    {
        /*
         * $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager'); $user = $em->find('User\Entity\User', $id); return new JsonModel($user);
         */
    }

    public function delete($id)
    {
        $responseArray = null;
        $response = $this->getResponse();
        try {
            $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $user = $em->find('User\Entity\User', $id);
            if ($user == null) {
                $responseArray = array(
                    'status' => '404',
                    'message' => 'User not found!'
                );
                $response->setContent(Json::encode($responseArray));
                return $response;
            }
            $qb = $em->createQueryBuilder();
            $query = $qb->delete('User\Entity\User', 'u')
                ->add('where', 'u.id = :userId')
                ->setParameter('userId', $id)
                ->getQuery();
            $resultArray = $query->getResult();
            if ($resultArray == 1) {
                $responseArray = array(
                    'status' => '200',
                    'message' => 'User successfully removed!'
                );
            } else {
                $responseArray = array(
                    'status' => '500',
                    'message' => 'Some error occured. Please try after sometime.'
                );
            }
            $response->setContent(Json::encode($responseArray));
            return $response;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
