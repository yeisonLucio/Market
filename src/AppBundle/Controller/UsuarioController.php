<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Usuario controller.
 *
 * 
 */
class UsuarioController extends Controller
{
    /**
     * Lists all usuario entities.
     *
     * @Route("usuario/lista", name="usuario_lista")
     * @Method({"GET", "POST"})
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

        $usuarios = $em->getRepository('AppBundle:Usuario')->findAll();

        return $this->render('usuario/listaUsuario.html.twig', array(
            'usuarios' => $usuarios,
            'rol' => $roles[0],
            'ajax' => $ajax
        ));
    }

     /**
     * Lists all usuario entities.
     *
     * @Route("usuario/index", name="index_usuario")
     * @Method("GET")
     */
    public function indexUsuarioAction()
    {
       

        return $this->render('usuario/indexUsuario.html.twig');
    }

     /**
     * Lists all usuario entities.
     *
     * @Route("usuario/admin", name="index_admin")
     * @Method("GET")
     */
    public function indexAdminAction()
    {
        
        return $this->render('usuario/indexAdmin.html.twig');
    }

    /**
     * Creates a new usuario entity.
     *
     * @Route("usuario/nuevo", name="usuario_nuevo")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $usuario = new Usuario();
        $form = $this->createForm('AppBundle\Form\UsuarioType', $usuario);
        $form->handleRequest($request);
        // Se verifica si la peticion es ajax
        if($request->isXmlHttpRequest()){
            $status = "";
            
          if ($form->isSubmitted() && $form->isValid()) {
              // Codifica el password para enviarlo a la base de datos
              $password = $passwordEncoder->encodePassword($usuario, $usuario->getPlainPassword());
              $usuario->setPassword($password);

              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($usuario);
              $entityManager->flush();
              $status = 200;
            
               return new JsonResponse(array('status' => $status, 'usuarioRegistrado'=> $usuario->getId()));
       
          }else if ($form->isSubmitted() && !$form->isValid()){
            $errors = [];
            $status = 400;
            
            $validator = $this->get('validator');
            $errorsValidator = $validator->validate($usuario);
            foreach ($errorsValidator as $error) {
                $valor = $error->getPropertyPath();
                $errors += ["$valor" => $error->getMessage()];
                
            }
            return new JsonResponse($errors);    
         
          }
        }

        return $this->render('usuario/new.html.twig', array(
            'usuario' => $usuario,
            'form' => $form->createView()
        ));
    }

    /**
     * Finds and displays a usuario entity.
     *
     * @Route("usuario/{id}/mostrar", name="usuario_mostrar")
     * @Method("GET")
     */
    public function showAction(Usuario $usuario)
    {
        $deleteForm = $this->createDeleteForm($usuario);

        return $this->render('usuario/show.html.twig', array(
            'usuario' => $usuario,
            'delete_form' => $deleteForm->createView(),
        ));
    }

     /**
     * Lists all usuario entities.
     *
     * @Route("usuario/login", name="usuario_login")
     * @Method("POST")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $peticion = null;

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
    
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        if($request->isXmlHttpRequest()){
           
        }else{
            $peticion = "url";
        }
        
          return $this->render('usuario/login.html.twig', array(
              'last_username' => $lastUsername,
              'error'         => $error,
              'peticion' => $peticion
          ));
    }

     /**
     * @Route("usuario/logout", name="usuario_logout")
     */
    public function logoutAction()
    {
    }

    /**
     * Displays a form to edit an existing usuario entity.
     *
     * @Route("usuario/{id}/editar", name="usuario_editar")
     * @Method({"GET", "POST"})
     */
    public function editarAction(Request $request, Usuario $usuario)
    {
        $deleteForm = $this->createDeleteForm($usuario);
        $editForm = $this->createForm('AppBundle\Form\UsuarioType', $usuario);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse(array("status" => "200"));
        }

        return $this->render('usuario/edit.html.twig', array(
            'usuario' => $usuario,
            'editForm' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));

    }

    /**
     * Deletes a usuario entity.
     *
     * @Route("usuario/{id}/eliminar", name="usuario_eliminar")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Usuario $usuario)
    {

        if($request->isXmlHttpRequest()){
            try {
                $em = $this->getDoctrine()->getManager();
                $em->remove($usuario);
                $em->flush();
                 
                 return new JsonResponse(array("status" => "200"));

            } catch (\Exception $e){
                return new JsonResponse(array("status" => "400"));

            }

        }

    }

    /**
     * Creates a form to delete a usuario entity.
     *
     * @param Usuario $usuario The usuario entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Usuario $usuario)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('usuario_eliminar', array('id' => $usuario->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * @Route("usuario/registro", name="registro_usuario")
     */
    public function registroAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // contruyendo el formulario
        $usuario = new usuario();
        $form = $this->createForm('AppBundle\Form\registrarUsuarioType', $usuario);
        // 2) escucha si el formulario fue enviado
        $form->handleRequest($request);
        if($request->isXmlHttpRequest()){
            $status = "";
            
          if ($form->isSubmitted() && $form->isValid()) {
              // 3) Encode the password (you could also do this via Doctrine listener)
              $password = $passwordEncoder->encodePassword($usuario, $usuario->getPlainPassword());
              $usuario->setPassword($password);

              $rol= ["ROLE_USUARIO"];
              $usuario->setRoles($rol);
              // 4) Guardando el usuario
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($usuario);
              $entityManager->flush();
              $status = 200;
              // ... do any other work - like sending them an email, etc
              // maybe set a "flash" success message for the user
               return new JsonResponse(array('status' => $status, 'usuarioRegistrado'=> $usuario->getId()));
              //return $this->redirectToRoute('replace_with_some_route');
          }else if ($form->isSubmitted() && !$form->isValid()){
            $errors = [];
            $status = 400;
            
            $validator = $this->get('validator');
            $errorsValidator = $validator->validate($usuario);
            foreach ($errorsValidator as $error) {
                $valor = $error->getPropertyPath();
                $errors += ["$valor" => $error->getMessage()];
                
            }
            return new JsonResponse($errors);    
         
          }
        }
        return $this->render(
            'usuario/registro.html.twig',
            array('form' => $form->createView())
        );
    }

   
}
