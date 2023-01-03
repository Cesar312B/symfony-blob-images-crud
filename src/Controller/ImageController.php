<?php

namespace App\Controller;

use App\Entity\Imagenes;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    /**
     * @Route("/image", name="app_image")
     */
    public function index(): Response
    {
        $em= $this->getDoctrine()->getManager();
        $images= $em->getRepository(Imagenes::class)->findAll();
        foreach($images as $key=>$value){
           $value->setImage(base64_encode(stream_get_contents($value->getImage())));
        }

        return $this->render('image/index.html.twig', [
            'images'=> $images,
        ]);
    }
}
