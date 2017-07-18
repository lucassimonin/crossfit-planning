<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Session;
use AppBundle\Entity\User;
use AppBundle\Form\Type\SessionType;
use AppBundle\Helper\SessionHelper;
use AppBundle\Helper\UserHelper;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FrontController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * Index page
     */
    public function indexAction(): Response
    {
        /** @var SessionHelper $sessionHelper */
        $sessionHelper = $this->get('app.session_helper');
        $sessions = $this->getDoctrine()->getRepository('AppBundle:Session')->findAllOrderBy(['day' => 'ASC', 'startTime' => 'ASC']);
        $sessionArray = [];
        if (count($sessions) > 0) {
            foreach ($sessions as $session) {
                $sessionArray[$session->intToDay()][] = [
                    'disabled' => $sessionHelper->isStarted($session),
                    'session' => $session
                ];
            }
        }

        return $this->render('front/index.html.twig', ['back' => false, 'sessions' => $sessionArray]);
    }

    /**
     * @Route("/subscription/add/{id}", name="subscription_add", requirements={"id" = "\d+"})
     * Subscription action
     *
     * @param int $id
     * @return mixed|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addSubscriptionAction(int $id)
    {
        $params = $this->getUserInformation();
        if ($params['error']) {
            return $params['response'];
        }
        /** @var EntityManager $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $repository = $entityManager->getRepository('AppBundle:Session');
        /** @var Session $session */
        $session = $repository->find($id);
        if (null === $session) {
            $this->addFlash('danger', "app.session.not_exist");

            return $this->redirectToRoute('homepage');
        }
        /** @var SessionHelper $sessionHelper */
        $sessionHelper = $this->get('app.session_helper');
        if ($sessionHelper->isMaxUsers($session)) {
            $this->addFlash('danger', "app.session.max_user");

            return $this->redirectToRoute('homepage');
        }
        /** @var User $user */
        $user = $params['user'];
        if ($user->isFullSubscription()) {
            $this->addFlash('danger', "app.session.max_session");

            return $this->redirectToRoute('homepage');
        }
        /** @var UserHelper $userHelper */
        $userHelper = $this->get('app.user_helper');
        if ($userHelper->isInThisSession($user, $session->getId())) {
            $this->addFlash('danger', "app.session.already_session");

            return $this->redirectToRoute('homepage');
        }

        if ($sessionHelper->isStarted($session)) {
            $this->addFlash('danger', "app.session.already_start");

            return $this->redirectToRoute('homepage');
        }

        $session->addUser($user);
        $entityManager->persist($session);
        $entityManager->flush();
        $entityManager->persist($user);
        $entityManager->flush();
        $this->addFlash('success', "app.session.add");

        return $this->redirectToRoute('homepage');
    }



    /**
     * @Route("/subscription/remove/{id}", name="subscription_remove", requirements={"id" = "\d+"})
     * Subscription action
     *
     * @param int $id
     * @return mixed|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeSubscriptionAction(int $id)
    {
        $params = $this->getUserInformation();
        if ($params['error']) {
            return $params['response'];
        }
        /** @var EntityManager $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $repository = $entityManager->getRepository('AppBundle:Session');
        $session = $repository->find($id);
        if (null === $session) {
            $this->addFlash('danger', "app.session.not_exist");

            return $this->redirectToRoute('homepage');
        }
        /** @var User $user */
        $user = $params['user'];
        $session->removeUser($user);
        $entityManager->persist($session);
        $entityManager->flush();
        $this->addFlash('success', "app.session.subscription_removed");

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/session/add", name="session_add")
     * Add session action
     * @param Request $request
     * @return mixed|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addSessionAction(Request $request)
    {
        $session = new Session();
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($session->getStartTime() >= $session->getEndTime()) {
                $this->addFlash('danger', "app.session.start_up_end");

                return $this->render('front/add_session.html.twig', ['form' => $form->createView()]);
            }
            /** @var EntityManager $entityManager */
            $entityManager = $this->container->get('doctrine.orm.entity_manager');
            $repository = $entityManager->getRepository('AppBundle:Session');
            $result = $repository->getExistingSession($session);
            if (count($result) > 0) {
                $this->addFlash('danger', "app.session.exist");

                return $this->render('front/add_session.html.twig', ['form' => $form->createView()]);
            }
            $entityManager->persist($session);
            $entityManager->flush();
            $this->addFlash('success', "app.session.session_add");

            return $this->redirectToRoute('homepage');
        }

        return $this->render('front/add_session.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/session/delete/{id}", name="session_delete", requirements={"id" = "\d+"})
     * Subscription action
     *
     * @param int $id
     * @return mixed|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteSessionAction(int $id)
    {
        $params = $this->getUserInformation();
        if ($params['error']) {
            return $params['response'];
        } elseif (!$params['admin']) {
            $this->addFlash('danger', "app.session.not_access");

            return $this->redirectToRoute('homepage');
        }
        /** @var EntityManager $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $repository = $entityManager->getRepository('AppBundle:Session');
        $session = $repository->find($id);
        if (null === $session) {
            $this->addFlash('danger', "app.session.not_exist");

            return $this->redirectToRoute('homepage');
        }
        foreach ($session->getUsers() as $user) {
            $user->removeSession($session);
        }
        $entityManager->remove($session);
        $entityManager->flush();
        $this->addFlash('success', "app.session.removed");

        return $this->redirectToRoute('homepage');
    }

    /**
     * Get user information
     * @return array
     */
    private function getUserInformation(): array
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash('danger', "app.session.login");

            return ['error' => true, 'response' => new RedirectResponse($this->container->get('router')->generate('login'))];
        }
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $params['error'] = false;
        $params['user'] = $user;
        $params['admin'] = $user->isSuperAdmin();

        return $params;
    }
}
