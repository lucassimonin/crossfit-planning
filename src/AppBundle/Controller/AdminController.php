<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Form\Factory\FormFactory;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * @Route("/admin/dashboard", name="adminpage")
     * Dashboard index
     */
    public function indexAction(): Response
    {
        return $this->render('admin/index.html.twig', []);
    }

    /**
     * @Route("/admin/user/add/{id}", name="user_add", requirements={"id": "\d+"})
     * Add and edit user
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function userAddAction(Request $request, int $id = 0)
    {
        /** @var $formFactory FormFactory */
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
        $form = $formFactory->createForm([
            'admin' => true
        ]);
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->updateUser($user);
            $this->addFlash('success', 'app.user.user_create');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('admin/form/add_user.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/user/list", name="user_list")
     * @return Response
     */
    public function userListAction(): Response
    {
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $users = $entityManager->getRepository('AppBundle:User')->findAll();
        return $this->render('admin/list.html.twig', ['datas' => $users, 'type' => 'user', 'titles' => ['app.user.form.htag', 'app.user.form.username', 'app.user.form.email', 'app.user.form.lastname', 'app.user.form.phone', 'app.user.form.enabled']]);
    }
    /**
     * @Route("/admin/user_state/{id}", name="user_change_state", requirements={"id": "\d+"})
     */
    public function changeStateUserAction(int $id)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $repository = $entityManager->getRepository('AppBundle:User');
        /** @var User $user */
        $user = $repository->find($id);
        if (null === $user) {
            $this->addFlash('danger', 'app.user.user_not_exist');

            return $this->redirectToRoute('user_list');
        }
        if ($user->isSuperAdmin()) {
            $this->addFlash('danger', 'app.user.admin_user');

            return $this->redirectToRoute('user_list');
        }
        $user->setEnabled(!$user->isEnabled());
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'app.user.state_update');

        return $this->redirectToRoute('user_list');
    }

    /**
     * @Route("/admin/remove/{id}/{entity}", name="admin_remove", requirements={"id": "\d+"})
     * @param $id
     * @param $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(int $id, string $entity)
    {
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $repository = $entityManager->getRepository('AppBundle:' . ucfirst($entity));
        $object = $repository->find($id);
        $error = false;
        if (null === $object) {
            $this->addFlash('danger', 'app.user.user_not_exist');
            $error = true;
        }
        if (!$error) {
            $entityManager->remove($object);
            $entityManager->flush();
            $this->addFlash('success', 'app.user.state_update');
        }

        return $this->redirectToRoute($entity . '_list');
    }
}
