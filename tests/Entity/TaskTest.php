<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Enum\TaskStatus;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    public function testValidTask()
    {
        $task = new Task();
        $task->setTitle("Faire les courses");
        $task->setDescription("Acheter du pain et du lait");
        $task->setStatus(TaskStatus::TODO);

        $errors = $this->validator->validate($task);
        $this->assertCount(0, $errors);
    }

    public function testTaskWithEmptyTitle()
    {
        $task = new Task();
        $task->setTitle(""); 
        $task->setDescription("Description test");
        $task->setStatus(TaskStatus::TODO);

        $errors = $this->validator->validate($task);
        $this->assertGreaterThan(0, count($errors)); 
    }

    public function testTaskWithLongTitle()
    {
        $task = new Task();
        $task->setTitle(str_repeat("A", 300)); 
        $task->setDescription("Description test");
        $task->setStatus(TaskStatus::TODO);

        $errors = $this->validator->validate($task);
        $this->assertGreaterThan(0, count($errors)); 
    }

    public function testInvalidTaskStatus()
{
    $this->expectException(\TypeError::class);

    $task = new Task();
    $task->setTitle("Apprendre Symfony");
    $task->setDescription("Suivre une formation avancée.");
    
    $task->setStatus("INVALID_STATUS"); 
}

}

