WataBread
=====

WataBread is an overlay for the [Watamelo framework](https://github.com/yosko/watamelo/) to handle BREAD operations on all MVC layers for data stored in a SQL (SQLite) database.

## BREAD

The base operations on persistant data (a derivate from CRUD):
* **B**: browse
* **R**: read
* **E**: edit
* **A**: add
* **D**: delete

## Requirements

WataBread requires **PHP 7.4** or above and **PDO** (PHP module).

## How to use

WataBread is composed of two parts:

### Model overlay
This one can be used alone.

To use it:
* your managers should inherit ```\WataBread\BreadManager``` instead of ```\Watamelo\Manager```
* your entities (models) should inherit ```\WataBread\BreadModel``` (no equivalent in Watamelo)
* you must configure your models and managers

Once done, you will be able to use BREAD operations on your managers.

TODO: detail configuration and possible uses.

### Controller/View overlay
This one can only be used if the model overlay is also configured.

To use it:
* your application should inherit ```\WataBread\BreadApplication``` instead of ```\Watamelo\AbstractApplication```
* you must add all the necessary routes to BreadController:

```xml
    <route path="data/:string|model:" controller="Bread" action=""/>
    <route path="data/:string|model:/:string|id:" controller="Bread" action="get"/>
    <route path="data/:string|model:/:string|id:/edit" controller="Bread" action="form"/>
    <route path="data/:string|model:/:string|id:/copy" controller="Bread" action="form">
        <additional name="copy" value="true"/>
    </route>
    <route path="data/:string|model:/:string|id:/delete" controller="Bread" action="delete"/>
    <route path="data/:string|model:/add" controller="Bread" action="form"/>
```

```\WataBread\BreadController```, ```\WataBread\BreadView``` and all templates in ```src/tpl/``` will then be used to generate views for all BREAD operations on your entities.

## Dependancies

WataBread relies on:

* [Watamelo](https://github.com/yosko/watamelo/) (of course)
* [SqlGenerator](https://github.com/yosko/sql-generator/)
