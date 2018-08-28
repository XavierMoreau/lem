<?php

namespace LemaireBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use LemaireBundle\Entity\Car;

class DefaultController extends Controller
{
    
    /**
     * @Route("/", name="homepage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
       
        $cars = $em->getRepository('LemaireBundle:Car')->findBy(array('active' => true), array('date' => 'DESC' ));
        $marques = $em->getRepository('LemaireBundle:Marque')->findAll();
        $energies = $em->getRepository('LemaireBundle:Energie')->findAll();
        
        $photos=[];
        foreach ($cars as $car){
             $photo = $em->getRepository('LemaireBundle:Image')->findBy(array('car' => $car->getId()));
                   
             if (isset($photo[0])){
             array_push($photos, $photo[0]);

             
             $optionArray = explode(", ",$car->getOptions());
             $car->setOptions($optionArray);

             }
//        echo '<pre>';
////        var_dump($cars);
//        var_dump($photos);
//        echo '</pre>';
            
//        $photos[$car->getId()] = $photo;
        }
        

 

        
        return $this->render('@Lemaire/Default/index.html.twig', array(
            'cars' => $cars,
            'photos' => $photos,
            'marques' => $marques,
            'energies' => $energies,
        ));
    }
    
     /**
     * @Route("/infos/{id}", defaults={"id" = null}, name="infos")
     * @return \Symfony\Component\HttpFoundation\Response
     */
//    public function infosAction()
//    {   
//        
//        return $this->render('@Lemaire/Default/infos.html.twig', array(  
//        ));
//    }
//    
    
    public function contactAction(Request $request, Car $car=null)
    {
        // Create the form according to the FormType created previously.
        // And give the proper parameters
        $form = $this->createForm('LemaireBundle\Form\ContactType', null, array(
            // To set the action use $this->generateUrl('route_identifier')
            'action' => $this->generateUrl('contact_form'),
            'method' => 'POST'
        ));

        if ($request->isMethod('POST')) {
            // Refill the fields in case the form is not valid.
            $form->handleRequest($request);

            if($form->isValid()){
                // Send mail
                if($this->sendEmail($form->getData())){

                    // Everything OK, redirect to wherever you want ! :
                    
                    return $this->redirectToRoute('redirect_to_somewhere_now');
                }else{
                    // An error ocurred, handle
                    var_dump("Errooooor :(");
                }
            }
        }

        return $this->render('LemaireBundle:Default:infos.html.twig', array(
            'form' => $form->createView(),
            'car' => $car,
        ));
    }

    private function sendEmail($data){
        $myappContactMail = 'mycontactmail@mymail.com';
        $myappContactPassword = 'yourmailpassword';
        
        // In this case we'll use the ZOHO mail services.
        // If your service is another, then read the following article to know which smpt code to use and which port
        // http://ourcodeworld.com/articles/read/14/swiftmailer-send-mails-from-php-easily-and-effortlessly
        $transport = \Swift_SmtpTransport::newInstance('smtp.zoho.com', 465,'ssl')
            ->setUsername($myappContactMail)
            ->setPassword($myappContactPassword);

        $mailer = \Swift_Mailer::newInstance($transport);
        
        $message = \Swift_Message::newInstance("Our Code World Contact Form ". $data["subject"])
        ->setFrom(array($myappContactMail => "Message by ".$data["name"]))
        ->setTo(array(
            $myappContactMail => $myappContactMail
        ))
        ->setBody($data["message"]."<br>ContactMail :".$data["email"]);
        
        return $mailer->send($message);
    }

    
     /**
     * @Route("/admin", name="admin")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminAction()
    {   
        
        return $this->render('@Lemaire/Default/admin.html.twig', array(  
        ));
    }
    
    
      
    
}
