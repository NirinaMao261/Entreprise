<?php

namespace App\Controller;

use App\Entity\Employe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\html;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_home')]
    /**
     * @Route("/", name="default_home")
     */
    public function home(EntityManagerInterface $entityManager): Response
    {

            # Cette instruction nous permet de récupérer en BDD toutes lignes de la table "employe" 
            # Ceci est possible grâce au Repositiry, accessible par $entityManager.
            $employes = $entityManager->getRepository(Employe::class)-> findAll();

            # Nous devons maintenant passer la variable $emplyes ( qui contient tout les empliyes de la BDD)
            # à notre vue Twig, pour pouvoir afficher les différentes données.
            return $this->render('default/home.html.twig', [
                'employes' => $employes
            ]);
            }
}

