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
        
        //Sérialisation des données pour envoi à la vue
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
        
        if ($commande === null){
            
            return $this->redirectToRoute('louvre_billetterie_commande');
        
        } else {
            $commandeId = $commande->getCommandeId();

            $details = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('LouvreBilletterieBundle:Detail')
                            ->findByCommandeId($commandeId);

            //Récupération et sérialisation de la date de commande pour pouvoir calculer l'age du visiteur pour le jour de la visite
            $encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new DateTimeNormalizer('d-m-Y'));

            $serializer = new Serializer($normalizers, $encoders);
            $commandeDate = $commande->getCommandeDate();

            $jsonCommandeDate = $serializer->serialize($commandeDate, 'json');


            //Création du formulaire Global
            $form = $this->get('form.factory')->create(GlobalType::class, $commande);
            $form -> add('valider', SubmitType::class, array(
                            'label' => "Valider la commande"));

            //Récupération de l'id de la commande
            $em = $this->getDoctrine()->getManager();    
            $commande = $em->getRepository('LouvreBilletterieBundle:Commande')->find($commandeId);

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

                //Enregistrement du prix total de la commande
                $commandePrixTotal = $form->get('commandePrixTotal')->getData();
                $commande->setCommandePrixTotal($commandePrixTotal);
                $em->persist($commande);

                //Enregistrement des détails des visiteurs
                //Si retour en arrière depuis la page de paiement suppression des détails déjà enregistrés puis enregistrement des modifications
                if ($details != 0){
                    foreach ($details as $detail){
                    $em->remove($detail);}
                    $em->flush();
                    $details = $form->get('details')->getData();
                    foreach ($details as $detail){
                        $details->add($detail);
                        $detail->setCommandeId($commandeId);
                        $em->persist($detail);
                    }

                } else {
                    $details = $form->get('details')->getData();
                    foreach ($details as $detail){
                        $details->add($detail);
                        $detail->setCommandeId($commandeId);
                        $em->persist($detail);
                    }
                }
                //Mise à jour de la bdd
                $em->flush();

                return $this->redirectToRoute('louvre_billetterie_payment');
            }
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
        
        if ($commande === null){
            return $this->redirectToRoute('louvre_billetterie_commande');
        
        } else {
            $commandeId = $commande->getCommandeId();
            $commandePrixTotal = $commande->getCommandePrixTotal();

            $em = $this->getDoctrine()->getManager();    
            $commande = $em->getRepository('LouvreBilletterieBundle:Commande')->find($commandeId);

            if ($request->isMethod('POST')) {

                //Mise à jour de la bdd avec le mail
                $em = $this->getDoctrine()->getManager();

                $commandeMail = $_POST['cardholder-mail'];
                $commande->setCommandeMail($commandeMail);

                //Création et mise à jour du numéro de commande avec une clé unique de commande (id de la commande+mail)
                $clécommande = $commandeId.$commandeMail;
                $commandeCode = str_split(hash('md5', $clécommande),8)[0];
                $commande->setCommandeCode($commandeCode);

                $em->persist($commande);
                $em->flush();

                \Stripe\Stripe::setApiKey("sk_test_7OPvHSQnlADZ7IaQ19NxHinf");

                //credit card details soumis par le formulaire
                $token = $_POST['stripeToken'];

                // Création d'une charge Stripe
                $charge = \Stripe\Charge::create(array(
                    "amount" => $commandePrixTotal*100, //Montant en centimes
                    "currency" => "eur",
                    "source" => $token,
                    "description" => "Paiement Stripe"
                    ));

                $commande = $em->getRepository('LouvreBilletterieBundle:Commande')->find($commandeId);

                //Récupération des détails correspondants à la commande pour l'envoi de mail de confirmation
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

                return $this->redirectToRoute('louvre_billetterie_confirmation');
            
            } else { 
                $session->getFlashBag()->add('error','Paiement non validé');
            }
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
        
        //Récupération des détails correspondants à la commande pour la confirmation
        $details = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('LouvreBilletterieBundle:Detail')
                        ->findByCommandeId($commandeId);
        
        $session->clear();
        
        return $this->render('LouvreBilletterieBundle:Booking:confirmation.html.twig', array(
            'commande' => $commande,
            'details' => $details,
            ));
    }
    
     public function tarifsAction()
        
    {   
         return $this->render('LouvreBilletterieBundle:Booking:tarifs.html.twig');
    }

    public function mentionsAction()
        
    {   
         return $this->render('LouvreBilletterieBundle:Booking:mentions.html.twig');
    }
    
    public function cgvAction()
        
    {   
         return $this->render('LouvreBilletterieBundle:Booking:cgv.html.twig');
    }
}
