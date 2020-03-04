<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 4; $i++) {
            $post = new Post;

            $post->setAutor($this->getReference('user'));            
            $post->setContent('Velit sint et laborum ea vel qui consequuntur. Voluptas et ut praesentium dolores sed accusamus quis qui. Necessitatibus ab dolores possimus voluptatem consequatur. Quia officia pariatur corrupti qui. Delectus molestias reprehenderit voluptatem architecto sed aliquam. Unde ullam dolores unde quia.');
            $post->setFeaturedImage('uploads/posts/post-image-dummy.png');
            $post->setPublishedDate(new DateTime('now'));
            $post->setSlug("my-post-{$i}");
            $post->setTitle("Post title dummy {$i}");

            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
