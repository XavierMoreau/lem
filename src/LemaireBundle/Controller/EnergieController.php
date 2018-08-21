<?php

namespace LemaireBundle\Controller;

use LemaireBundle\Entity\Energie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Energie controller.
 *
 * @Route("admin/energie")
 */
class EnergieController extends Controller
{
    /**
     * Lists all energie entities.
     *
     * @Route("/", name="energie_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $energies = $em->getRepository('LemaireBundle:Energie')->findAll();

        return $this->render('energie/index.html.twig', array(
            'energies' => $energies,
        ));
    }

    /**
     * Creates a new energie entity.
     *
     * @Route("/new", name="energie_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $energie = new Energie();
        $form = $this->createForm('LemaireBundle\Form\EnergieType', $energie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($energie);
            $em->flush();

            return $this->redirectToRoute('energie_show', array('id' => $energie->getId()));
        }

        return $this->render('energie/new.html.twig', array(
            'energie' => $energie,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a energie entity.
     *
     * @Route("/{id}", name="energie_show")
     * @Method("GET")
     */
    public function showAction(Energie $energie)
    {
        $deleteForm = $this->createDeleteForm($energie);

        return $this->render('energie/show.html.twig', array(
            'energie' => $energie,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing energie entity.
     *
     * @Route("/{id}/edit", name="energie_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Energie $energie)
    {
        $deleteForm = $this->createDeleteForm($energie);
        $editForm = $this->createForm('LemaireBundle\Form\EnergieType', $energie);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('energie_edit', array('id' => $energie->getId()));
        }

        return $this->render('energie/edit.html.twig', array(
            'energie' => $energie,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a energie entity.
     *
     * @Route("/{id}", name="energie_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Energie $energie)
    {
        $form = $this->createDeleteForm($energie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($energie);
            $em->flush();
        }

        return $this->redirectToRoute('energie_index');
    }

    /**
     * Creates a form to delete a energie entity.
     *
     * @param Energie $energie The energie entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Energie $energie)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('energie_delete', array('id' => $energie->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
