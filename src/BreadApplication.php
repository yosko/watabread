<?php

namespace Yosko\WataBread;

use Yosko\Watamelo\AbstractApplication;

abstract class BreadApplication extends AbstractApplication
{
    private BreadView $breadView;

    /**
     * Initialise the View object, including BreadView
     */
    public function initView(string $template, string $rootUrl, bool $ApacheURLRewriting)
    {
        parent::initView($template, $rootUrl, $ApacheURLRewriting);

        // Bread managers encapsulator for the view
        $this->breadView = new BreadView($this);
        $this->view->setParam('breadView', $this->breadView);
    }

    /**
     * TODO: not sure if necessary...
     */
    public function breadView(): BreadView
    {
        return $this->breadView;
    }
}