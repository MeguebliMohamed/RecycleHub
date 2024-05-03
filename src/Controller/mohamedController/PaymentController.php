<?php

namespace App\Controller\mohamedController;
use App\Entity\Offre;
use DateTime;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{

    #[Route('/payment', name: 'payment')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }


    #[Route('/checkout/{id}', name: 'checkout')]
    public function checkout(Offre $offre): Response
    {
        $stripeSK = 'sk_test_51OoTIhIZwMThMQrbvciH7ytahyGar4F8JmIkuuzTFQkxrVfhzO1hWwmyW4ZQErZ72BAUuwxNmIIybQWwCn6xNnzh00sy9kHamj';
        Stripe::setApiKey($stripeSK);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name' => $offre->getDescription(), // Utilisation du nom de l'offre comme nom du produit
                        ],
                        'unit_amount'  => $offre->getMontant() * 100, // Conversion du montant en cents
                    ],
                    'quantity'   => 1,
                ]
            ],
            'mode'                 => 'payment',
            'success_url'          => $this->generateUrl('success_url', ['offre_id' => $offre->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        return $this->redirect($session->url, 303);
    }


    #[Route('/success-url', name: 'success_url')]
    public function successUrl(Request $request): Response
    {
        $offreId = $request->query->get('offre_id');
        $offre = $this->getDoctrine()->getRepository(Offre::class)->find($offreId);

        // Vérifier si l'URL correspond à la page de succès et si l'offre existe

        // Mettre à jour l'état de paiement de l'offre et de l'appel d'offres associé
        $offre->setEtatPayment('paye');
        $offre->setDatePayment(new DateTime());
        $appelOffre = $offre->getAppelOffre();
        $appelOffre->setEtatPayment('paye');

        // Sauvegarder les entités mises à jour dans la base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($offre);
        $entityManager->persist($appelOffre);
        $entityManager->flush();

        // Rediriger vers la page de succès de votre application

        return $this->render('MohamedTemplate/payment/success.html.twig', []);
    }


    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('MohamedTemplate/payment/cancel.html.twig', []);
    }
}