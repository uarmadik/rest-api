<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 0; $i < 5; $i++) {
            $task = new Task();
            $task->setTitle('Task ' . ($i + 1));
            $task->setContent('Content for task ' . ($i + 1));
            $manager->persist($task);
        }

        $manager->flush();
    }
}
