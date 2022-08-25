<?php

namespace App\Controller;

use DateTime;
use App\Entity\Employe;
use App\Form\EmployeFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeController extends AbstractController
{
    /*
    -------1 _ Action
    Lorsque vous créer une foncton dans un controller, cela devient une "action" 
    commençant toujours par un verbe (sauf 'home').La convention de nommage est le camelCase
    -------2 _ Injection de dépendance
    Dans les parenthèses d'une fonction("action") vous allez, peut être, avoir besoin d'outils (objet). 
    Pour vous en servir ,en Symfony, on injectera des dépendances. Cela revient à les définir comme 'paramètres' .
    --------3_ Route
    La route depuis PHP8, peut s'ecrire sous forme d'Attribut, cela permet de dissocier des annotations.
    Cela se traduit par une syntaxe différente. Une Route prendra TOUJOURS 3 arguments
        *a - Une URI, qui est un bout d'URL
        *b - Une 'name', qui permet de nommer la route pour s'en servir plus tard
        *c - Une méthode HTTP, qui autorise telle ou telle requête HTTP. Question de sécurité. 
        !!!TOUTES VOS ROUTES DOIVENT ETRE COLLEES A VOTRE FONCTION
    */

    #[Route('/ajouter-un-employe', name: 'create_employe', methods: ['GET', 'POST'])] 
    public function createEmploye(Request $request, EntityManagerInterface $entityManager): Response
    {
            // ----------------------------------- 1ere Méthode : GET --------------------------------- //


        #Instanciation d'un objet de type Employe
        $employe = new Employe();

        #Nous créons une variable *form qui continedra le formulaire créé par la méthode createForm()
        # avec la méthode d'auto-hydratation se fait concrétement par l'ajout d'un second argument
        # dans la méthode createForm(). On passera $employe en argument
        $form = $this->createForm(EmployeFormType::class, $employe);

        #Pour que le mécanisme de base de Symfony soit respecté, on devra manipuler la requête
            # avec la méthode handleRequest() et l'objet $request
        $form->handleRequest($request);

        // ----------------------------------- 2ème  Méthode : POST --------------------------------- //
        if($form->isSubmitted() && $form->isValid()) {
           
            # Nous devons renseigner manuellement une valeur pour la propriété createdAt
           # car cette valeur ne peut pas être "null" et n'est pas setter dans la formulaire.
            $employe->setCreatedAt(new DateTime());
            
            # Nous insérons en BDD grâce à notre $entityManager et la méthode persist()
            
            $entityManager->persist($employe);

            # Nousdevrons "vider" (trad de flush) l'entityManager pour reèllement ajouter une ligne en BDD
            $entityManager->flush();

            # Pour terminer , nous devons rediriger l'utilisateur sur une page HTML
            # Nous utilisons la méthode redirectToRoute() pour faire la redirection.
            return $this->redirectToRoute('default_home');
            // dd($employe);
        }
            
                    // ----------------------------------- 1ere Méthode : GET --------------------------------- //

      
        #On peut directement return pour rendre la vue (par la page HTML) du formulaire
        return $this->render('form/employe.html.twig', [
            'form_employe' => $form->createView()
        ]);
    } // end function create

    #[Route('/modifier-un-employe/{id}', name: 'update_employe', methods: ['GET', 'POST'])]
    public function updateEmploye(Employe $employe, Request $request, EntityManager $entityManager) :Response{
    {
        $form = $this->createForm(EmployeFormType ::class, $employe)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid) {
                $entitymanager->persist($employe);
                $entityManager->flush();

                return $this->redirectToRoute('default_home');
        }  

        return $this->render('form/employe.html.twig', [
            'form_employe'=> $form->createView(),
            'employe' => $employe
        ]); 
    }


} // end class
}