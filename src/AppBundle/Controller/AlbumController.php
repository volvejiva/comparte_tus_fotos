<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Album;
use AppBundle\Form\AlbumType;

/**
 * Album controller.
 *
 * @Route("/")
 */
class AlbumController extends Controller
{
    /**
     * Creates a new Album entity.
     *
     * @Route("/", name="album_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $album = new Album();
        $form = $this->createForm('AppBundle\Form\AlbumType', $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($album);
            $em->flush();
            
            $filesUtils = $this->get('file.utils');
            if ($filesUtils->uncompress($album) == true) {
              $this->addFlash(
                'success',
                'album.zipOk'
              );                    
            }
            
            $this->addFlash(
                'success',
                'album.creation'
            );
            
            return $this->redirectToRoute('album_show', array(
                'id' => $album->getId(),
                'token' => $album->getToken()
            ));
        }
        
        return $this->render('album/new.html.twig', array(
            'album' => $album,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Album entity.
     *
     * @Route("/album/{id}/{token}", name="album_show")
     * @Method("GET")
     */
    public function showAction(Request $request, $id, $token)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repositorioAlbum = $entityManager->getRepository("AppBundle:Album");
        $album = $repositorioAlbum->findOneBy(array(
                'id' => $id,
                'token' => $token
            )
        );
        
        if ($album == null) {
            die("Este album no existe");
        }
        
        // Obtenemos las imágenes para el Album con el servicio filesUtils
        $filesUtils = $this->get('file.utils');
        $imagenes = $filesUtils->getImagesForAlbum($album);

        return $this->render('album/show.html.twig', array(
            'album' => $album,
            'imagenes' => $imagenes
        ));
    }
    
    /**
     * @Route("/list", name="album_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repositorioAlbum = $entityManager->getRepository("AppBundle:Album");
        $albums = $repositorioAlbum->findAll();
        
        return $this->render('album/list.html.twig', array('albums' => $albums));
    }
}