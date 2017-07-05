<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\SessionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserManagerInterface;

class AdminController extends Controller
{
    /**
     * @Route("/admin/dashboard", name="adminpage")
     * Dashboard index
     */
    public function indexAction()
    {
        return $this->render('admin/index.html.twig', []);
    }

    /**
     * @Route("/admin/product/add/{id}", name="product_add", requirements={"id": "\d+"})
     * Add and Edit product
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function productAddAction(Request $request, $id = 0)
    {
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $repository = $entityManager->getRepository('AppBundle:Product');
        $product = $repository->find($id);
        if (null === $product) {
            $product = new Product();
        }

        $form = $this->createForm(SessionType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('success', "Product updated/created !");

            return $this->redirectToRoute('product_list');
        }

        return $this->render('admin/form/add_product.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/user/add/{id}", name="user_add", requirements={"id": "\d+"})
     * Add and edit user
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function userAddAction(Request $request, $id = 0)
    {
        /** @var $formFactory FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');

        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $repository = $entityManager->getRepository('AppBundle:User');
        $user = $repository->find($id);
        if (null === $user) {
            $user = $userManager->createUser();
            $user->setEnabled(true);
        }
        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->updateUser($user);
            $this->addFlash('success', "User updated/created !");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('admin/form/add_user.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/user/list", name="user_list")
     */
    public function userListAction()
    {
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $users = $entityManager->getRepository('AppBundle:User')->findAll();
        return $this->render('admin/list.html.twig', ['datas' => $users, 'type' => 'user', 'titles' => ['app.user.form.htag', 'app.user.form.username', 'app.user.form.email', 'app.user.form.lastname', 'app.user.form.phone', 'app.user.form.enabled']]);
    }

    /**
     * @Route("/admin/remove/{id}/{entity}", name="admin_remove", requirements={"id": "\d+"})
     * @param $id
     * @param $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction($id, $entity)
    {
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $repository = $entityManager->getRepository('AppBundle:' . ucfirst($entity));
        $object = $repository->find($id);
        $error = false;
        if (null === $object) {
            $this->addFlash('danger', "Object doesn't exist !");
            $error = true;
        }
        if (!$error) {
            $entityManager->remove($object);
            $entityManager->flush();
            $this->addFlash('success', "Object deleted !");
        }

        return $this->redirectToRoute($entity . '_list');
    }
}
