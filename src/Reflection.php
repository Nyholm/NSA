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
     * @param object $object
     * @param string $propertyName
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public static function getProperty($object, $propertyName)
    {
        Assert::string($propertyName, 'Property name must be a string. Variable of type "%s" was given.');

        if (is_string($object)) {
            self::getStaticProperty($object, $propertyName)->getValue();
        }

        return self::getAccessibleReflectionProperty($object, $propertyName)->getValue($object);
    }

    /**
     * Set a property to an object.
     *
     * @param object $object
     * @param string $propertyName
     * @param mixed  $value
     *
     * @throws \InvalidArgumentException
     */
    public static function setProperty($object, $propertyName, $value)
    {
        Assert::string($propertyName, 'Property name must be a string. Variable of type "%s" was given.');

        if (is_string($object)) {
            self::getStaticProperty($object, $propertyName)->getValue();
        }

        return self::getAccessibleReflectionProperty($object, $propertyName)->setValue($object, $value);
    }

    /**
     * Invoke a method on a object and get the return values.
     *
     * @param object $object
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
        $object = array_shift($arguments);
        $methodName = array_shift($arguments);

        Assert::object($object, 'Can not invoke method of a non object. Variable of type "%s" was given.');
        Assert::notInstanceOf($object, '\stdClass', 'Can not get a method of \stdClass.');
        Assert::string($methodName, 'Method name has to be a string. Variable of type "%s" was given.');

        $refl = new \ReflectionClass($object);
        if (!$refl->hasMethod($methodName)) {
            throw new \LogicException(sprintf('The method %s::%s does not exist.', get_class($object), $methodName));
        }

        $method = $refl->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $arguments);
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
     * @param object $object
     * @param string $propertyName
     *
     * @return \ReflectionProperty
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException if the property is not found on the object
     */
    protected static function getAccessibleReflectionProperty($object, $propertyName)
    {
        Assert::object($object, 'Can not get a property of a non object. Variable of type "%s" was given.');
        Assert::notInstanceOf($object, '\stdClass', 'Can not get a property of \stdClass.');
        Assert::string($propertyName, 'Property name must be a string. Variable of type "%s" was given.');

        if (null === $refl = self::getReflectionClassWithProperty($object, $propertyName)) {
            throw new \LogicException(sprintf('The property %s does not exist on %s or any of its parents.', $propertyName, get_class($object)));
        }

        $property = $refl->getProperty($propertyName);
        $property->setAccessible(true);

        return $property;
    }
}
