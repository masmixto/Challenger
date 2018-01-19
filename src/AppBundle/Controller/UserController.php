<?php
/**
 * Created by PhpStorm.
 * User: masmiix
 * Date: 09.01.18
 * Time: 11:50
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
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

   /*
    public function addFriendAction(User $user)
    {
        $userId = $user->getId();
        var_dump($userId);
        $em = $this->getDoctrine()->getManager();


        $em->persist($newFriend);
        $em->flush();


        return $this->json('dodano');

    }*/



    /**
     * @Route("/friendList")
     */
    public function friendListAction(){
        $user = $this->getUser();
        $friends = $user->getMyFriends();
        $names = array();
        foreach($friends as $friend){
            $names[] = $friend->getName();
        }

        return $this->render('user/friendList.html.twig', array(
            'names' => $names
        ));
    }

}