<?php

namespace App\Controller;

use App\Entity\Imagenes;
use App\Form\ImagenesType;
use App\Repository\ImagenesRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class ImagenesController extends AbstractController
{
    /**
     * @Route("/", name="app_imagenes_index", methods={"GET"})
     */
    public function index(ImagenesRepository $imagenesRepository): Response
    {   
        $images= $imagenesRepository->findAll();

        
        
        /*foreach($images as $key=>$value){
            $value->setImage(base64_encode(stream_get_contents($value->getImage())));
         }*/

         $img = array();

           foreach ($images as $key => $entity) {
    
             $abt= $entity->getImage();
              if ($abt != null){

                $img[$key] =base64_encode(stream_get_contents($abt));
              }             
            }
         
            

        return $this->render('imagenes/index.html.twig', [
            'imagenes' => $images,
            'img'=>$img
        ]);
    }

    /**
     * @Route("/new", name="app_imagenes_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ImagenesRepository $imagenesRepository): Response
    {
        $imagene = new Imagenes();
        $form = $this->createForm(ImagenesType::class, $imagene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file=  $form->get('image')->getData();
            if ($file) {
         
                $strm = fopen($file->getRealPath(),'rb');
         
                // Move the file to the directory where brochures are stored
                try {

                  $imagene->setImage(stream_get_contents($strm));

                 
  
                
                } catch (Exception $e) {
                    throw  new \Exception('Error al subir archivos');
                    // ... handle exception if something happens during file upload
                }

                
    
            }
            $imagenesRepository->add($imagene, true);
            return $this->redirectToRoute('app_imagenes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('imagenes/new.html.twig', [
            'imagene' => $imagene,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_imagenes_show", methods={"GET"})
     */
    public function show(Imagenes $imagene): Response
    {
        return $this->render('imagenes/show.html.twig', [
            'imagene' => $imagene,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_imagenes_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Imagenes $imagene, ImagenesRepository $imagenesRepository): Response
    {
        $form = $this->createForm(ImagenesType::class, $imagene);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file=  $form->get('image')->getData();
            if ($file) {
         
                $strm = fopen($file->getRealPath(),'rb');
         
                // Move the file to the directory where brochures are stored
                try {

                  $imagene->setImage(stream_get_contents($strm));

                  
  
                
                } catch (Exception $e) {
                    throw  new \Exception('Error al subir archivos');
                    // ... handle exception if something happens during file upload
                }
                 
                

            }
            $imagenesRepository->add($imagene, true);
            return $this->redirectToRoute('app_imagenes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('imagenes/edit.html.twig', [
            'imagene' => $imagene,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_imagenes_delete", methods={"POST"})
     */
    public function delete(Request $request, Imagenes $imagene, ImagenesRepository $imagenesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$imagene->getId(), $request->request->get('_token'))) {
            $imagenesRepository->remove($imagene, true);
        }

        return $this->redirectToRoute('app_imagenes_index', [], Response::HTTP_SEE_OTHER);
    }
}
