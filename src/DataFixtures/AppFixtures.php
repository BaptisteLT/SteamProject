<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Craft;
use App\Entity\Items;
use App\Entity\Tchat;
use App\Entity\Ticket;
use App\Entity\Giveaway;
use App\Entity\UserLikeCraft;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');


        //Créer les users
        for($u=0; $u<5; $u++)
        {
            $user = new User;
            $user->setRoles($faker->randomElement([['ROLE_USER','ROLE_MOD'],['ROLE_USER'],['ROLE_MOD'],['ROLE_ADMIN']]));
            $user->setSteamId($faker->numberBetween($min = 10000000000000000, $max = 99999999999999999));
            $user->setTchatBan($faker->dateTime('now'),null);
            $user->setSiteBan(null);
            $user->setTchatBanCounter($faker->numberBetween($min = 0, $max = 3));
            $user->setSiteBanCounter($faker->numberBetween($min = 0, $max = 1));
            $user->setSteamId($faker->numberBetween($min = 10000000000000000, $max = 99999999999999999));
            $user->setCommunityVisibilityState(3);
            $user->setProfileState(1);
            $user->setProfileName($faker->userName);
            $user->setLastLogOff(0);//Date est mise direct dans la méthode
            $user->setCommentPermission(1);
            $user->setProfileUrl('https://steamcommunity.com/id/JeanAimar/');
            $user->setAvatar('https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/59/59c3e4259c733dec3f90491b0b6d96da2c0d0958_full.jpg');
            $user->setPersonaState(0);
            $user->setPrimaryClanId(103582791438491038);
            $user->setJoinDate(0);//Date est mise direct dans la méthode
            $user->setCountryCode($faker->countryCode);


            //Créer les tchats
            for($t=0; $t<5; $t++)
            {
                $tchat = new Tchat;
                $tchat->setUser($user);
                $tchat->setMessage($faker->sentence(14,true));
                $tchat->setDate($faker->dateTime('now'),null);
                $tchat->setSteamid($user->getSteamId());

                $manager->persist($tchat);
            }
            $user->addTchat($tchat);

            //Créer Giveaway
            $giveaway=new Giveaway;
            $giveaway->setDescription($faker->sentence(14,true));
            $giveaway->addUser($user);
            $giveaway->setNomImage("nom_de_img");
            $giveaway->setUpdatedAt($faker->dateTime('now'),null);
            $giveaway->setWinnerTradeurl(null);
            $giveaway->setWinner(null);
            $giveaway->setImageName("nom_de_img");
            $giveaway->setImageFile(null);

            $manager->persist($giveaway);

            //Créer les tickets
            for($t=0; $t<3; $t++)
            {
                $ticket = new Ticket;
                $ticket->setUser($user);
                $ticket->setDescription($faker->sentence(14,true));
                $ticket->setStatut(1);

                $manager->persist($ticket);
            }

            //Créer les items
            for($i=0; $i<10; $i++)
            {
                $item = new Items;
                $item->setItemsGroup(null);
                $item->setIconUrl('-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXQ9QVcJY8gulROWE3eQ-W_04DURlxmIQ1ZsYWtLgtsnfeYdTxDuovgwYPflq72Mu7TkGkBsJQoiLGUpo_z2VG2qBFqZmilcI6LMlhpUqHYdWE');
                $item->setName("Katowice 2015 | Envyus");
                $item->setPrice(5);
                $item->setStickerBool($faker->randomElement([true,false]));
                $item->setId($faker->numberBetween($min = 100000000, $max = 999999999));


                //Craft Verified
                $craftV = new Craft;
                $craftV->setUser($user);
                $craftV->setSlot1($item);
                $craftV->setSlot2($item);
                $craftV->setSlot3($item);
                $craftV->setSlot4($item);
                $craftV->setTitre("titre");
                $craftV->setDescription($faker->sentence(14,true));
                $craftV->setCost(100);
                $craftV->setVerified(true);
                $craftV->setDateAjout(new \DateTime('2020-05-21'));
                $craftV->setNomImage("nom_de_img");
                $craftV->setUpdatedAt(new \DateTime('2020-05-21'));

                $manager->persist($craftV);
                
                //Craft not verified
                $craft = new Craft;
                $craft->setUser($user);
                $craft->setSlot1($item);
                $craft->setSlot2($item);
                $craft->setSlot3($item);
                $craft->setSlot4($item);
                $craft->setTitre($faker->title);
                $craft->setDescription($faker->sentence(14,true));
                $craft->setCost(100);
                $craft->setVerified(false);
                $craft->setDateAjout(new \DateTime('2020-05-22'));
                $craft->setNomImage("nom_de_img");
                $craft->setUpdatedAt(new \DateTime('2020-05-22'));

                $manager->persist($craft);
                

                $manager->persist($item);

                //(User/craft like)
                $user_like_craft=new UserLikeCraft;
                $user_like_craft->setUser($user);
                $user_like_craft->setNiveauDuLike(2);
                $user_like_craft->setCraft($craftV); //(Only a verified craft can be liked)

                $manager->persist($user_like_craft);
            }

            $manager->persist($user);
        }


        $manager->flush();
    }
}
