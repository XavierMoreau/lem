<?php

namespace LemaireBundle\Controller;

use LemaireBundle\Entity\Options;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Option controller.
 *
 * @Route("admin/options")
 */
class OptionsController extends Controller
{
    /**
     * Lists all options entities.
     *
     * @Route("/", name="options_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $options = $em->getRepository('LemaireBundle:Options')->findAll();

        return $this->render('options/index.html.twig', array(
            'options' => $options,
        ));
    }

    /**
     * Creates a new options entity.
     *
     * @Route("/new", name="options_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $options = new Options();
        $form = $this->createForm('LemaireBundle\Form\OptionsType', $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($options);
            $em->flush();

            return $this->redirectToRoute('options_show', array('id' => $options->getId()));
        }

        return $this->render('options/new.html.twig', array(
            'options' => $options,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a option entity.
     *
     * @Route("/{id}", name="options_show")
     * @Method("GET")
     */
    public function showAction(Options $options)
    {
        $deleteForm = $this->createDeleteForm($options);

        return $this->render('options/show.html.twig', array(
            'options' => $options,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing option entity.
     *
     * @Route("/{id}/edit", name="options_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Options $options)
    {
        $deleteForm = $this->createDeleteForm($options);
        $editForm = $this->createForm('LemaireBundle\Form\OptionsType', $options);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('options_edit', array('id' => $options->getId()));
        }

        return $this->render('options/edit.html.twig', array(
            'options' => $options,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a option entity.
     *
     * @Route("/{id}", name="options_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Options $options)
    {
        $form = $this->createDeleteForm($options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($options);
            $em->flush();
        }

        return $this->redirectToRoute('options_index');
    }

    /**
     * Creates a form to delete a option entity.
     *
     * @param Options $option The option entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Options $options)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('options_delete', array('id' => $options->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
