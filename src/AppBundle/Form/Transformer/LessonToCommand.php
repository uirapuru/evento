<?php
namespace AppBundle\Form\Transformer;

use AppBundle\Command\CreateLesson;
use AppBundle\Command\LessonCommandInterface;
use AppBundle\Command\UpdateLesson;
use AppBundle\Entity\Lesson;
use AppBundle\Entity\LessonRepository;
use AppBundle\Factory\LessonFactory;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Traversable;

/**
 * Class LessonToCommand
 * @package AppBundle\Form\Transformer
 */
class LessonToCommand implements DataTransformerInterface
{
    /** @var string */
    private $commandClass = CreateLesson::class;

    /**
     * @var LessonFactory
     */
    private $lessonFactory;

    /**
     * LessonToCommand constructor.
     * @param LessonFactory $lessonFactory
     */
    public function __construct(LessonFactory $lessonFactory)
    {
        $this->lessonFactory = $lessonFactory;
    }

    /**
     * Transforms a value from the original representation to a transformed representation.
     *
     * This method is called on two occasions inside a form field:
     *
     * 1. When the form field is initialized with the data attached from the datasource (object or array).
     * 2. When data from a request is submitted using {@link Form::submit()} to transform the new input data
     *    back into the renderable format. For example if you have a date field and submit '2009-10-10'
     *    you might accept this value because its easily parsed, but the transformer still writes back
     *    "2009/10/10" onto the form field (for further displaying or other purposes).
     *
     * This method must be able to deal with empty values. Usually this will
     * be NULL, but depending on your implementation other empty values are
     * possible as well (such as empty strings). The reasoning behind this is
     * that value transformers must be chainable. If the transform() method
     * of the first value transformer outputs NULL, the second value transformer
     * must be able to process that value.
     *
     * By convention, transform() should return an empty string if NULL is
     * passed.
     *
     * @param Lesson[] $value The value in the original representation
     * @return CreateLesson[] The value in the transformed representation
     *
     * @throws TransformationFailedException When the transformation fails.
     */
    public function transform($value)
    {
        return $this->product($value);
    }

    /**
     * Transforms a value from the transformed representation to its original
     * representation.
     *
     * This method is called when {@link Form::submit()} is called to transform the requests tainted data
     * into an acceptable format for your data processing/model layer.
     *
     * This method must be able to deal with empty values. Usually this will
     * be an empty string, but depending on your implementation other empty
     * values are possible as well (such as empty strings). The reasoning behind
     * this is that value transformers must be chainable. If the
     * reverseTransform() method of the first value transformer outputs an
     * empty string, the second value transformer must be able to process that
     * value.
     *
     * By convention, reverseTransform() should return NULL if an empty string
     * is passed.
     *
     * @param CreateLesson[] $value The value in the transformed representation
     * @return Lesson[] The value in the original representation
     * @throws TransformationFailedException When the transformation fails.
     */
    public function reverseTransform($value)
    {
        return $this->product($value);
    }

    /**
     * @param $class
     * fuck fluent interfaces, but I need it here ;)
     * @return $this
     */
    public function setCommandClass($class)
    {
        $this->commandClass = $class;
        return $this;
    }

    private function product($value)
    {
        if(!is_null($value)) {
            if(is_array($value) || $value instanceof Traversable) {
                $result = [];

                /** @var Lesson $lesson */
                foreach($value as $lesson) {
                    if($lesson instanceof Lesson) {
                        $result[] = $this->lessonFactory->createCommand($lesson, $this->commandClass);
                    } elseif($lesson instanceof LessonCommandInterface) {
                        $result[] = $this->lessonFactory->createFromCommand($lesson);
                    }
                }

                return $result;
            }
        }
    }
}