<?php

namespace Yosko\WataBread;

use App\Utils\Tools;
use RuntimeException;
use Yosko\Watamelo\AbstractApplication;
use Yosko\Watamelo\AbstractController;

/**
 * Generic controller for CRUD actions on data using managers inheriting BreadManager
 */
class BreadController extends AbstractController
{
    public const TEMPLATE_PATH = 'vendor/yosko/watabread/src/tpl/';
    public const ITEMS_PER_PAGE = 50;

    public function __construct(AbstractApplication $app)
    {
        parent::__construct($app);

        $this->app()->view()->setParam("pluginTplPath", self::TEMPLATE_PATH);
    }

    private function secureModel($model)
    {
        if (!array_key_exists($model, $this->app()->manager('\Yosko\WataBread\Bread')->getModels())) {
            throw new RuntimeException("Unauthorized access to model \"" . $model . "\"");
        }
    }

    public function executeIndex()
    {
        $model = $this->parameters['model'];
        $this->app()->view()->setParam("model", $model);

        $this->secureModel($model);
        $manager = $this->app()->manager($model);

        foreach ($manager->getAvailableFilters() as $name => $filterInfo) {
            if (!empty($this->parameters['get'][$name])) {
                $manager->filters[$name] = $this->parameters['get'][$name];
            }
        }
        $data = $manager->getList(false);
        $this->app()->view()->setParam("data", $data);
        // TODO: only retreive current page data but build summary information
        $quantity = count($data);

        $page = $this->parameters['get']['page'] ?? 1;
        $this->app()->view()->setParam("page", $page);
        $this->app()->view()->setParam("lastPage", ceil($quantity/static::ITEMS_PER_PAGE));
        $this->app()->view()->setParam("itemsPerPage", static::ITEMS_PER_PAGE);

        $pagination = Tools::generatePagination($page, 0, static::ITEMS_PER_PAGE, $quantity);
        $this->app()->view()->setParam("pagination", $pagination);

        $paginationUrl = $this->app()->view()->buildRoute('data/%s?page=', $model);
        $this->app()->view()->setParam("paginationUrl", $paginationUrl);


        $this->app()->view()->renderView("collection", true, self::TEMPLATE_PATH);
    }

    public function executeForm()
    {
        $model = $this->parameters['model'];
        $this->secureModel($model);
        $manager = $this->app()->manager($model);

        $isCopy = isset($this->parameters['copy']) && $this->parameters['copy'] == 'true';

        if (isset($this->parameters['id'])) {
            $instance = $manager->get($manager->parseIdsString($this->parameters['id']));

            // instance based on given form data starts with existing instance data
            $formInstance = clone $instance;
        } else {
            $instance = null;

            // empty instance based on given form data
            $className = $manager->getModelClass();
            $formInstance = new $className();
        }

        if (isset($this->parameters['id']) && !$isCopy) {
            $properties = $manager->getUpdateProperties();
        } else {
            $instance = null;
            $properties = $manager->getInsertProperties();
            if (isset($this->parameters['get'])) {
                foreach ($this->parameters['get'] as $getParam => $getValue) {
                    if (in_array($getParam, $properties)) {
                        $formInstance->$getParam = $getValue;
                    }
                }
            }
        }

        // handle submitted form
        $errors = [];
        if (isset($_POST['submitForm'])) {
            foreach ($properties as $name) {
                $type = $manager->getPropertyType($name);

                // as Firefox doesn't support datetime-local type for inputs, for now we use a date one and a time one
                if ($type == BreadManager::TYPE_DATETIME && isset($_POST[$name . 'Date']) && isset($_POST[$name . 'Time'])) {
                    $formInstance->$name = $_POST[$name . 'Date'] . ' ' . $_POST[$name . 'Time'];

                    // passwords need to be hashed (and only updated if given a new one
                } elseif ($type == BreadManager::TYPE_PASSWORD) {
                    if (!empty($_POST[$name])) {
                        $formInstance->$name = password_hash($_POST[$name]);

                        if ($formInstance->$name === false) {
                            throw new RuntimeException('Could not hash password properly');
                        }
                    }

                    // foreign keys
                } elseif ($type == BreadManager::TYPE_INT && $manager->isForeignKey($name)) {
                    if (!empty($_POST[$name]) && is_numeric($_POST[$name])) {
                        $formInstance->$name = $_POST[$name];
                    } else {
                        $formInstance->$name = null;
                    }

                } elseif ($type == BreadManager::TYPE_BOOL) {
                    $formInstance->$name = !empty($_POST[$name]);

                } elseif ($type == BreadManager::TYPE_INT || $type == BreadManager::TYPE_FLOAT) {
                    $formInstance->$name = empty($_POST[$name]) ? null : $_POST[$name];

                } elseif ($type == BreadManager::TYPE_MONEY_CENTS) {
                    $formInstance->$name = empty($_POST[$name]) ? null : round($_POST[$name] * 100);

                    // other fields
                } elseif (isset($_POST[$name])) {
                    $formInstance->$name = $_POST[$name];
                }
            }

            // no error means we can save it to DB
            if (!in_array(true, $errors, true)) {
                if (isset($instance)) {
                    $result = $manager->update($formInstance);
                } else {
                    $result = $manager->add($formInstance);
                }


                if ($result === false) {
                    $errors['unhandled'] = true;
                } else {
                    header('Location: ' . $this->app()->view()->buildRoute('data/%s/%s', $model , $result));
                    exit;
                }
            }
        } elseif ($instance) {
            // no form submitted but an instance exists in DB: will be displayed in form
            $formInstance = clone $instance;
        }

        $foreignKeys = $manager->getForeignKeys();
        $foreignData = [];
        foreach ($foreignKeys as $key => $value) {
            $foreignManager = $this->app()->manager($value['class']);
            $foreignData[$value['class']] = $foreignManager->getList();
        }
        $this->app()->view()->setParam("foreignData", $foreignData);

        $this->app()->view()->setParam("instance", $instance);
        $this->app()->view()->setParam("formInstance", $formInstance);
        $this->app()->view()->renderView("form", true, self::TEMPLATE_PATH);
    }

    public function executeGet()
    {
        $model = $this->parameters['model'];
        $this->secureModel($model);
        $manager = $this->app()->manager($model);

        $instance = $manager->get($manager->parseIdsString($this->parameters['id']));
        $this->app()->view()->setParam("instance", $instance);

        $childrenData = [];
        foreach ($manager->getModelChildren() as $modelChild) {
            $managerChild = $this->app()->manager($modelChild);
            $managerChild->filters = ['foreign' => ['table' => $model, 'value' => $instance->id]];
            $childrenData[$modelChild] = $managerChild->getList(false);
        }
        $this->app()->view()->setParam("childrenData", $childrenData);

        $this->app()->view()->renderView("instance", true, self::TEMPLATE_PATH);
    }

    public function executeDelete()
    {
        $model = $this->parameters['model'];
        $this->secureModel($model);

        $manager = $this->app()->manager($model);

        if (!isset($this->parameters['id'])) {
            $this->app()->returnError("404");
        }

        $instance = $manager->get($manager->parseIdsString($this->parameters['id']));

        if (isset($_POST['submitDelete'])) {
            $errors['unhandled'] = empty($instance);

            if (!in_array(true, $errors)) {
                $result = $manager->deleteInstance($instance);

                if ($result === false) {
                    $errors['unhandled'] = true;
                } else {
                    header('Location: ' . $this->app()->view()->buildRoute('data/%s', $model));
                    exit;
                }
            }
        }

        $this->app()->view()->setParam("instance", $instance);
        $this->app()->view()->renderView("delete", true, self::TEMPLATE_PATH);
    }
}
