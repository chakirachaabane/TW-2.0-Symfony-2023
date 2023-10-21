<?php

namespace App\Controller;

use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;

class AuthorController extends AbstractController
{
    private $authors = array(

        array('id' => 1, 'picture' => '/img/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100),

        array('id' => 2, 'picture' => '/img/William-Shakespeare.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200),

        array('id' => 3, 'picture' => '/img/Taha-Hussein.jpg', 'username' => ' Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),

    );

    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/author/home/{name}', name: 'app_author1')]
    public function show($name): Response
    {
        return $this->render('author/show.html.twig', [
            'controller_name' => 'AuthorController', 'name' => $name
        ]);
    }

    #[Route('/listOfAuthors', name: 'list_author')]
    public function list()
    {

        return $this->render('author/list.html.twig', array('tabAuthors' => $this->authors));
    }


    #[Route('/AuthorDetails/{id}', name: 'app_details')]
    public function auhtorDetails($id)
    {

        foreach ($this->authors as $author) {
            if ($author['id'] == $id) {
                $details = $author;

            }

        }
        return $this->render('author/showAuthor.html.twig', array('authorDetails' => $details));
    }


    #[Route('/authors', name: 'list_authors')]
    public function listAuthors(AuthorRepository $repository)
    {
        $authors = $repository->findAll();
        return $this->render("author/authors.html.twig", array('tabAuthors' => $authors));
    }

    #[Route('/addauthor', name: 'add_author')]
    /* public function addAuthor  (ManagerRegistry $managerRegistry){
          $author= new Author();
          $author->setUsername("Abu l kacem");
          $author->setEmail("AbuLKacem@gmail.com");
          $em= $managerRegistry->getManager();
          $em->persist($author);
          $em->flush();
          return $this->redirectToRoute("list_authors");
      }*/
    #[Route('/add', name: 'add')]
    public function add(Request $request, ManagerRegistry $managerRegistry)
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {// si on clique sur le button submit
            $em = $managerRegistry->getManager();
            $author->setNbrBooks(0);
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute("list_authors");
        }
//     1ère methode
        /*        return $this->render("author/add.html.twig",
                    array('authorForm'=>$form->createView()));*/
//        2ème méthode
        return $this->renderForm("author/add.html.twig",
            array('authorForm' => $form));
    }

    //delete sans formulaire
    #[Route('/delete/{id}', name: 'delete')]
    public function deleteAuthor($id, AuthorRepository $repository, ManagerRegistry $managerRegistry)
    {
        $author = $repository->find($id);
        $em = $managerRegistry->getManager();
        $em->remove($author);
        $em->flush();
        return $this->redirectToRoute("list_authors");

    }
//sans formulaire == avec formulaire


    /*#[Route('/update/{id}',name:'update_author')]
    public function update(ManagerRegistry $managerRegistry,$id,AuthorRepository $repository ){
         $author=$repository->find($id);
         $author->setUsername("Taher L Fezaa");
         $author->setEmail("TaherLFezaa@gmail.com");
         $em= $managerRegistry->getManager();
         $em->flush();
         return $this->redirectToRoute("list_authors");
    }*/

    #[Route('/update1/{id}', name: 'update1_author')]
    public function update1(ManagerRegistry $managerRegistry, $id, AuthorRepository $repository, Request $request)
    {
        $author = $repository->find($id);
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {// si on clique sur le button submit
            $em = $managerRegistry->getManager();
            $em->flush();
            return $this->redirectToRoute("list_authors");
        }

        return $this->renderForm("author/updateAuthor.html.twig",
            array('authorForm' => $form));
    }

    #[Route('/delete1/{id}', name: 'delete1_author')]
    public function deleteAuthor1($id, AuthorRepository $repository, ManagerRegistry $managerRegistry)
    {
        $author = $repository->find($id);
        $em = $managerRegistry->getManager();
        $em->remove($author);
        $em->flush();
        return $this->redirectToRoute("list_authors");

    }

    #[Route('/deleteAuthor/{id}', name: 'delete_author_no_book')]
    public function deleteAuthorsWithoutBooks($id, AuthorRepository $repository, ManagerRegistry $managerRegistry)
    {
        $author = $repository->find($id);
        $em = $managerRegistry->getManager();
        if($author->getNbrBooks()==0){
        $em->remove($author);
        $em->flush();
        }else{
            return new Response("Error,this author has books!!");
        }
        return $this->redirectToRoute("list_authors");

    }
}


