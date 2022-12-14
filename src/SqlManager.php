<?php

namespace Yosko\WataBread;

use PDO;
use PDOException;
use Yosko\Watamelo\AbstractApplication;
use Yosko\Watamelo\AbstractManager;
use Yosko\SqlGenerator;

/**
 * Basic manager to handle access to entities stored in SQL databases
 */
abstract class SqlManager extends AbstractManager
{
    protected array $tables;
    protected string $prefix;

    public function __construct(AbstractApplication $app)
    {
        parent::__construct($app);

        //TODO: remove dependency to
        $this->prefix = $this->app()->config()->get('db.prefix');

        $sql = sprintf("SELECT name FROM sqlite_master WHERE type='table' AND name LIKE '%s%%'", $this->prefix);
        $qry = $this->dao->prepare($sql);
        $qry->execute();
        $results = $qry->fetchAll(PDO::FETCH_OBJ);
        
        $this->tables = [];
        foreach ($results as $table) {
            $this->tables[substr($table->name, strlen($this->prefix))] = $table->name;
        }
    }

    /**
     * Initialize a SqlGenerator object with the default db access and table list
     * @return SqlGenerator object
     */
    public function newSqlGenerator()
    {
        return new SqlGenerator($this->dao, $this->tables);
    }

    /**
     * Execute given SqlGenerator query within a transaction
     * @param SqlGenerator $sql
     * @param bool $returnLastInsertId
     * @return int|bool true or last inserted id on success, false on failure
     */
    public function executeTransaction(SqlGenerator $sql, bool $returnLastInsertId = false)
    {
        $alreadyInTransaction = $sql->inTransaction();
        if (!$alreadyInTransaction) {
            $sql->beginTransaction();
        }
        try {
            $sql->execute();
            if ($returnLastInsertId) {
                $result = $sql->lastInsertId();
            } else {
                $result = true;
            }

            if (!$alreadyInTransaction) {
                $sql->commit();
            }

        } catch (PDOException $e) {
            if (!$alreadyInTransaction) {
                $sql->rollback();
            }
            $this->app()->logException($e);
            return false;
        }

        return $result;
    }
}
