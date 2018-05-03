<?php
/**
 * Created by PhpStorm.
 * User: masmiix
 * Date: 09.01.18
 * Time: 11:50
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Challenge;
use AppBundle\Entity\Exercise;
use AppBundle\Form\ChallengeType;
use AppBundle\Form\ExerciseChallengeType;
use Doctrine\ORM\Mapping as ORM;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @ORM\Entity
 * @ORM\Table(name="challenge_controller")
 * @Route("/challenge")
 */
class ChallengeController extends Controller
{

    /**
     * @Route("/")
     */
    public function indexAction(){
        $userID = $this->getUser()->getId();
        $challenges = $this->getDoctrine()->getRepository('AppBundle:Challenge')->findBy(array('userid' => $userID));

        return $this->render('challenge/list.html.twig', array(
            'challenges' => $challenges
        ));
    }


    /**
     * @Route("/addChallenge")
     */
    public function addChallengeAction(Request $request){
        $userID = $this->getUser()->getId();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ChallengeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $actualDate = date('d-m-Y');
            $endChallengeDate = date('d-m-Y', strtotime($actualDate. ' + '.$formData['time'].' days'));
            $date = \DateTime::createFromFormat("d-m-Y", $endChallengeDate);

            $challenge = new Challenge();
            $challenge->setAmount($formData['amount']);
            $challenge->setExercise($formData['exercise']);
            $challenge->setTime($date);
            $challenge->setUserId($userID);

            $em->persist($challenge);
            $em->flush();

            return $this->redirectToRoute('app_challenge_index');
        }

        return $this->render('challenge/addChallenge.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/challenges/delete/{id}")
     */
    public function deleteChallengeAction(Challenge $challenge)
    {
        $challengeID = $challenge->getId();
        if (!$challenge) {
            throw $this->createNotFoundException('Cannot find record with id: ' . $challengeID);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($challenge);
        $em->flush();

        return $this->json('Usunieto');
    }


    /**
     * @Route("/checkChallenge/delete/{id}")
     */
    public function deleteExerciseAction(Exercise $exercise)
    {
        $exerciseID = $exercise->getId();
        if (!$exercise) {
            throw $this->createNotFoundException('Cannot find record with id: ' . $exerciseID);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($exercise);
        $em->flush();

        return $this->json('Usuniete');
    }


    /**
     * @Route("/checkChallenge/{id}")
     */
    public function checkChallengeAction(Challenge $challenge){
        $repsToDo = $challenge->getAmount();
        $challangeId = $challenge->getId();
        $sum = 0; //initialize
        $em = $this->getDoctrine()->getManager();

        $historyOfChallenge = $this->getDoctrine()->getRepository('AppBundle:Exercise')->findBy(array('id'=>$challangeId));

        foreach($historyOfChallenge as $reps){
            $rep = $reps->getAmount();
            $sum += $rep;
        }
        $repsLeft = $repsToDo-$sum;

        if($repsLeft <= 0){
            $challenge->setDone(1);
            $em->persist($challenge);
            $em->flush();
            $repsLeft=0;
        }

        $endChallengeTime = $challenge->getTime();

        $actualDate = date('Y-m-d');

        if($endChallengeTime < $actualDate){
            $challenge->setDone(1);
        }

        return $this->render('challenge/checkChallenge.html.twig', array(
            'challenge' => $challenge,
            'historyOfChallenge' => $historyOfChallenge,
            'repsToDo' => $repsToDo,
            'repsLeft' => $repsLeft
        ));
    }


    /**
     * @Route("/checkChallenge/{id}/add")
     */
    public function addToChallengeAction(Request $request, Challenge $challenge){
        $challengeId = $challenge->getId();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ExerciseChallengeType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $actualDate = date("d-m-Y");
            $date = \DateTime::createFromFormat("d-m-Y", $actualDate);

            $exercise = new Exercise();
            $exercise->setAmount($formData['Amount']);
            $exercise->setDate($date);
            $exercise->setExerciseId($challengeId);

            $em->persist($exercise);
            $em->flush();

            return $this->redirectToRoute('app_challenge_checkchallenge',  array('id' => $challengeId));
        }


        return $this->render('challenge/addChallenge.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
