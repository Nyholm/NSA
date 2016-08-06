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
     */
    public static function invokeMethod()
    {
        if (func_num_args() < 2) {
            throw new \LogicException('The method Reflection::invokeMethod need at least two arguments');
        }

        $arguments = func_get_args();
        $object = array_shift($arguments);
        $methodName = array_shift($arguments);

        $refl = new \ReflectionClass($object);
        $method = $refl->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $arguments);
    }

    /**
     * Get a reflection class that has this property.
     *
     * @param mixed  $objectOrClass
     * @param string $propertyName
     *
     * @return \ReflectionClass
     */
    protected static function getReflectionClassWithProperty($objectOrClass, $propertyName)
    {
        $refl = new \ReflectionClass($objectOrClass);
        if ($refl->hasProperty($propertyName)) {
            return $refl;
        }

        return self::getReflectionClassWithProperty(get_parent_class($objectOrClass), $propertyName);
    }

    /**
     * Get an reflection property that you can access directly.
     *
     * @param $object
     * @param $propertyName
     *
     * @return \ReflectionProperty
     */
    protected static function getAccessibleReflectionProperty($object, $propertyName)
    {
        $refl = self::getReflectionClassWithProperty($object, $propertyName);

        $property = $refl->getProperty($propertyName);
        $property->setAccessible(true);

        return $property;
    }
}
