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
        $userid = $this->getUser()->getId();

        $challenges = $this->getDoctrine()->getRepository('AppBundle:Challenge')->challangerUser($userid);

        return $this->render('challenge/challenges.html.twig', array(
            'challenges' => $challenges
        ));
    }

    /**
     * @Route("/addChallenge")
     */
    public function addChallengeAction(Request $request){
        $userid = $this->getUser()->getId();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ChallengeType::class, null, array(
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

             $time = $formData['Time'];
             $actualDate = date('Y-m-d');
             $endChallengeDate = date('Y-m-d', strtotime($actualDate. ' + '.$time.' days'));
              $end = \DateTime::createFromFormat('Y-m-d', $endChallengeDate);

            $challenge = new Challenge();
            $challenge->setAmount($formData['Amount']);
            $challenge->setExercise($formData['Exercise']);
            $challenge->setTime($end);
             $challenge->setUserId($userid);
            $challenge->setDone(0);

             $em->persist($challenge);
             $em->flush();

            $this->addFlash(
                'success',
                'Dodane'
            );

            return $this->redirectToRoute('app_challenge_index');
        }


        return $this->render('challenge/addChallenge.html.twig', array(
            'form' => $form->createView()
        ));
    }



    /**
     * @Route("/challenges/delete/{id}")
     */
    public function deleteChallengeAction(Request $request, Challenge $challenge)
    {
        $id = $challenge->getId();
        if (!$challenge) {
            throw $this->createNotFoundException('Nie znaleziono rekordu o id: ' . $id);
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($challenge);
            $em->flush();

            $this->addFlash(
                'success',
                'Usunięto rekord'
            );

            return $this->json('Usunieto');
        }
    }

    /**
     * @Route("/checkChallenge/{id}/deleteExercise")
     */
    public function deleteExerciseAction(Request $request, Exercise $exercise)
    {
        $id = $exercise->getId();
        if (!$exercise) {
            throw $this->createNotFoundException('Nie znaleziono rekordu o id: ' . $id);
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($exercise);
            $em->flush();
            $this->addFlash(
                'success',
                'Usunięto rekord'
            );
            return $this->json('Usuniete');
        }
    }



    /**
     * @Route("/checkChallenge/{id}")
     */
    public function checkChallengeAction(Challenge $challenge){
        $repsToDo = $challenge->getAmount();
        $challangeId = $challenge->getId();
        $sum = 0;

        $historyOfChallenge = $this->getDoctrine()->getRepository('AppBundle:Exercise')->historyOfChallenge($challangeId);

        foreach($historyOfChallenge as $reps){
            $rep = $reps->getAmount();
            $sum+=$rep;
        }
        $repsLeft = $repsToDo-$sum;

        if($repsLeft<=0){
            $challenge->setDone(1);
            $em=$this->getDoctrine()->getManager();
            $em->persist($challenge);
            $em->flush();
            $repsLeft=0;
        }

        $endChallengeTime = $challenge->getTime();

        $actualDate = date('Y-m-d H:i:s');

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

        $form = $this->createForm(ExerciseChallengeType::class, null, array(
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $actualDate = date('Y-m-d');
            $date = \DateTime::createFromFormat('Y-m-d', $actualDate);

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