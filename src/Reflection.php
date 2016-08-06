<?php

namespace Nyholm\Reflection;

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
     */
    public static function getProperty($object, $propertyName)
    {
        return self::getAccessibleReflectionProperty($object, $propertyName)->getValue($object);
    }

    /**
     * Set a property to an object.
     *
     * @param object $object
     * @param string $propertyName
     * @param mixed  $value
     */
    public static function setProperty($object, $propertyName, $value)
    {
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
     * @throws \LogicException
     */
    public static function invokeMethod()
    {
        if (func_num_args() < 2) {
            throw new \LogicException('The method Reflection::invokeMethod need at least two arguments.');
        }

        $arguments = func_get_args();
        $object = array_shift($arguments);
        $methodName = array_shift($arguments);

        if (!is_object($object)) {
            throw new \LogicException(sprintf('Can not invoke method of a non object. Variable of type "%s" was given..', gettype($object)));
        }

        if (!is_string($methodName)) {
            throw new \LogicException(sprintf('Method name has to be a string. Variable of type "%s" was given..', gettype($methodName)));
        }

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
     * @throws \LogicException if the property is not found
     */
    protected static function getAccessibleReflectionProperty($object, $propertyName)
    {
        if (!is_object($object)) {
            throw new \LogicException(sprintf('Can not get a property of a non object. Variable of type "%s" was given.', gettype($object)));
        }

        if (null === $refl = self::getReflectionClassWithProperty($object, $propertyName)) {
            throw new \LogicException(sprintf('The property %s does not exist on %s or any of its parents.', $propertyName, get_class($object)));
        }

        $property = $refl->getProperty($propertyName);
        $property->setAccessible(true);

        return $property;
    }
}
