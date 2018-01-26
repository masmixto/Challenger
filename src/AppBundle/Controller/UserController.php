<?php
/**
 * Created by PhpStorm.
 * User: masmiix
 * Date: 09.01.18
 * Time: 11:50
 */

namespace AppBundle\Controller;

use Doctrine\ORM\Mapping as ORM;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

}
