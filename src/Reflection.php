<?php

namespace Nyholm\SandReflection;

use Webmozart\Assert\Assert;

/**
 * Warning: This class should only be used with tests, fixtures or debug.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Reflection
{
    /**
     * Get a property of an object. If the property is static you may provide the class name (including namespace)
     * instead of an object.
     *
     * @param object|string $objectOrClass
     * @param string        $propertyName
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public static function getProperty($objectOrClass, $propertyName)
    {
        return static::getAccessibleReflectionProperty($objectOrClass, $propertyName)->getValue($objectOrClass);
    }

    /**
     * Set a property to an object. If the property is static you may provide the class name (including namespace)
     * instead of an object.
     *
     * @param object|string $objectOrClass
     * @param string        $propertyName
     * @param mixed         $value
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public static function setProperty($objectOrClass, $propertyName, $value)
    {
        static::getAccessibleReflectionProperty($objectOrClass, $propertyName)->setValue($objectOrClass, $value);
    }

    /**
     * Invoke a method on a object and get the return values. If the method is static you may provide the class
     * name (including namespace) instead of an object.
     *
     * @param object|string $objectOrClass
     * @param string        $methodName
     * @param mixed         ...$params
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public static function invokeMethod()
    {
        if (func_num_args() < 2) {
            throw new \LogicException('The method Reflection::invokeMethod need at least two arguments.');
        }

        $arguments = func_get_args();
        $objectOrClass = array_shift($arguments);
        $methodName = array_shift($arguments);

        Assert::string($methodName, 'Method name has to be a string. Variable of type "%s" was given.');
        if (is_string($objectOrClass)) {
            Assert::classExists($objectOrClass, 'Could not find class "%s"');
        } else {
            Assert::notInstanceOf($objectOrClass, '\stdClass', 'Can not get a method of \stdClass.');
            Assert::object($objectOrClass, 'Can not get a property of a non object. Variable of type "%s" was given.');
        }

        $refl = new \ReflectionClass($objectOrClass);
        if (!$refl->hasMethod($methodName)) {
            throw new \LogicException(sprintf('The method %s::%s does not exist.', get_class($objectOrClass), $methodName));
        }

        $method = $refl->getMethod($methodName);
        $method->setAccessible(true);

        if ($method->isStatic()) {
            // If it is a static call we should pass null as first parameter to \ReflectionMethod::invokeArgs
            $object = null;
        } else {
            $object = $objectOrClass;
            Assert::object($objectOrClass, 'Can not access non-static method without an object.');
        }

        return $method->invokeArgs($object, $arguments);
    }

    /**
     * Get a reflection class that has this property.
     *
     * @param string $class
     * @param string $propertyName
     *
     * @return \ReflectionClass|null
     *
     * @throws \InvalidArgumentException
     */
    protected static function getReflectionClassWithProperty($class, $propertyName)
    {
        Assert::string($class, 'First argument to Reflection::getReflectionClassWithProperty must be string. Variable of type "%s" was given.');
        Assert::classExists($class, 'Could not find class "%s"');

        $refl = new \ReflectionClass($class);
        if ($refl->hasProperty($propertyName)) {
            return $refl;
        }

        if (false === $parent = get_parent_class($class)) {
            // No more parents
            return;
        }

        return self::getReflectionClassWithProperty($parent, $propertyName);
    }

    /**
     * Get an reflection property that you can access directly.
     *
     * @param object|string $objectOrClass
     * @param string        $propertyName
     *
     * @return \ReflectionProperty
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException           if the property is not found on the object
     */
    protected static function getAccessibleReflectionProperty($objectOrClass, $propertyName)
    {
        Assert::string($propertyName, 'Property name must be a string. Variable of type "%s" was given.');

        if (is_string($objectOrClass)) {
            $class = $objectOrClass;
        } else {
            Assert::object($objectOrClass, 'Can not get a property of a non object. Variable of type "%s" was given.');
            Assert::notInstanceOf($objectOrClass, '\stdClass', 'Can not get a property of \stdClass.');
            $class = get_class($objectOrClass);
        }

        if (null === $refl = static::getReflectionClassWithProperty($class, $propertyName)) {
            throw new \LogicException(sprintf('The property %s does not exist on %s or any of its parents.', $propertyName, $class));
        }

        $property = $refl->getProperty($propertyName);
        $property->setAccessible(true);

        if (!$property->isStatic()) {
            Assert::object($objectOrClass, 'Can not access non-static property without an object.');
        }

        return $property;
    }
}
