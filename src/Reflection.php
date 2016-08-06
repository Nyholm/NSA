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
        if (!is_string($object)) {
            return self::getAccessibleReflectionProperty($object, $propertyName)->getValue($object);
        }

        Assert::string($propertyName, 'Property name must be a string. Variable of type "%s" was given.');
        Assert::classExists($object, 'The class %s does not exist');

        if (null === $refl = self::getReflectionClassWithStaticProperty($object, $propertyName)) {
            throw new \LogicException(sprintf('The static property %s does not exist on %s or any of its parents.', $propertyName, $class));
        }

        $prop = $refl->getProperty($propertyName);
        $prop->setAccessible(true);

        return $prop->getValue(null);
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
        if (!is_string($object)) {
            self::getAccessibleReflectionProperty($object, $propertyName)->setValue($object, $value);

            return;
        }

        Assert::string($propertyName, 'Property name must be a string. Variable of type "%s" was given.');
        Assert::classExists($object, 'The class %s does not exist');

        if (null === $refl = self::getReflectionClassWithStaticProperty($object, $propertyName)) {
            throw new \LogicException(sprintf('The static property %s does not exist on %s or any of its parents.', $propertyName, $object));
        }

        $prop = $refl->getProperty($propertyName);
        $prop->setAccessible(true);
        $prop->setValue(null, $value);
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
     * Get a reflection class that has this static property.
     *
     * @param object|string  $objectOrClass
     * @param string $propertyName
     *
     * @return \ReflectionClass|null
     */
    protected static function getReflectionClassWithStaticProperty($class, $propertyName)
    {
        if (!is_string($class)) {
            return;
        }

        $refl = new \ReflectionClass($class);
        $properties = $refl->getStaticProperties();
        if (isset($properties[$propertyName])) {
            return $refl;
        }

        return self::getReflectionClassWithStaticProperty(get_parent_class($class), $propertyName);
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
