<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/listBooks', name: 'list_books')]
    public function listBooks(BookRepository $repository)
    {   $books= $repository->findBy(['published'=>False]);
        $unpublished=count($books);
        $books= $repository->findBy(['published'=>True]);
       // $books= $repository->findAll();
        return $this->render("book/listBooks.html.twig"
            ,array('tabBooks'=>$books,'unpublished'=>$unpublished));
    }


    #[Route('/addBook', name: 'addBook')]
    public function addBook(Request $request,ManagerRegistry $managerRegistry)
    {
        $book= new Book();
        $form = $this->createForm(BookType::class,$book);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em= $managerRegistry->getManager();
            //$book->setPublished('true');
            $nbrBooks=$book->getAuthor()->getNbrBooks();
            //var_dump($nbrBooks).die();
            $book->getAuthor()->setNbrBooks($nbrBooks+1);
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute("list_books");
        }
        //     1ère methode
        /*        return $this->render("author/add.html.twig",
                    array('authorForm'=>$form->createView()));*/
//        2ème méthode
        return $this->renderForm("book/addBook.html.twig",
            array('bookForm'=>$form));
    }


    #[Route('/updateBook/{ref}', name: 'update1_book')]
    public function updateBook(ManagerRegistry $managerRegistry, $ref, BookRepository $repository, Request $request)
    {
        $book = $repository->find($ref);
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {// si on clique sur le button submit
            $em = $managerRegistry->getManager();
            $em->flush();
            return $this->redirectToRoute("list_books");
        }

        return $this->renderForm("book/updateBook.html.twig",
            array('bookForm' => $form));
    }


    #[Route('/deleteBook/{ref}', name: 'delete1_book')]
    public function deleteBook($ref, BookRepository $repository, ManagerRegistry $managerRegistry)
    {
        $book = $repository->find($ref);
        $em = $managerRegistry->getManager();
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute("list_books");

    }

    #[Route('/showBook/{ref}', name: 'show_book')]
    public function showBook($ref, BookRepository $repository){
        $book = $repository -> find($ref);
        return $this->render("book/showBook.html.twig",
            array('book'=>$book));
    }
}
