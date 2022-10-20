<?php

namespace Yosko\WataBread;

use LogicException;
use Yosko\Watamelo\AbstractComponent;
use Yosko\ViewFormatter;

class BreadView extends AbstractComponent
{

    /**
     * BreadManager for the given BreadModel class
     * @param BreadModel $object
     * @return BreadManager
     */
    protected function getManager(BreadModel $object): BreadManager
    {
        $manager = $this->app->manager($object::getClassName());
        if (!($manager instanceof BreadManager)) {
            throw new LogicException('Manager for "' . $object::getClassName() . '" can\'t be used by BreadView');
        }
        return $manager;
    }

    /**
     * Is the given property a defined field coming from DB
     * @param BreadModel $object
     * @param string $field
     * @return bool
     */
    public function isPropertyDefined(BreadModel $object, string $field): bool
    {
        return $this->getManager($object)->isPropertyDefined($field);
    }

    /**
     * indicates type (BreadManager::TYPE_*) for given field
     * @param BreadModel $object
     * @param string $field
     * @return int
     */
    public function getPropertyType(BreadModel $object, string $field): int
    {
        return $this->getManager($object)->getPropertyType($field);
    }

    /**
     * Determines if instances pre-existed (coming from DB)
     * @param BreadModel $object
     * @return bool
     */
    public function instanceExists(BreadModel $object): bool
    {
        foreach ($this->getManager($object)->getPrimaryKeys() as $pk) {
            if (empty($object->$pk)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get hidden fields of an object to include un a form
     * @param BreadModel $object
     * @return array
     */
    public function getHidden(BreadModel $object): array
    {
        return $this->getManager($object)->getNonForeignPrimaryKeys();
    }

    /**
     * Determine if a field should be displayed or not (and if it should be an <input type="hidden"> in forms
     * @param BreadModel $object
     * @param string $field
     * @return bool
     */
    public function isHidden(BreadModel $object, string $field): bool
    {
        return in_array($field, $this->getHidden($object));
    }

    public function isPropertyWritable(BreadModel $object, string $field): bool
    {
        $manager = $this->getManager($object);
        if ($manager->isPropertyDefined('id') && empty($object->id)) {
            return in_array($field, $manager->getInsertProperties());
        } else {
            return in_array($field, $manager->getUpdateProperties());
        }
    }

    public function isPropertyReadable(BreadModel $object, string $field): bool
    {
        return $this->getManager($object)->isReadable($field);
    }

    public function isPropertySecondary(BreadModel $object, string $field): bool
    {
        return $this->getManager($object)->isPropertySecondary($field);
    }

    /**
     * Formats data based on type (used for date formats, currencies, etc.)
     * @param BreadModel $object
     * @param string $property property name
     * @return string
     */
    public function formated(BreadModel $object, string $property): string
    {
        return self::formatedType($object->$property, $this->getPropertyType($object, $property));
    }

    /**
     * Formats data based on type (used for date formats, currencies, etc.)
     * @param mixed $value data
     * @param int $type BreadManager::TYPE_* constant
     * @return string
     */
    public function formatedType($value, int $type = BreadManager::TYPE_TEXT)
    {
        if (is_null($value)) {
            return '';
        }

        switch ($type) {
            case BreadManager::TYPE_DATE:
                $output = ViewFormatter::formatDate($value);
                break;
            case BreadManager::TYPE_DATETIME:
                $output = ViewFormatter::formatDateTime($value);
                break;
            case BreadManager::TYPE_MONEY:
                $output = ViewFormatter::formatCurrency($value);
            case BreadManager::TYPE_MONEY_CENTS:
                $output = ViewFormatter::formatCurrency($value / 100);
                break;
            case BreadManager::TYPE_BOOL:
                $output = $value ? 'Oui' : 'Non';
                break;
            default:
                $output = $value;
        }
        return $output;
    }

    public function getForegroundColor(BreadModel $object, string $property): string
    {
        return $this->getManager($object)->getForegroundColor($object, $property);
    }

    public function getBackgroundColor(BreadModel $object, string $property): string
    {
        return $this->getManager($object)->getBackgroundColor($object, $property);
    }

    /**
     * Give a summary for the given list of BreadModel objects
     * @param array $data
     * @param string $context class name for context (some collection are displayed in other BreadModel types context)
     * @return string
     */
    public function getSummary(array $data, string $context): string
    {
        if (empty($data)) {
            return '';
        }

        $firstInstance = reset($data);
        $manager = $this->getManager($firstInstance);
        return $manager->getSummary($data, $context);
    }

    /**
     * Returns a link to corresponding resource (if there is one)
     * @param BreadModel $object
     * @param string $property
     * @return string
     */
    public function getHyperlink(BreadModel $object, string $property): string
    {
        $manager = $this->getManager($object);
        if ($manager->isForeignData($property)) {
            $info = $manager->getForeignData($property);
            $fManager = $this->app->manager($info['class']);
            $keys = $fManager->getPrimaryKeys();
            if (count($keys) == 1) {
                $key = reset($keys);
                $key .= $info['class'];
                if (isset($object->$key)) {
                    return $this->app()->view()->buildRoute('data/%s/%d', $info['class'], $object->$key);
                }
            }
        }
        return '';
    }

    public function modelRoute(string $model, string $additional = ''): string
    {
        return $this->app()->view()->buildRoute('data/%s/%s', $model, $additional);
    }

    public function instanceRoute(BreadModel $object, string $action = ''): string
    {
        $manager = $this->getManager($object);
        $idsStr = $manager->getIdString($object);
        return $this->app()->view()->buildRoute('data/%s/%s/%s', $object::getClassName(), $idsStr, $action);
    }

    /**
     * @param string $model
     * @param BreadModel|null $instance
     * @return array
     */
    public function getCustomActions(string $model, BreadModel $instance = null): array
    {
        $manager = $this->app->manager($model);
        return $manager->getCustomActions($instance);
    }

    public function getAvailableFilters(string $model): array
    {
        $manager = $this->app->manager($model);
        $filters = $manager->getAvailableFilters();
        foreach ($filters as $property => $filterInfo) {
            $propertyInfo = $manager->getProperty($property);

            if (isset($propertyInfo['foreignKey'])) {
                $fClass = $propertyInfo['foreignKey']['class'];
                $fManager = $this->app->manager($fClass);
                $filters[$property]['class'] = $fClass;
                $filters[$property]['data'] = $fManager->getList();
            }
        }

        return $filters;
    }

    public function getForeignKeys($model)
    {
        $manager = $this->app->manager($model);
        return $manager->getForeignKeys();
    }

    public function getInstance($model)
    {
        $manager = $this->app->manager($model);
        $class = $manager->getModelClass();
        return new $class();
    }
}