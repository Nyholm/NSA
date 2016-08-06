<?php

namespace Nyholm\Reflection;

use Webmozart\Assert\Assert;

/**
 * Warning: This class should only be used with tests, fixtures or debug.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Reflection
{
    /**
     * Get a property of an object.
     *
     * @param object|string $objectOrClass
     * @param string $propertyName
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public static function getProperty($objectOrClass, $propertyName)
    {
        return self::getAccessibleReflectionProperty($objectOrClass, $propertyName)->getValue($objectOrClass);
    }

    /**
     * Set a property to an object.
     *
     * @param object|string $objectOrClass
     * @param string $propertyName
     * @param mixed  $value
     *
     * @throws \InvalidArgumentException
     */
    public static function setProperty($objectOrClass, $propertyName, $value)
    {
        self::getAccessibleReflectionProperty($objectOrClass, $propertyName)->setValue($objectOrClass, $value);
    }

    /**
     * Invoke a method on a object and get the return values.
     *
     * @param object|string $objectOrClass
     * @param string $methodName
     * @param mixed ...$params
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public static function invokeMethod()
    {
        if (func_num_args() < 2) {
            throw new \LogicException('The method Reflection::invokeMethod need at least two arguments.');
        }

        $arguments = func_get_args();
        $objectOrClass = array_shift($arguments);
        $methodName = array_shift($arguments);

        Assert::notInstanceOf($objectOrClass, '\stdClass', 'Can not get a method of \stdClass.');
        Assert::string($methodName, 'Method name has to be a string. Variable of type "%s" was given.');

        if (!is_object($objectOrClass) && !is_string($objectOrClass)) {
            throw new \InvalidArgumentException(sprintf('Can not invoke method of a non object. Variable of type "%s" was given.', gettype($objectOrClass)));
        }

        if (is_string($objectOrClass)) {
            Assert::classExists($objectOrClass, 'Coud not find class "%s"');
        }

        $refl = new \ReflectionClass($objectOrClass);
        if (!$refl->hasMethod($methodName)) {
            throw new \LogicException(sprintf('The method %s::%s does not exist.', get_class($objectOrClass), $methodName));
        }

        $method = $refl->getMethod($methodName);
        $method->setAccessible(true);

        if (is_string($objectOrClass)) {
            $objectOrClass = null;
        }

        return $method->invokeArgs($objectOrClass, $arguments);
    }

    /**
     * Get a reflection class that has this property.
     *
     * @param object|string  $objectOrClass
     * @param string $propertyName
     *
     * @return \ReflectionClass|null
     */
    protected static function getReflectionClassWithProperty($objectOrClass, $propertyName)
    {
        if (!is_object($objectOrClass) && !is_string($objectOrClass)) {
            return;
        }

        $refl = new \ReflectionClass($objectOrClass);
        if ($refl->hasProperty($propertyName)) {
            return $refl;
        }

        return self::getReflectionClassWithProperty(get_parent_class($objectOrClass), $propertyName);
    }

    /**
     * Get an reflection property that you can access directly.
     *
     * @param object|string $objectOrClass
     * @param string $propertyName
     *
     * @return \ReflectionProperty
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException if the property is not found on the object
     */
    protected static function getAccessibleReflectionProperty($objectOrClass, $propertyName)
    {
        Assert::string($propertyName, 'Property name must be a string. Variable of type "%s" was given.');

        if (is_string($objectOrClass)) {
            Assert::classExists($objectOrClass, 'Coud not find class "%s"');
        } else {
            Assert::object($objectOrClass, 'Can not get a property of a non object. Variable of type "%s" was given.');
            Assert::notInstanceOf($objectOrClass, '\stdClass', 'Can not get a property of \stdClass.');
        }

        if (null === $refl = self::getReflectionClassWithProperty($objectOrClass, $propertyName)) {
            throw new \LogicException(sprintf('The property %s does not exist on %s or any of its parents.', $propertyName, get_class($objectOrClass)));
        }

        $property = $refl->getProperty($propertyName);
        $property->setAccessible(true);

        return $property;
    }
}
