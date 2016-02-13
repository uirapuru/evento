<?php
namespace AppBundle\Factory;

use AppBundle\Command\CreateLesson;
use AppBundle\Command\UpdateLesson;
use AppBundle\Entity\Lesson;
use AppBundle\Entity\LessonRepository;
use AppBundle\Generator\IdGeneratorInterface;

class LessonFactory
{
    private $idGenerator;

    /** @var  LessonRepository */
    private $lessonRepository;

    /**
     * LessonFactory constructor.
     * @param IdGeneratorInterface $idGenerator
     * @param LessonRepository $repository
     */
    public function __construct(IdGeneratorInterface $idGenerator, LessonRepository $repository)
    {
        $this->idGenerator = $idGenerator;
        $this->lessonRepository = $repository;
    }

    /**
     * @param CreateLesson $command
     * @return Lesson
     */
    public function createFromCommand(CreateLesson $command){

        if(get_class($command) === UpdateLesson::class)
        {
            return $this->lessonRepository->find($command->id);
        }

        return new Lesson(
            $this->idGenerator->generate(),
            $command->title,
            $command->description,
            $command->address,
            $command->city,
            null,
            null,
            $command->workshop,
            $command->event
        );
    }

    /**
     * @param Lesson $lesson
     * @param string $commandClass
     * @return CreateLesson|UpdateLesson
     */
    public function createCommand(Lesson $lesson, $commandClass = CreateLesson::class)
    {
        /** @var CreateLesson|UpdateLesson $command */
        $command =  new $commandClass;
        $command->id = $lesson->getId();
        $command->title = $lesson->getTitle();
        $command->description = $lesson->getDescription();
        $command->address = $lesson->getAddress();
        $command->city = $lesson->getCity();
        $command->workshop = $lesson->getWorkshop();

        return $command;
    }
}