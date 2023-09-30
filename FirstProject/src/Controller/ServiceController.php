<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\HomeController;

class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service1')] 
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController', 
        ]);
    }

    #[Route('/services/{name}', name: 'ServicePage')]
    public function showService1($name) //Question  2/3
    {
        return new Response ("Service: " .$name);
    }
    
    #[Route('/service/{name}', name: 'app_service')] //Question 4
    public function showService($name): Response
    {
        return $this->render('service/showService.html.twig', [
            'controller_name' => 'ServiceController', 'name'=>$name 
        ]);
    }
    
    #[Route('/home1', name: 'app_service2')] //Question 5
    public function goToIndex(): Response
    {
      $home = new HomeController();
      return $home->index();
    }
    
   

}
