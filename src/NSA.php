<?php

namespace Nyholm;

use Webmozart\Assert\Assert;

/**
 * Warning: This class should only be used with tests, fixtures or debug.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class NSA
{
    /**
     * Get a constant of an object. You may provide the class name (including namespace) instead of an object.
     *
     * @param object|string $objectOrClass
     * @param string        $constantName
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public static function getConstant($objectOrClass, $constantName)
    {
        $class = $objectOrClass;

        if (!is_string($objectOrClass)) {
            Assert::object($objectOrClass, 'Can not get a constant of a non object. Variable of type "%s" was given.');
            $class = get_class($objectOrClass);
        }

        $refl = static::getReflectionClassWithConstant($class, $constantName);

        if (null === $refl) {
            throw new \LogicException(sprintf('The constant %s does not exist on %s or any of its parents.', $constantName, $class));
        }

        return $refl->getConstant($constantName);
    }

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

        // If it is a static call we should pass null as first parameter to \ReflectionMethod::invokeArgs
        $object = null;
        if (!$method->isStatic()) {
            $object = $objectOrClass;
            Assert::object($object, 'Can not access non-static method without an object.');
        }

        return $method->invokeArgs($object, $arguments);
    }

    /**
     * Get a reflection class that has this constant.
     *
     * @param string $class
     * @param string $constantName
     *
     * @return \ReflectionClass|null
     *
     * @throws \InvalidArgumentException
     */
    protected static function getReflectionClassWithConstant($class, $constantName)
    {
        Assert::string($class, 'First argument to Reflection::getReflectionClassWithConstant must be string. Variable of type "%s" was given.');
        Assert::classExists($class, 'Could not find class "%s"');

        $refl = new \ReflectionClass($class);
        if ($refl->hasConstant($constantName)) {
            return $refl;
        }

        if (false === $parent = get_parent_class($class)) {
            // No more parents
            return;
        }

        return self::getReflectionClassWithConstant($parent, $constantName);
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

        $class = $objectOrClass;
        if (!is_string($objectOrClass)) {
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

    /**
     * Get all property names on a class or object
     *
     * @param object|string $objectOrClass
     *
     * @return array of strings
     *
     * @throws \InvalidArgumentException
     */
    public static function getProperties($objectOrClass)
    {
        $class = $objectOrClass;
        if (!is_string($objectOrClass)) {
            Assert::object($objectOrClass, 'Can not get a property of a non object. Variable of type "%s" was given.');
            Assert::notInstanceOf($objectOrClass, '\stdClass', 'Can not get a property of \stdClass.');
            $class = get_class($objectOrClass);
        }

        $refl = new \ReflectionClass($class);
        $properties = $refl->getProperties();

        // check parents
        while (false !== $parent = get_parent_class($class)) {
            $parentRefl = new \ReflectionClass($parent);
            $properties = array_merge($properties, $parentRefl->getProperties());
            $class = $parent;
        }

        return array_map(function($reflectionProperty) {
            return $reflectionProperty->name;
        }, $properties);
    }
}
