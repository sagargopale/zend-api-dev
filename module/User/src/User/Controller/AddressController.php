<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Swagger\Annotations as SWG;
use Zend\View\Model\JsonModel;
use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * AddressController
 *
 * @author
 *
 * @version
 *
 */
class AddressController extends AbstractActionController
{

    /**
     * @SWG\Api(
         * path="/address/create",
         * @SWG\Operation(
             * method="POST",
             * summary="Create test address",
             * type="Address",
             * nickname="createTestAddress",
             * @SWG\Parameter(
                 * name="",
                 * description="Data of the address to be created",
                 * required=true,
                 * type="Address",
                 * format="int64",
                 * paramType="body"
             * ),
             * @SWG\ResponseMessage(code=400, message="Invalid parameters passed"),
             * @SWG\ResponseMessage(code=404, message="Address not found")
         * )
     * )
     */
    public function getList()
    {
        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $users = $om->getRepository('User\Entity\Address')->findAll();
        return new JsonModel($addresses);
    }
    
    /**
     * @SWG\Api(
     * path="/address/create",
     * @SWG\Operation(
     * method="POST",
     * summary="Create address for user",
     * type="Address",
     * nickname="createAddress",
     * @SWG\Parameter(
     * name="userData",
     * description="Data of the user to be created",
     * required=true,
     * type="Address",
     * format="int64",
     * paramType="body"
     * ),
     * @SWG\ResponseMessage(code=400, message="Invalid parameters passed"),
     * @SWG\ResponseMessage(code=404, message="User not found")
     * )
     * )
     */
    public function create($data)
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $address = new \User\Entity\Address();
        if ($this->getRequest()->isPost()) {
            $address->setData($data);
            $em->persist($address); 
            $em->flush();
        }
        $response = $this->getResponse()->setContent(Json::encode($address));
        return $response;
    }
    
    public function get($id)
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	$query = $em->createQuery('SELECT u FROM User\Entity\Address u WHERE u.id = ?1');
    	$query->setParameter(1, $id);
    	//$user = Doctrine_Core::getTable('User')->find($id);
    	$address = $query->getResult();
    	return new JsonModel($address);
    }
    
    public function update($id, $data)
    {
        $response = $this->getResponse();
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager'); 
        $address = $em->find('User\Entity\Address', $id);
        if ($address == null) {
        	$responseArray = array(
        			'status' => '404',
        			'message' => 'Address not found!'
        	);
        	$response->setContent(Json::encode($responseArray));
        	return $response;
        }
        $address->setData($data);
        $address = $em->merge($address);
        $em->flush(); 
        $response = $this->getResponse()->setContent(Json::encode($address));
        return $response;
    }
    
    public function delete($id)
    {
        $responseArray = null;
        $response = $this->getResponse();
        try {
            $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $user = $em->find('User\Entity\Address', $id);
            if ($user == null) {
                $responseArray = array(
                    'status' => '404',
                    'message' => 'Address not found!'
                );
                $response->setContent(Json::encode($responseArray));
                return $response;
            }
            $qb = $em->createQueryBuilder();
            $query = $qb->delete('User\Entity\Address', 'u')
                ->add('where', 'u.id = :addressId')
                ->setParameter('addressId', $id)
                ->getQuery();
            $resultArray = $query->getResult();
            if ($resultArray == 1) {
                $responseArray = array(
                    'status' => '200',
                    'message' => 'Address successfully removed!'
                );
            } else {
                $responseArray = array(
                    'status' => '500',
                    'message' => 'Some error occured. Please try after Some time.'
                );
            }
            $response->setContent(Json::encode($responseArray));
            return $response;
        } catch (\Exception $e) {
            echo $e->getMessage();
            $responseArray = array(
            		'status' => '500',
            		'message' => 'Some error occured. Please try after sometime.'
            );
            $response->setContent(Json::encode($responseArray));
            return $response;
        }
    }
}

