<?php

namespace App\DataFixtures;

use App\Entity\Commit;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 *
 */
class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $userPasswordHasher;

    /**
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $users =[];
        for($i=1;$i<=10 ; $i++){
            $user = new User();
            $user->setPassword($this->userPasswordHasher->hashPassword($user,'password'));
            $user->setEmail(sprintf("email+%d@email.com",$i));
            $user->setName(sprintf("name+%d",$i));
            $manager->persist($user);
            $users[]=$user;
        }
        foreach ($users as $user){
            for($j=1;$j<=5;$j++){
                $post = Post::create("content",$user);

              shuffle($users);
              foreach (array_slice($users,0,5)as $usercanlike){
                  $post->likeBy($usercanlike);
              }

                $manager->persist($post);
             for($k=1;$k<=10;$k++){
             $commit = Commit::create(sprintf("message+%d",$k),$users[array_rand($users)],$post);
                 $manager->persist($commit);
             }
            }
        }

        $manager->flush();
    }
}
