WataBread
=====

WataBread is an overlay for the [Watamelo framework](https://github.com/yosko/watamelo/) to handle BREAD operations on all MVC layers for data stored in a SQL (SQLite) database.

**Warning**: this documentation is a work in progress. The codebase is currently way ahead of its documentation.

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

WataBread is composed of two parts: model overlay and Controller/View overlay.

For the following examples, let's assume we have a database tables:

```
                 .------------.
.--------.       | characters |
| movies |       |------------|
|--------|       | id         |
| id     |<------| movieId    |
| title  |       | name       |
'--------'       | birth      |
                 '------------'
```

### Model overlay
This one can be used alone.

To use it:
* your managers should inherit `\Yosko\WataBread\BreadManager` instead of `\Yosko\Watamelo\Manager`
* your entities (models) should inherit `\Yosko\WataBread\BreadModel` (no equivalent in Watamelo)
* you must configure your models and managers

Once done, you will be able to use BREAD operations on your managers.

#### Model definition and configuration
Firstly, define a class for your model (inherits `BreadModel`). You will probably only need to define your attributes and their types. Use the same name as your database columns:

  ```php
// Movie.php
namespace App\Models;

use Yosko\WataBread\BreadModel;

class Movie extends BreadModel
{
    public ?int $id = null;
    public string $title = '';
}


// Character.php
namespace App\Models;

use Yosko\WataBread\BreadModel;

class Character extends BreadModel
{
    public ?int $id = null;
    public string $name = '';
    public string $birth = '';

    // foreign data
    public ?int $movieId = null;
    public string $movieTitle = '';
}
  ```

Here are some of the base methods accessible for each `BreadModel`:
* `getId()`, `issetId()` and `isEven()`: methods to access and control attribute `->id` (if it exists).
* `getTitle()`: short text to display/describe this instance. You should probably override this.
* (static) `getClassName()`: as the name suggests.

#### Manager definition and configuration
Your managers inherits from `BreadManager`.

  ```php
// MovieManager.php
namespace App\Managers;

use App\Models\Movie;
use Yosko\WataBread\BreadManager;

class MovieManager extends BreadManager
{
    protected string $fetchClass = Movie::class;
    protected string $tableName = 'movies';
    protected string $tableAlias = 'm';
    protected array $defaultOrderBy = ['title' => 'asc'];

    protected array $properties = [
        'id' => [
            'type' => self::TYPE_INT,
            'primary' => true
        ],
        'title' => [
            'type' => self::TYPE_TEXT,
            'insert' => true,
            'update' => true,
            'required' => true
        ]
    ];
}
  ```

As you can see, the manager needs to know:
* the `BreadModel` class name (`$fetchCLass`): will be used as a `PDO::FETCH_CLASS`.
* the database table name and its alias (which will be used in SQL statements).
* the default `ORDER BY` to use (optional).
* a clear definition of all properties. For each:
  * **(required) type**: `BreadManager` data type. Each one corresponds to a PDO type and has an impact on the way the data will be displayed on the view.
  * **primary**: is this property part of the primary key?
  * **foreignKey**: is it a foreign key. *See detailed example below*.
  * **foreign**: is it a field retrieved from another joined table (not present in the current model's table). *See detailed example below*.
  * **required**: is it required (not null)
  * **insert**: is it possible to set it during an insert/create.
  * **update**: is it possible to edit it during an update.

In our example, a `Character` instance has a foreign key (`movieId`) and a foreign property (`movieTitle`, stored in another table but present in the class). Here is the way to define it properly:


  ```php
// CharacterManager.php
namespace App\Managers;

use App\Models\Character;
use Yosko\WataBread\BreadManager;

class CharacterManager extends BreadManager
{
    protected string $fetchClass = Character::class;
    protected string $tableName = 'characters';
    protected string $tableAlias = 'c';
    protected array $defaultOrderBy = ['name' => 'asc'];

    protected array $properties = [
        'id' => [
            'type' => self::TYPE_INT,
            'primary' => true
        ],
        'name' => [
            'type' => self::TYPE_TEXT,
            'insert' => true,
            'update' => true,
            'required' => true
        ],
        'birth' => [
            'type' => self::TYPE_DATE,
            'insert' => true,
            'update' => true
        ],
        'movieId' => [
            'type' => self::TYPE_INT,
            'foreignKey' =>  => [
                'class' => 'Movie',
                'key' => 'id',
                'fields' => ['title' => 'movieTitle']
            ],
        ],
        'movieTitle' => [
            'type' => self::TYPE_TEXT,
            'foreign' => ['class' => 'Movie', 'field' => 'title']
        ]
    ];
}
  ```

Note that there is some redundancy for foreign fields retrieved (the class, their name and their alias): this is for efficiency.

Main methods you can use on any `BreandManager`:
* 

Methods you can override in any `BreandManager` to extends its features:
* 

#### Documentation TODO
Here is a list of undocumented `BreadManager` features:
* `$availableFilters`/`getAvailableFilters()`
* Configuration file `BreadModels.json`/`$models` and especially each model's children
* 

### Controller/View overlay
This one can only be used if the model overlay is also configured.

To use it:
* your application should inherit `\Yosko\WataBread\BreadApplication` instead of `\Yosko\Watamelo\AbstractApplication`
* you must add all the necessary routes to BreadController:

```xml
    <route path="data/:string|model:" controller="\Yosko\WataBread\Bread" action=""/>
    <route path="data/:string|model:/:string|id:" controller="\Yosko\WataBread\Bread" action="get"/>
    <route path="data/:string|model:/:string|id:/edit" controller="\Yosko\WataBread\Bread" action="form"/>
    <route path="data/:string|model:/:string|id:/copy" controller="\Yosko\WataBread\Bread" action="form">
        <additional name="copy" value="true"/>
    </route>
    <route path="data/:string|model:/:string|id:/delete" controller="\Yosko\WataBread\Bread" action="delete"/>
    <route path="data/:string|model:/add" controller="\Yosko\WataBread\Bread" action="form"/>
```

`\Yosko\WataBread\BreadController`, `\Yosko\WataBread\BreadView` and all templates in `src/tpl/` will then be used to generate views for all BREAD operations on your entities.

## Dependancies

WataBread relies on:

* [Watamelo](https://github.com/yosko/watamelo/) (of course)
* [SqlGenerator](https://github.com/yosko/sql-generator/)
