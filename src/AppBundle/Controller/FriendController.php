<?php
/**
 * Created by PhpStorm.
 * User: masmiix
 * Date: 09.01.18
 * Time: 11:50
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Relationships;
use AppBundle\Form\findFriendsType;
use Doctrine\ORM\Mapping as ORM;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @ORM\Entity
 * @ORM\Table(name="friend_controller")
 * @Route("/friend")
 */
class FriendController extends Controller
{
    /**
     * @Route("/friendList")
     */
    public function friendListAction(){
        $friendsId=array();
        $friends = array();

        $userId = $this->getUser()->getId();
        $friendList = $this->getDoctrine()->getRepository('AppBundle:Relationships')->showFriends($userId,$status=1);

        if(isset($friendList)) {
            foreach ($friendList as $friend) {
                $friendsId[] = $this->checkUserIdInRealtionAction($friend['userOneId'], $friend['userTwoId']);
            }

            foreach($friendsId as $friendId){
                $oneRecord = $this->getDoctrine()->getRepository('AppBundle:User')->find($friendId);
                if($oneRecord!=null){
                    $friends[]=$oneRecord;
                }
            }
        }


        return $this->render('friendsManagment/friendList.html.twig', array(
            'friends' => $friends
        ));
    }



    /**
     * @Route("/find")
     */
    public function findAction(Request $request){

        $found = null;
        $form = $this->createForm(findFriendsType::class, null, array(
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

           $found =  $this->getDoctrine()->getRepository('AppBundle:User')->findByUsername($formData['nick']);
        }


        return $this->render('friendsManagment/findFriends.html.twig',array(
            'form' => $form->createView(),
            'founded' => $found
        ));
    }


    /**
     * @Route("/add/{id}")
     */
    public function addAction(User $user)
    {
        $friendId = $user->getId();
        $userId = $this->getUser()->getId();

       $users = $this->compareId($userId, $friendId);

        $em = $this->getDoctrine()->getManager();
        $relation = new Relationships();
        $relation->setUserOneId($users[0]);
        $relation->setUserTwoId($users[1]);
        $relation->setActionUserId($userId);

        $em->persist($relation);
        $em->flush();
        return $this->json('dodano');
    }


    /**
     * @Route("/accept/{id}")
     */
    public function acceptAction(User $user)
    {
        $friendId = $user->getId();
        $userId = $this->getUser()->getId();

        $users = $this->compareId($userId, $friendId);

        $relation = $this->getDoctrine()->getRepository('AppBundle:Relationships')->findOneBy(array('userOneId'=>$users[0], 'userTwoId'=>$users[1]));

        $relation->setStatus(1);

        $em = $this->getDoctrine()->getManager();
        $em->persist($relation);
        $em->flush();
        return $this->json('akceptowano');
    }





    /**
     * @Route("/requests")
     */
    public function requestsAction(){
        $userId = $this->getUser()->getId();
        $friendList = $this->getDoctrine()->getRepository('AppBundle:Relationships')->friendsRequests($userId, $status=0);
        $friends = null;
        $friendsId =null;


        if($friendList) {
            foreach ($friendList as $friend) {
                $friendsId[] = $this->checkUserIdInRealtionAction($friend['userOneId'], $friend['userTwoId']);
            }
            foreach($friendsId as $friendId){
                $oneRecord = $this->getDoctrine()->getRepository('AppBundle:User')->find($friendId);
                if($oneRecord!=null){
                    $friends[]=$oneRecord;
                }
            }
        }


        return $this->render('friendsManagment/friendsRequest.html.twig', array(
            'friends' => $friends
        ));
    }



    /**
     * @Route("/delete/{id}")
     */
    public function deleteAction(Request $request, User $user)
    {
        $userId = $user->getId();
        $user = $this->getUser()->getId();

        $users = $this->compareId($userId, $user);

        $relation = $this->getDoctrine()->getRepository('AppBundle:Relationships')->findOneBy(array('userOneId'=>$users[0], 'userTwoId'=>$users[1]));


        if (!$userId) {
            throw $this->createNotFoundException('Nie znaleziono relacji o id: ' . $userId);
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($relation);
            $em->flush();

            $this->addFlash(
                'success',
                'Usunięto rekord'
            );

            return $this->json('Usunieto');
        }
    }


    /**
     * @Route("/block/{id}")
     */
    public function blockAction(User $user)
    {
        $friendId = $user->getId();
        $userId = $this->getUser()->getId();

        $users = $this->compareId($userId, $friendId);

        $relation = $this->getDoctrine()->getRepository('AppBundle:Relationships')->findOneBy(array('userOneId'=>$users[0], 'userTwoId'=>$users[1]));

        $relation->setStatus(3);

        $em = $this->getDoctrine()->getManager();
        $em->persist($relation);
        $em->flush();
        return $this->json('Zablokowany');
    }

    /**
     * @Route("/blockedList")
     */
    public function blockedListAction(){
        $friendsId=array();
        $friends = array();

        $userId = $this->getUser()->getId();
        $friendList = $this->getDoctrine()->getRepository('AppBundle:Relationships')->showFriends($userId,$status=1);

        if(isset($friendList)) {
            foreach ($friendList as $friend) {
                $friendsId[] = $this->checkUserIdInRealtionAction($friend['userOneId'], $friend['userTwoId']);
            }

            foreach($friendsId as $friendId){
                $oneRecord = $this->getDoctrine()->getRepository('AppBundle:User')->find($friendId);
                if($oneRecord!=null){
                    $friends[]=$oneRecord;
                }
            }
        }


        return $this->render('friendsManagment/friendList.html.twig', array(
            'friends' => $friends
        ));
    }






        //HELPER METHODS


    /**
     * compare ids
     * check for less value to add to database (always less as user one id)
     *
     * $result[0] - lower value
     * $result[1] - bigger value
     *
     */
    public function compareId($idOne, $idTwo){
        $result = array();
        if($idOne<$idTwo){
            $result[]=$idOne;
            $result[]=$idTwo;
        }
        else{
            $result[]=$idTwo;
            $result[]=$idOne;
        }
        return $result;
    }

    /**
     * Check if user is first or second in realtion
     */
    public function checkUserIdInRealtionAction($userOneId, $userTwoId){
        $userId = $this->getUser()->getId();
        if($userId==$userOneId){
            $result = $userTwoId;
        }
        else{
            $result = $userOneId;
        }
        return $result;
    }
}
