<?php
declare(strict_types=1);

namespace Avalonia\Core\Exception;

/**
 * Class TypeException
 * @package Avalonia\Core\Exception
 * @author Benjamin Perche <benjamin@perche.me>
 */
class TypeException extends AvaloniaException
{
    /** @var string  */
    private $expectedType;

    /** @var mixed */
    private $givenValue;

    public function __construct(string $expectedType, $givenValue, \Exception $previous = null)
    {
        $this->expectedType = $expectedType;
        $this->givenValue = $givenValue;

        parent::__construct(
            sprintf(
                "The given value doesn't match with expected type %s. %s given",
                $this->expectedType,
                $this->getGivenValueType()
            ),
            null,
            $previous
        );
    }

    public function getExceptedType(): string
    {
        return $this->expectedType;
    }

    public function getGivenValue()
    {
        return $this->givenValue;
    }

    public function getGivenValueType(): string
    {
        return is_object($this->givenValue) ? get_class($this->givenValue) : gettype($this->givenValue);
    }
}
