<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Categoria;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Categorium controller.
 *
 *
 */
class CategoriaController extends Controller
{
    /**
     * Lists all categorium entities.
     *
     * @Route("usuario/categoria/lista", name="categoria_lista")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $ajax = "";
        if($request->isXmlHttpRequest()){
            $ajax = 1;
        }else{
            $ajax=0;
        }
        $em = $this->getDoctrine()->getManager();

        $usuario = $this->getUser(); 
        $roles = $usuario->getRoles();

        $categorias = $em->getRepository('AppBundle:Categoria')->findAll();

        return $this->render('categoria/listaCategoria.html.twig', array(
            'categorias' => $categorias,
            'rol' => $roles[0],
            'ajax' => $ajax
        ));
    }

    /**
     * Creates a new categoria entity.
     *
     * @Route("/new", name="categoria_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        
        $categoria = new Categoria();
        $form = $this->createForm('AppBundle\Form\CategoriaType', $categoria);
        $form->handleRequest($request);

            if($request->isXmlHttpRequest()){
                
                $status = "";
                if ($form->isSubmitted() && $form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($categoria);
                    $em->flush();
                    $status = 200;
                    return new JsonResponse(array('status' => $status, 'categoriaRegistrado'=> $categoria->getId()));
                    
                }else if($form->isSubmitted() && !$form->isValid()){
 
                    $status=400;
                    return new JsonResponse(array('status'=>$status, "errores" => $form->getErrors()));
                }
            } 
       
        return $this->render('categoria/new.html.twig', array(
            'categoria' => $categoria,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a categorium entity.
     *
     * @Route("/{id}", name="categoria_show")
     * @Method("GET")
     */
    public function showAction(Categoria $categoria)
    {
        $deleteForm = $this->createDeleteForm($categoria);

        return $this->render('categoria/show.html.twig', array(
            'categoria' => $categoria,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing categorium entity.
     *
     * @Route("/{id}/edit", name="categoria_editar")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Categoria $categoria)
    {
        $editForm = $this->createForm('AppBundle\Form\CategoriaType', $categoria);
        $editForm->handleRequest($request);

        if($request->isXmlHttpRequest()){
                
            $status = "";
            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                
                $status = 200;
                return new JsonResponse(array('status' => $status, 'categoriaRegistrado'=> $categoria->getId()));
                
            }else if($editForm->isSubmitted() && !$editForm->isValid()){

                $status=400;
                return new JsonResponse(array('status'=>$status, "errores" => $editForm->getErrors()));
            }
        } 

        return $this->render('categoria/edit.html.twig', array(
            'categorias' => $categoria,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a categoria entity.
     *
     * @Route("/{id}/eliminar", name="categoria_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Categoria $categoria)
    {

        if($request->isXmlHttpRequest()){
            try {
                $em = $this->getDoctrine()->getManager();
                $em->remove($categoria);
                $em->flush();
                 
                 return new JsonResponse(array("status" => "200"));

            } catch (\Exception $e){
                return new JsonResponse(array("status" => "400"));

            }

        }
    }

    /**
     * Creates a form to delete a categorium entity.
     *
     * @param Categoria $categorium The categorium entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Categoria $categorium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('categoria_delete', array('id' => $categorium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
