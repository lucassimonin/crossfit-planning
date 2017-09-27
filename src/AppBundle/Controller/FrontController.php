<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Session;
use AppBundle\Entity\Strength;
use AppBundle\Entity\User;
use AppBundle\Entity\Wod;
use AppBundle\Form\Type\SessionType;
use AppBundle\Form\Type\StrengthType;
use AppBundle\Form\Type\WodType;
use AppBundle\Helper\SessionHelper;
use AppBundle\Helper\UserHelper;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $params = $this->getUserInformation();
        if ($params['error']) {
            return $params['response'];
        }
        /** @var SessionHelper $sessionHelper */
        $sessionHelper = $this->get('app.session_helper');
        $sessions = $this->getDoctrine()->getRepository(Session::class)->findAllOrderBy(['day' => 'ASC', 'startTime' => 'ASC']);
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
     * @Route("/showwod", name="wod_show")
     * Page of wod page
     */
    public function showWod(): Response
    {
        $params = $this->getUserInformation();
        if ($params['error']) {
            return $params['response'];
        }
        /** @var User $user */
        $user = $params['user'];
        $events = [];

        foreach ($user->getStrengths() as $strength) {
            $events[] = ['title' => $strength->getName(),
                'start' => $strength->getDate()->format('Y-m-d'),
                'className' => 'strength',
                'data' => [
                    'weight' => $strength->getWeight(),
                    'mvt' => $strength->getName()
                ]
            ];
        }
        $translator = $this->get('translator');
        foreach ($user->getWods() as $wod) {
            $movements = [];
            foreach ($wod->getMovements() as $movement) {
                $movements[] = [
                    'name' => $movement->getName(),
                    'weight' => $movement->getWeight(),
                    'repetition' => $movement->getRepetition()
                ];
            }
            $events[] = ['title' => 'WOD',
                'start' => $wod->getDate()->format('Y-m-d'),
                'className' => 'wod',
                'data' => [
                    'date' => $wod->getDate()->format('d/m/Y'),
                    'type' => $translator->trans($wod->intToType()),
                    'comment' => $wod->getComment(),
                    'movements' => $movements,
                    'score' => $wod->getScore()
                ]
            ];
        }

        return $this->render('front/wod.html.twig', ['back' => false, 'events' => json_encode($events), 'eventCount' => count($events)]);
    }

    /**
     * @Route("/strength/add", name="strength_add")
     * Add strength action
     * @param Request $request
     * @return mixed|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addStrengthAction(Request $request)
    {
        $params = $this->getUserInformation();
        if ($params['error']) {
            return $params['response'];
        }
        /** @var User $user */
        $user = $params['user'];
        $strength = new Strength();
        $form = $this->createForm(StrengthType::class, $strength);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var EntityManager $entityManager */
            $entityManager = $this->container->get('doctrine.orm.entity_manager');
            /** @var UserHelper $userHelper */
            $userHelper = $this->get('app.user_helper');
            if ($userHelper->alreadyTraningAtThisDate($user->getStrengths(), $strength->getDate())) {
                $this->addFlash('danger', "app.strength.already_exist");

                return $this->render('front/add_strength.html.twig', ['form' => $form->createView()]);
            }


            $user->addStrength($strength);
            $entityManager->persist($strength);
            $entityManager->flush();
            $this->addFlash('success', "app.strength.strength_add");

            return $this->redirectToRoute('wod_show');
        }

        return $this->render('front/add_strength.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/wod/add", name="wod_add")
     * Add wod action
     * @param Request $request
     * @return mixed|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addWodAction(Request $request)
    {
        $params = $this->getUserInformation();
        if ($params['error']) {
            return $params['response'];
        }
        $wod = new Wod();
        $form = $this->createForm(WodType::class, $wod);

        $form->handleRequest($request);
        //dd($wod);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $params['user'];
            /** @var EntityManager $entityManager */
            $entityManager = $this->container->get('doctrine.orm.entity_manager');
            /** @var UserHelper $userHelper */
            $userHelper = $this->get('app.user_helper');
            if ($userHelper->alreadyTraningAtThisDate($user->getWods(), $wod->getDate())) {
                $this->addFlash('danger', "app.wod.already_exist");

                return $this->render('front/add_wod.html.twig', ['form' => $form->createView()]);
            }


            $user->addWod($wod);
            $entityManager->persist($wod);
            $entityManager->flush();
            $this->addFlash('success', "app.wod.wod_add");

            return $this->redirectToRoute('wod_show');
        }

        return $this->render('front/add_wod.html.twig', ['form' => $form->createView()]);
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
        $repository = $entityManager->getRepository(Session::class);
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
