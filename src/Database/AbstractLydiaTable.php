<?php

namespace Pythagus\LaravelLydia\Database;

use RuntimeException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Pythagus\LaravelLydia\Support\HasLongIdentifier;

/**
 * Base Lydia table for every datatables.
 * 
 * @author Damien MOLINA
 */
abstract class AbstractLydiaTable extends Migration {

    /**
     * Model key in the config file.
     *
     * @var string
     */
    protected $model ;

    /**
     * Cached table name.
     * 
     * @var string
     */
    private $table ;

    /**
     * Structure the given table.
     *
     * @param Blueprint $table
     * @return void
     */
    abstract protected function structure(Blueprint $table) ;

    /**
     * Get the model table name.
     *
     * @return string
     */
    private function getTableName() {
        if(is_null($this->table)) {
            if(is_null($this->model)) {
                throw new RuntimeException("Null model key") ;
            }
    
            $this->table = $this->tableNameModel($this->model) ;
        }

        return $this->table ;
    }

    /**
     * Get a model instance.
     *
     * @param string $model
     * @return Model
     */
    private function getModelInstance(string $model) {
        $class = lydia()->config('models.' . $model);

        if($class) {
            return app($class) ;
        }

        throw new RuntimeException("Unknown model $model");
    }

    /**
     * Get the table name for the given model config key.
     *
     * @param string $model
     * @return void
     */
    protected function tableNameModel(string $model) {
        return $this->getModelInstance($model)->getTable() ;
    }

    /**
     * Add the long_id column to the table.
     *
     * @param Blueprint $table
     * @return void
     */
    protected function longIdColumn(Blueprint $table) {
        /** @var HasLongIdentifier $instance */
        $instance = $this->getModelInstance($this->model) ;

        $table->string('long_id', $instance->getLongIdentifierLength()) ;
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create($this->getTableName(), function(Blueprint $table) {
            $this->structure($table) ;
        }) ;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists($this->getTableName()) ;
    }
}