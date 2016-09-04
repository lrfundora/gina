<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Form\Type\AddCategoryType;
use AppBundle\Entity\Category;
use AppBundle\Form\Type\EditCategoryType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminCategoryController
 * @package AppBundle\Controller\Admin
 * @Route("/admin")
 */
class AdminCategoryController extends Controller
{
    /**
     * @Route("/category", name="_admin_category")
     */
    public function listCategoryAction()
    {
        // Render view
        return $this->render('AppBundle:backend/category:listCategory.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => 'category',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'categories' => $this->get('app.query')->findAllCategory()
        ]);
    }

    /**
     * @Route("/category/add", name="_admin_category_add")
     */
    public function addCategoryAction(Request $request)
    {
        $query = $this->get('app.query')->getAllBusinessName();
        foreach ($query as $business) {
            $choiceBusiness[$business->getId()] = $business->getName();
        }

        $form = $this->createForm(new AddCategoryType($choiceBusiness));

        $form->handleRequest($request);

        if ($form->isValid()) {

            $formData = $form->getData();
            $em = $this->getDoctrine()->getManager();

            // Check if category exist
            if (!$this->get('app.query')->existCategory($formData['business'], $formData['category'])) {
                $business = $this->getDoctrine()->getRepository('AppBundle:Business')->findOneBy(['id' => $formData['business']]);
                if (!$business) {
                    $msg = 'Error: Categoría <strong>no encontrada!</strong>';
                    $this->get('session')->getFlashBag()->add('addCategoryError', $msg);
                    return $this->redirectToRoute('_admin_category_add');
                }

                $entity = new Category();
                $entity->setName($formData['category']);
                $entity->setBusiness($business);

                $em->persist($entity);
                $em->flush();

                $msg = 'Categoría <strong>agregada con exito!</strong>';
                $this->get('session')->getFlashBag()->add('addCategorySuccess', $msg);

                return $this->redirectToRoute('_admin_category_add');

            } else {
                $msg = 'Ya existe la categoría "<strong>' . $formData['category'] . '</strong>"';
                $this->get('session')->getFlashBag()->add('addCategoryError', $msg);
            }

        }
        // Render view
        return $this->render('AppBundle:backend/category:addCategory.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => 'category',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/category/edit/{id}", name="_admin_category_edit")
     */
    public function editCategoryAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $this->get('app.query')->findCategoryById($id);
        if (!$category) {
            $this->get('session')->getFlashBag()->add('categoryError', 'Error: Categoría <strong>no encontrada!</strong>');
            return $this->redirectToRoute('_admin_category');
        }
        $query = $this->get('app.query')->getAllBusinessName();
        foreach ($query as $business) {
            $choiceBusiness[$business->getId()] = $business->getName();
        }

        $form = $this->createForm(new EditCategoryType($choiceBusiness, $category->getBusiness()->getId(), $category->getName()));
        $form->handleRequest($request);
        $formData = $form->getData();
        if ($form->isValid()) {
            if ($formData['business'] != $category->getBusiness()->getId()) {
                $business = $this->get('app.query')->findBusinessId($formData['business']);
                $category->setBusiness($business);
            }
            $category->setName($formData['name']);
            $em->flush();
            $this->get('session')->getFlashBag()->add('editCategory', 'Categoría <strong>actualizada con éxito!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_category_edit', ['id' => $id]));
        }

        // Render view
        return $this->render('AppBundle:backend/category:editCategory.html.twig', [
            'appConfig' => $this->get('app.query')->getConfig(),
            'menu' => 'category',
            'nNotice' => $this->get('app.query')->countUnreadNotice(),
            'newNotices' => $this->get('app.query')->findAllNoticesUnRead(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/category/delete/{id}", name="_admin_category_delete")
     */
    public function deleteCategoryAction($id)
    {
        $category = $this->get('app.query')->findCategoryById($id);

        if (!$category) {
            $this->get('session')->getFlashBag()->add('categoryError', 'La categoría que ha intentado eliminar <strong>no existe!</strong>');
            return new RedirectResponse($this->generateUrl('_admin_category'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        $this->get('session')->getFlashBag()->add('categorySuccess', 'Categoría <strong>eliminada correctamente!</strong>');
        return new RedirectResponse($this->generateUrl('_admin_category'));
    }
}
