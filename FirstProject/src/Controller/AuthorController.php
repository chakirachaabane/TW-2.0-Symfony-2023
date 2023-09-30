<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
   private $authors = array(

array('id' => 1, 'picture' => '/img/Victor-Hugo.jpg', 'username' => 'Victor Hugo','email'=>'victor.hugo@gmail.com', 'nb_books' => 100),

array ('id' => 2, 'picture' => '/img/William-Shakespeare.jpg', 'username' =>'William Shakespeare','email'=>'william.shakespeare@gmail.com','nb_books' => 200),

array('id' => 3, 'picture' => '/img/Taha-Hussein.jpg', 'username' => ' Taha Hussein','email'=>'taha.hussein@gmail.com','nb_books'=> 300),

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
            'controller_name' => 'AuthorController', 'name'=> $name
        ]);
    }

    #[Route('/listOfAuthors', name: 'list_author')]
    public function list(){

        return $this->render('author/list.html.twig', array('tabAuthors'=>$this->authors));
    }


    #[Route('/AuthorDetails/{id}', name: 'app_details')]
   public function auhtorDetails($id){

    foreach ($this->authors as $author ){
        if ($author['id'] == $id ){
            $details= $author;

        }

    }
        return $this->render('author/showAuthor.html.twig', array('authorDetails'=>$details));
    }

}
