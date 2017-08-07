<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    /**
     * @Route("/register", name="user_register")
     * Register user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function userAddAction(Request $request): Response
    {
        /** @var $formFactory FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setEnabled(false);
        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->updateUser($user);
            $this->addFlash('success', 'app.user.user_create_front');
            $message = new \Swift_Message('Crossfit Palavas - Nouveau compte', $this->renderView(
                    'front/email/new_account.html.twig'),'text/html');
            $message->setFrom('no_reply@crossfitpalavas.fr');
            $message->setTo($this->getParameter('email_contact'));
            $this->get('mailer')->send($message);

            $message = new \Swift_Message('Crossfit Palavas - Nouveau compte', $this->renderView(
                    'front/email/new_account_user.html.twig'),'text/html');
            $message->setFrom('no_reply@crossfitpalavas.fr');
            $message->setTo($user->getEmail());
            $this->get('mailer')->send($message);

            return $this->redirectToRoute('user_list');
        }

        return $this->render('front/form/register.html.twig', ['form' => $form->createView()]);
    }

}