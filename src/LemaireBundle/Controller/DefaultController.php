<?php

namespace LemaireBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use LemaireBundle\Entity\Car;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    
    /**
     * @Route("/", name="homepage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
       
        if ($this->container->has('profiler'))
            {
            $this->container->get('profiler')->disable();
            }
        $em = $this->getDoctrine()->getManager();
       
        $cars = $em->getRepository('LemaireBundle:Car')->findBy(array('active' => true), array('date' => 'DESC' ));

//        $marques = $em->getRepository('LemaireBundle:Marque')->findAll();
        $marques = [];
        $energies = $em->getRepository('LemaireBundle:Energie')->findAll();
        $carsFinal = [];
        $photos=[];
        
        $dateToCompare = date('y-m-d g:i:s', strtotime('-22 days'));

        foreach ($cars as $car){

            $dateCarSold = $car->getDateSold();
            $sold = $car->getVendu();
    
            if ($sold === false || ($sold === true && strtotime($dateCarSold->format('y-m-d g:i:s')) > strtotime($dateToCompare))){
                 
                $optionArray = explode(", ",$car->getOptions());
                $car->setOptions($optionArray);
                array_push($carsFinal,$car);
                
                $photo = $em->getRepository('LemaireBundle:Image')->findBy(array('car' => $car->getId()));
                if (isset($photo[0])){
                 array_push($photos, $photo[0]);
                }
                
                $marque =  $car->getModele()->getMarque()->getName();         
                if (!(isset($marques[$marque]))){
                    $marques[$marque] = 0;
                }
                $marques[$marque] = $marques[$marque] + 1;
            }  
        }

        return $this->render('@Lemaire/Default/index.html.twig', array(
            'cars' => $carsFinal,
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

if ($this->container->has('profiler'))
{
    $this->container->get('profiler')->disable();
}
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
    
    
     /**
     * @Route("/admin/typeslbc/{type}/{modele}", name="typeslbc")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getGammeAction($type, $modele)
    {   
        try
            {
                
          
            
        $em = $this->getDoctrine()->getManager();   

        $GAMME_QUERY = 'SELECT m.name FROM modele AS m INNER JOIN car AS c ON m.id = c.modele_id INNER JOIN type as t ON c.type_id = t.id WHERE t.id ='.$type;    

        
        $gamme_statement = $em->getConnection()->prepare($GAMME_QUERY);
        $gamme_statement->execute();
        $gamme_results = $gamme_statement->fetchAll();
        

        $gammes = [];
        
        foreach ($gamme_results as $gamme_results){
            
            $gamme_results['name'] = str_replace(" III", "", $gamme_results['name']);
            $gamme_results['name'] = str_replace(" II", "", $gamme_results['name']);
            $gamme_results['name'] = str_replace(" IV", "", $gamme_results['name']);
            $gamme_results['name'] = str_replace(" PHASE", "", $gamme_results['name']);

            $gamme_results['name'] = ucwords(strtolower($gamme_results['name']));
            
            if (!(in_array($gamme_results['name'], $gammes)) && $gamme_results['name'] != $modele){
                array_push($gammes, $gamme_results['name']);
            }
        }

        if (count($gammes) > 6){
           $gammesFinal = array_slice($gammes, 0, 6);
        }

            
             return new JsonResponse($gammesFinal);
            
            
            }
            catch (ErrorException $e)
            {
                return new JsonResponse($e->getMessage(), $e->getCode());
            }
        }
    
    
    
}
