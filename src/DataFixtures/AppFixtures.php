<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Event;
use App\Entity\Post;
use App\Entity\Comment;
use App\Entity\Gallery;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Users
        $users = [];
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail($faker->unique()->email)
                ->setPassword($this->passwordHasher->hashPassword($user, 'password'))
                ->setUsername($faker->userName)
                ->setPhoto($faker->imageUrl(100, 100, 'people'))
                ->setBio($faker->paragraph)
                ->setVerified($faker->boolean)
                ->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-2 years', '-1 year')->format('Y-m-d H:i:s')))
                ->setUpdatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s')))
                ->setMembershipStatus($faker->randomElement(['Active', 'Inactive']));
            $manager->persist($user);
            $users[] = $user;
        }

        // Products
        $products = [];
        for ($i = 0; $i < 5; $i++) {
            $product = new Product();
            $product->setName($faker->word)
                    ->setDescription($faker->sentence)
                    ->setExclusive($faker->boolean)
                    ->setPrice($faker->randomFloat(2, 10, 100))
                    ->setImage($faker->imageUrl(360, 360, 'animals', true, 'cats'))
                    ->setStock($faker->numberBetween(0, 50))
                    ->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s')));

            $manager->persist($product);
            $products[] = $product;
        }

        // Orders
        for ($i = 0; $i < 5; $i++) {
            $order = new Order();
            $order->setUser($faker->randomElement($users))
                  ->setOrderDate(new \DateTimeImmutable($faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s')))
                  ->setTotalPrice($faker->randomFloat(2, 50, 500))
                  ->setStatus($faker->randomElement(['PENDING', 'PAID', 'SHIPPED', 'COMPLETED', 'CANCELLED']));

            // Add random products to the order
            $selectedProducts = $faker->randomElements($products, $faker->numberBetween(1, 3));
            foreach ($selectedProducts as $product) {
                $order->addProduct($product);
            }

            $manager->persist($order);
        }

        // Events
        $events = [];
        for ($i = 0; $i < 5; $i++) {
            $event = new Event();
            $event->setTitle($faker->sentence)
                  ->setDescription($faker->paragraph)
                  ->setLocation($faker->city)
                  ->setDate($faker->dateTimeBetween('+1 week', '+1 month'))
                  ->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'))); // Correction
            $manager->persist($event);
            $events[] = $event;
        }

        // Posts
        $posts = [];
        for ($i = 0; $i < 5; $i++) {
            $post = new Post();
            $post->setTitle($faker->sentence)
                 ->setContent($faker->paragraph)
                 ->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s')))
                 ->setUpdatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s')))
                 ->setAuthor($faker->randomElement($users));
            $manager->persist($post);
            $posts[] = $post;
        }

        // Comments
        for ($i = 0; $i < 5; $i++) {
            $comment = new Comment();
            $comment->setContent($faker->sentence)
                    ->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d H:i:s')))
                    ->setUser($faker->randomElement($users))
                    ->setPost($faker->randomElement($posts));
            $manager->persist($comment);
        }

        // Galleries
        for ($i = 0; $i < 5; $i++) {
            $gallery = new Gallery();
            $gallery->setTitle($faker->sentence)
                    ->setFile($faker->imageUrl())
                    ->setUser($faker->randomElement($users));

            // Add random comments to the gallery
            $commentsCount = $faker->numberBetween(0, 3);
            for ($j = 0; $j < $commentsCount; $j++) {
                $comment = new Comment();
                $comment->setContent($faker->sentence)
                        ->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d H:i:s')))
                        ->setUser($faker->randomElement($users));
                $manager->persist($comment);
                $gallery->addComment($comment);
            }

            $manager->persist($gallery);
        }

        $manager->flush();
    }
}
