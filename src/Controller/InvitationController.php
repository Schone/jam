<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Invitation;
use App\Form\InvitationForm;

/**
 * Invitation controller.
 * @Route("/api", name="api_")
 */
class InvitationController extends FOSRestController
{
    /**
     * Lists user invitation.
     * @Rest\Get("/got-invitation")
     *
     * @return Response
     */
    public function gotInvitationsAction()
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');
        $repository = $this->getDoctrine()->getRepository(Invitation::class);
        $query = $repository->createQueryBuilder('p')
            ->where('p.invited = :user')
            ->setParameter('user', $this->getUser())
            ->getQuery();

        $invitations = $query->getResult();
        return $this->handleView($this->view($invitations));
    }

    /**
     * Lists user invitation.
     * @Rest\Get("/sent-invitation")
     *
     * @return Response
     */
    public function sentInvitationsAction()
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');
        $repository = $this->getDoctrine()->getRepository(Invitation::class);
        $query = $repository->createQueryBuilder('p')
            ->where('p.sender = :user')
            ->setParameter('user', $this->getUser())
            ->getQuery();

        $invitations = $query->getResult();
        return $this->handleView($this->view($invitations));
    }

    /**
     * Update invitation status.
     * @Rest\Post("/invitation/update")
     *
     * @return Response
     */
    public function updateInvitationsAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');
        $id = $request->request->get('id');
        $action = $request->request->get('action');
        $repository = $this->getDoctrine()->getRepository(Invitation::class);
        $invitations = $repository->find($id);
        $invitations->setAccepted($action);
        $em = $this->getDoctrine()->getManager();
        $em->persist($invitations);
        $em->flush();
        return $this->handleView($this->view($invitations));
    }

    /**
     * Add new invitation.
     * @Rest\Post("/invitation/add")
     *
     * @return Response
     */
    public function addInvitationsAction(Request $request)
    {
        $invitation = new Invitation();
        $form = $this->createForm(InvitationForm::class, $invitation);
        $title = $request->request->get('title');
        $description = $request->request->get('description');
        $invite = $request->request->get('invite');
        $data = ['title' => $title, 'description' => $description, 'invited' => $invite];
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $action = $form->getData();
            $action->setSender($this->getUser());
            $action->setAccepted('none');
            $em = $this->getDoctrine()->getManager();
            $em->persist($action);
            $em->flush();
            return $this->handleView($this->view(['status' => 'Successfully created invitation'], Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors()));
    }
}