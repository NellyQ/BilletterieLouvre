<?php
// src/Louvre/BilletterieBundle/Controller/BookingController.php

namespace Louvre\BilletterieBundle\Controller;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Louvre\BilletterieBundle\Entity\Commande;
use Louvre\BilletterieBundle\Form\CommandeType;
use Louvre\BilletterieBundle\Entity\Detail;
use Louvre\BilletterieBundle\Form\DetailType;
use Louvre\BilletterieBundle\Form\GlobalType;
use Louvre\BilletterieBundle\Form\StripeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

class BookingController extends Controller
{
    public function commandeAction(Request $request)
        
    {   
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new DateTimeNormalizer('d-m-Y'));

        $serializer = new Serializer($normalizers, $encoders);
        
        //Récupération du nombre total de billet déjà vendu par jour avec la fonction totalNbBilletJour() définit dans CommandeRepository.php
        $arrayTotalBillets = $this->getDoctrine()
                                    ->getManager()
                                    ->getRepository('LouvreBilletterieBundle:Commande')
                                    ->totalNbBilletJour();
        //On sérialise les données
        $jsonTotalBillets = $serializer->serialize($arrayTotalBillets, 'json');
        
        //Ouverture de la session
        $session = $request->getSession();
        
        //Création d'une nouvelle commande
        $commande = new Commande();
        
        //Création du formulaire commande
        $form = $this->get('form.factory')->create(CommandeType::class, $commande);
        
        //Enregistrement des données dans la bdd si le formulaire est valide et renvoi vers la page details
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();
            $session->set('commande', $commande);

            return $this->redirectToRoute('louvre_billetterie_details');
        }

    return $this->render('LouvreBilletterieBundle:Booking:commande.html.twig', array(
        'form' => $form->createView(),
        'arrayTotalBillets'=> $arrayTotalBillets,
        'jsonTotalBillets' => $jsonTotalBillets,
    ));
  }

    public function detailsAction(Request $request)
    {
        $session = $request->getSession();
        $commande = $session->get('commande');
        $commandeId = $commande->getCommandeId();
        $commandeNbBillet = $commande->getCommandeNbBillet();
        
        //Récupération et sérialisation de la date de commande pour pouvoir calculer l'age du visiteur pour le jour de la visite
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new DateTimeNormalizer('d-m-Y'));

        $serializer = new Serializer($normalizers, $encoders);
        $commandeDate = $commande->getCommandeDate();
        
        //On sérialise les données
        $jsonCommandeDate = $serializer->serialize($commandeDate, 'json');
        
        
        //Création du formulaire Global
        $form = $this->get('form.factory')->create(GlobalType::class, $commande);
        $form -> add('valider', SubmitType::class, array(
                        'label' => "Valider la commande"));
        
        $em = $this->getDoctrine()->getManager();    
        $commande = $em->getRepository('LouvreBilletterieBundle:Commande')->find($commandeId);
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            
            //Enregistrement du prix total de la commande
            $commandePrixTotal = $form->get('commandePrixTotal')->getData();
            $commande->setCommandePrixTotal($commandePrixTotal);
            $em->persist($commande);
            
            //Enregistrement des détails des visiteurs
            $details = $form->get('details')->getData(); 
            foreach ($details as $detail){
                $details->add($detail);
                $detail->setCommandeId($commandeId);
                $em->persist($detail);
            }
       
            //Mise à jour de la bdd
            $em->flush();

        return $this->redirectToRoute('louvre_billetterie_payment');
        }
        
    return $this->render('LouvreBilletterieBundle:Booking:details.html.twig', array(
        'form' => $form->createView(),
        'commande'=> $commande,
        'jsonCommandeDate'=> $jsonCommandeDate,
    ));
    }
    
    public function paymentAction(Request $request)
    {
        $session = $request->getSession();
        $commande = $session->get('commande');
        $commandeId = $commande->getCommandeId();
        $commandePrixTotal = $commande->getCommandePrixTotal();
        $em = $this->getDoctrine()->getManager();    
        $commande = $em->getRepository('LouvreBilletterieBundle:Commande')->find($commandeId);
        
        if ($request->isMethod('POST')) {
            
            \Stripe\Stripe::setApiKey("sk_test_7OPvHSQnlADZ7IaQ19NxHinf");

            // Get the credit card details submitted by the form
            $token = $_POST['stripeToken'];

            // Create a charge: this will charge the user's card
            $charge = \Stripe\Charge::create(array(
                "amount" => $commandePrixTotal*100, // Amount in cents
                "currency" => "eur",
                "source" => $token,
                "description" => "Paiement Stripe"
                ));
            
            //Mise à jour de la bdd avec le numéro de commande et le mail
            $em = $this->getDoctrine()->getManager();
            
            $commande->setCommandeCode($token);
            
            $commandeMail = $_POST['cardholder-mail'];
            $commande->setCommandeMail($commandeMail);
            
            $em->persist($commande);
            $em->flush();
            
            $commande = $em->getRepository('LouvreBilletterieBundle:Commande')->find($commandeId);
        
            $details = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('LouvreBilletterieBundle:Detail')
                        ->findByCommandeId($commandeId);
            
            //Envoi de l'email de confirmation
            $message = (new \Swift_Message('Votre commande'))
                ->setFrom(array('nelly.quesada19@gmail.com'=> "Billetterie du Louvre"))
                ->setTo($commandeMail)
                ->setBody(
                    $this->renderView(
                        // app/Resources/views/Booking/email.html.twig
                        'LouvreBilletterieBundle:Booking:email.html.twig',array(
                            'commande' => $commande,
                            'details' => $details,
                    )),
                    'text/html'
                );
            
            $this->get('mailer')->send($message);
            
            //return $this->redirectToRoute('louvre_billetterie_confirmation');
        }
        
      return $this->render('LouvreBilletterieBundle:Booking:payment.html.twig', array(
          'commande' => $commande,
          'commandePrixTotal' => $commandePrixTotal,
          'commandeId' => $commandeId,
      ));
    }
    
    public function confirmationAction(Request $request)
    {
        $session = $request->getSession();
        $commande = $session->get('commande');
        $commandeId = $commande->getCommandeId();
        $em = $this->getDoctrine()->getManager();    
        $commande = $em->getRepository('LouvreBilletterieBundle:Commande')->find($commandeId);
        
        $details = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('LouvreBilletterieBundle:Detail')
                        ->findByCommandeId($commandeId);
        
        return $this->render('LouvreBilletterieBundle:Booking:confirmation.html.twig', array(
            'commande' => $commande,
            'details' => $details,
            ));
    }

}
