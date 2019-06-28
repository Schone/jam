<?php


namespace App\Controller;

use App\Entity\Invitation;
use App\Form\InvitationForm;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
class PageController extends Controller{

    /**
     *@Route("/profile", name="profile")
     */
    public function profileAction(){
        $user = $this->getUser();
        $user_id = $user->getId();
        $invitation = new Invitation();
        $data = ['id' => $this->getUser()->getId()];
        $form = $this->createFormBuilder($invitation)
            ->add('title')
            ->add('description')
            ->add('invited', EntityType::class, array(
                'class' => User::class,
                'choice_label' => 'username',
                'query_builder' => function (EntityRepository $er) use ($user_id) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.id <> :user')
                        ->setParameter('user', $user_id)
                        ->orderBy('u.id', 'ASC');
                }))
            ->add('save', SubmitType::class)
            ->getForm();

        return $this->render('pages/profile.html.twig',[
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

}