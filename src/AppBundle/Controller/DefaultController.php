<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/about")
     */
    public function aboutAction()
    {
        // replace this example code with whatever you need
        return $this->render('default/about.html.twig');
    }

    /**
     * @Route("/contact")
     */
    public function contactAction(Request $request)
    {
        $contact = new Contact();

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            var_dump($formData);
            $contact->setName($formData['name']);
            $contact->setEmail($formData['email']);
            $contact->setSubject($formData['subject']);
            $contact->setMessage($formData['message']);

            $em->persist($contact);
            $em->flush();


            $message = \Swift_Message::newInstance()
                ->setSubject($formData['subject'])
                ->setFrom($this->getParameter('mailer_user'))
                ->setTo($formData['email'])
                ->setBody($this->renderView('email/sendEmail.html.twig',array('name' => $formData['name'])),'text/html');

            $this->get('mailer')->send($message);
        }


        return $this->render('default/contact.html.twig', array(
            'form' => $form->createView()
        ));
    }


}
