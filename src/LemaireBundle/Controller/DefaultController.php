<?php

namespace LemaireBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use LemaireBundle\Entity\Car;
use LemaireBundle\Controller\CarController;

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
//        $marques = $em->getRepository('LemaireBundle:Marque')->findAll();
        $marques = [];


        $energies = $em->getRepository('LemaireBundle:Energie')->findAll();
        foreach ($cars as $car){
            $marque =  $car->getModele()->getMarque()->getName();
             $marques[$marque] = 0;
        }
        
        
        $photos=[];
        foreach ($cars as $car){
             $photo = $em->getRepository('LemaireBundle:Image')->findBy(array('car' => $car->getId()));
             
             
             if (isset($photo[0])){
             array_push($photos, $photo[0]);
            }
             
             $optionArray = explode(", ",$car->getOptions());
             $car->setOptions($optionArray);
             
            $marque =  $car->getModele()->getMarque()->getName();         

                $marques[$marque] = $marques[$marque] + 1;

           

        

             
//        echo '<pre>';
////        var_dump($cars);
//        var_dump($photos);
//        echo '</pre>';
            
//        $photos[$car->getId()] = $photo;
        }
        
//                     echo '<pre>';
//
//        var_dump($marques);
//        echo '</pre>';
//        
//        die;
// 

        
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
    
                $checkCaptcha = $this->captchaverify($request->get('g-recaptcha-response'));     
                if ($checkCaptcha === true){
                     // Send mail
                        if($this->sendEmail($form->getData())){ 
                            return $this->redirectToRoute('infos', array('envoi' => 'OK', '_fragment' => 'contact'));
                        }else{        
                            return $this->redirectToRoute('infos', array('envoi' => 'NOK', '_fragment' => 'contact'));
                        }                    
                }else{                 
                     return $this->redirectToRoute('infos', array('envoi' => 'NOK', '_fragment' => 'contact'));
                }
            }
        }

        return $this->render('LemaireBundle:Default:infos.html.twig', array(
            'form' => $form->createView(),
            'car' => $car,
        ));
    }

    private function sendEmail($data){
        $myappContactMail = 'site@lemaire-autos.com';
        $myappContactPassword = 'David1973';
        
        $transport = \Swift_SmtpTransport::newInstance('SSL0.OVH.NET', 465,'ssl')
            ->setUsername($myappContactMail)
            ->setPassword($myappContactPassword);

        $mailer = \Swift_Mailer::newInstance($transport);
        
        $message = \Swift_Message::newInstance("Du site lemaire-autos.com : ". $data["subject"])
        ->setFrom(array($myappContactMail => "Message de ".$data["name"]))
        ->setTo(array(
            $myappContactMail => $myappContactMail
        ))
        ->setBody($data["message"]." \n\n Contact Nom :".$data["name"] ." \n Contact Mail :".$data["email"] ." \n Contact Téléphone :".$data["phone"] );
        
        return $mailer->send($message);
    }

    
     /**
     * @Route("/admin", name="admin")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminAction()
    {   
        
//          $carController = new CarController();
//          
//          $carController->indexAction();
        return $this->redirectToRoute('car_index', array());
//        return $this->render('@Lemaire/Default/admin.html.twig', array(  
//        ));
    }
    
    
        # get success response from recaptcha and return it to controller
    function captchaverify($recaptcha){
        
        $params = [
        'secret'    => "6LcESm4UAAAAAB9jS314AaWaedogJMPNMIxFKtLQ",
        'response'  => $recaptcha
        ];

        $url = "https://www.google.com/recaptcha/api/siteverify?" . http_build_query($params);
         if (function_exists('curl_version')) {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Evite les problèmes, si le ser
            $response = curl_exec($curl);
        } else {
            // Si curl n'est pas dispo, un bon vieux file_get_contents
            $response = file_get_contents($url);
        }  

        if (empty($response) || is_null($response)) {
            return false;
        }

        $json = json_decode($response);
        return $json->success;
           
    }
    
}
