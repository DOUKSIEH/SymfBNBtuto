<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin_comment_index")
     */
    public function index(ObjectManager $em,CommentRepository $repo)
    {
        // dd($repo->findAll());
        // $test =  [];
        // foreach($repo->findAll() as $tt ){
        //     $test[] = $tt->getCreatedAt()->format('Y-m-d H:i:s')    ;        
        // }
        // dd($test);
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $repo->findAll(),
        ]);
    }
     /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/admin/comments/{id}/edit", name="admin_comment_edit")
     *
     * @param Comment $comment
     * @return Response
     */
    public function edit(Comment $comment, Request $request, ObjectManager $manager) {
        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le commentaire numéro <strong>{$comment->getId()}</strong> a bien été modifié !"
            );
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }
     /**
     * Permet de supprimer un commentaire
     *
     * @Route("/admin/comments/{id}/delete", name="admin_comment_delete")
     * 
     * @param Comment $comment
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Comment $comment, ObjectManager $manager) {
        // if(count($comment->getBookings()) > 0) 
        // {
        //     $this->addFlash(
        //         'warning',
        //         "Vous ne pouvez pas supprimer l'annonce <strong>{$ad->getTitle()}</strong> car elle possède déjà des réservations !"
        //     );
        // } 
        // else 
        // {
            // dd();
            $manager->remove($comment);
            $manager->flush();
    
            $this->addFlash(
                'success',
                "Le commentaire numero <strong>{$comment->getAuthor()->getFullName()}</strong> a bien été supprimée !"
            );
        

        return $this->redirectToRoute('admin_comment_index');
    }
}
