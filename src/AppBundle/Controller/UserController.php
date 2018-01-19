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
 * @ORM\Table(name="user_controller")
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction(){

        return $this->render('user/index.html.twig');
    }

    /**
     * @Route("/findFriends")
     */
    public function findFriendsAction(Request $request){

        $found = null;
        $form = $this->createForm(findFriendsType::class, null, array(
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();


           $found =  $this->getDoctrine()->getRepository('AppBundle:User')->findByUsername($formData['nick']);
        }
        $founded = array();
       /* if(isset($found)){
            foreach($found as $key=>$value){
               $username = $value->getUsername();
               $founded[]=$username;
            }
        }*/

        return $this->render('user/findFriends.html.twig',array(
            'form' => $form->createView(),
            'founded' => $found
        ));
    }

    /**
     * @Route("/addFriend/{id}")
     */
    public function addFriendAction(User $user)
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
     * @Route("/friendList")
     */
    public function friendListAction(){
        $friendsId=array();
        $friends = array();

        $userId = $this->getUser()->getId();
        $friendList = $this->getDoctrine()->getRepository('AppBundle:Relationships')->showFriends($userId);

        if(isset($friendList)) {
            foreach ($friendList as $friend) {
               $friendsId[] = $this->checkUserIdInRealtionAction($friend['userOneId'], $friend['userTwoId']);
            }
        }
        foreach($friendsId as $friendId){
             $oneRecord = $this->getDoctrine()->getRepository('AppBundle:User')->find($friendId);
             if($oneRecord!=null){
                 $friends[]=$oneRecord;
             }
        }

        return $this->render('user/friendList.html.twig', array(
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
