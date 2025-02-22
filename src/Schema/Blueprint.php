<?php

namespace AHAbid\EloquentCassandra\Schema;

use AHAbid\EloquentCassandra\Exceptions\CassandraNoPrimarySetException;
use Illuminate\Database\Schema\Blueprint as BaseBlueprint;

class Blueprint extends BaseBlueprint
{
    /** @var array */
    protected $primaryKeys = [];

    /** @var array */
    protected $clusterKeys = [];

    /** @var mixed */
    protected $withOptions;

    /**
     * Specify the primary key(s) for the table.
     *
     * @param  string|array $columns
     * @param  string       $name
     * @param  string|null  $algorithm
     * @return \Illuminate\Support\Fluent
     */
    public function primary($columns, $name = null, $algorithm = null)
    {
        $columns = (array) $columns;
        if (isset($columns[0]) && !is_array($columns[0])) {
            $columns[0] = (array) $columns[0];
        }

        if (count($columns) > 1) {
            $this->clusterKeys = array_slice($columns, 1);
        }

        $this->primaryKeys = $columns[0];

        return $this->createCommand('primary', compact('columns', 'algorithm'));
    }

    /**
     * Set With Options
     *
     * @param \Closure $callback
     */
    public function withOptions(\Closure $callback)
    {
        $this->withOptions = new WithOption;
        $callback($this->withOptions);
    }


    /**
     * Compile Primary
     *
     * @return string
     */
    public function compilePrimary()
    {
        if (empty($this->primaryKeys)) {
            throw new CassandraNoPrimarySetException('No primary key has been set for the table.');
        }

        $cql = sprintf(
            'primary key (("%s"), "%s") ',
            implode('", "', $this->primaryKeys),
            implode('", "', $this->clusterKeys)
        );

        return str_replace('), "")', '))', $cql);
    }

    /**
     * Compile With Options
     *
     * @return string
     */
    public function compileWithOptions()
    {
        if (empty($this->withOptions)) {
            return '';
        }

        return $this->withOptions->compile();
    }

    /**
     * Create a new ascii column on the table.
     *
     * @param  string $column
     * @return \Illuminate\Support\Fluent
     */
    public function ascii($column)
    {
        return $this->addColumn('ascii', $column);
    }

    /**
     * Create a new bigint column on the table.
     *
     * @param  string $column
     * @return \Illuminate\Support\Fluent
     */
    public function bigint($column)
    {
        return $this->addColumn('bigint', $column);
    }

    /**
     * Create a new blob column on the table.
     *
     * @param  string $column
     * @return \Illuminate\Support\Fluent
     */
    public function blob($column)
    {
        return $this->addColumn('blob', $column);
    }

    /**
     * Create a new boolean column on the table.
     *
     * @param  string $column
     * @return \Illuminate\Support\Fluent
     */
    public function boolean($column)
    {
        return $this->addColumn('boolean', $column);
    }

    /**
     * Create a new counter column on the table.
     *
     * @param  string $column
     * @return \Illuminate\Support\Fluent
     */
    public function counter($column)
    {
        return $this->addColumn('counter', $column);
    }

    /**
     * Create a new frozen column on the table.
     *
     * @param  string $column
     * @return \Illuminate\Support\Fluent
     */
    public function frozen($column)
    {
        return $this->addColumn('frozen', $column);
    }

    /**
     * Create a new inet column on the table.
     *
     * @param  string $column
     * @return \Illuminate\Support\Fluent
     */
    public function inet($column)
    {
        return $this->addColumn('inet', $column);
    }

    /**
     * Create a new int column on the table.
     *
     * @param  string $column
     * @return \Illuminate\Support\Fluent
     */
    public function int($column)
    {
        return $this->addColumn('int', $column);
    }

    /**
     * Create an integer column on the table.
     *
     * @param  string  $column
     * @param  bool  $autoIncrement
     * @param  bool  $unsigned
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function integer($column, $autoIncrement = false, $unsigned = false)
    {
        if ($autoIncrement == true) {
            return $this->uuid($column);
        }

        return $this->addColumn('int', $column);
    }

    /**
     * Create a new list column on the table.
     *
     * @param  string $column
     * @param  string $collectionType
     * @return \Illuminate\Support\Fluent
     */
    public function listCollection($column, $collectionType)
    {
        return $this->addColumn('list', $column, compact('collectionType'));
    }

    /**
     * Create a new map column on the table.
     *
     * @param  string $column
     * @param  string $collectionType1
     * @param  string $collectionType2
     * @return \Illuminate\Support\Fluent
     */
    public function mapCollection($column, $collectionType1, $collectionType2)
    {
        return $this->addColumn('map', $column, compact('collectionType1', 'collectionType2'));
    }

    /**
     * Create a new set column on the table.
     *
     * @param  string $column
     * @param  string $collectionType
     * @return \Illuminate\Support\Fluent
     */
    public function setCollection($column, $collectionType)
    {
        return $this->addColumn('set', $column, compact('collectionType'));
    }

    /**
     * Create a new timestamp column on the table.
     *
     * @param  string  $column
     * @param  int  $precision
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function timestamp($column, $precision = 0)
    {
        return $this->addColumn('timestamp', $column, compact('precision'));
    }

    /**
     * Create a new timeuuid column on the table.
     *
     * @param  string $column
     * @return \Illuminate\Support\Fluent
     */
    public function timeuuid($column)
    {
        return $this->addColumn('timeuuid', $column);
    }

    /**
     * Create a new tuple column on the table.
     *
     * @param  string $column
     * @param  string $tuple1type
     * @param  string $tuple2type
     * @param  string $tuple3type
     * @return \Illuminate\Support\Fluent
     */
    public function tuple($column, $tuple1type, $tuple2type, $tuple3type)
    {
        return $this->addColumn('tuple', $column, compact('tuple1type', 'tuple2type', 'tuple3type'));
    }

    /**
     * Create a new varchar column on the table.
     *
     * @param  string $column
     * @return \Illuminate\Support\Fluent
     */
    public function varchar($column)
    {
        return $this->addColumn('varchar', $column);
    }

    /**
     * Create a new varint column on the table.
     *
     * @param  string $column
     * @return \Illuminate\Support\Fluent
     */
    public function varint($column)
    {
        return $this->addColumn('varint', $column);
    }
}