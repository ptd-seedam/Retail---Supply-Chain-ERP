<?php

namespace App\Support;

use Illuminate\Database\Migrations\Migration;

/**
 * Base Migration Class
 *
 * Provides common functionality and properties for all migrations.
 * All migration files should extend this class.
 */
abstract class BaseMigration extends Migration
{
    /**
     * Table name for this migration
     *
     * @var string
     */
    protected string $table = '';

    /**
     * Get the table name
     *
     * @return string
     */
    public function getTableName(): string
    {
        return $this->table;
    }

    /**
     * Set the table name
     *
     * @param string $table
     * @return self
     */
    public function setTableName(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Common columns that appear in most tables
     */

    /**
     * Add ID column
     *
     * @var string COLUMN_ID
     */
    protected const COLUMN_ID = 'id';

    /**
     * Add created_by_id column
     *
     * @var string COLUMN_CREATED_BY_ID
     */
    protected const COLUMN_CREATED_BY_ID = 'created_by_id';

    /**
     * Add updated_by_id column
     *
     * @var string COLUMN_UPDATED_BY_ID
     */
    protected const COLUMN_UPDATED_BY_ID = 'updated_by_id';

    /**
     * Add deleted_by_id column
     *
     * @var string COLUMN_DELETED_BY_ID
     */
    protected const COLUMN_DELETED_BY_ID = 'deleted_by_id';

    /**
     * Add created_at timestamp
     *
     * @var string COLUMN_CREATED_AT
     */
    protected const COLUMN_CREATED_AT = 'created_at';

    /**
     * Add updated_at timestamp
     *
     * @var string COLUMN_UPDATED_AT
     */
    protected const COLUMN_UPDATED_AT = 'updated_at';

    /**
     * Add deleted_at timestamp
     *
     * @var string COLUMN_DELETED_AT
     */
    protected const COLUMN_DELETED_AT = 'deleted_at';

    /**
     * Common status values
     */

    /**
     * @var string STATUS_ACTIVE
     */
    protected const STATUS_ACTIVE = 'active';

    /**
     * @var string STATUS_INACTIVE
     */
    protected const STATUS_INACTIVE = 'inactive';

    /**
     * @var string STATUS_PENDING
     */
    protected const STATUS_PENDING = 'pending';

    /**
     * @var string STATUS_APPROVED
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @var string STATUS_PAID
     */
    protected const STATUS_PAID = 'paid';

    /**
     * Common field lengths
     */

    /**
     * @var int LENGTH_SHORT_CODE
     */
    protected const LENGTH_SHORT_CODE = 50;

    /**
     * @var int LENGTH_CODE
     */
    protected const LENGTH_CODE = 100;

    /**
     * @var int LENGTH_NAME
     */
    protected const LENGTH_NAME = 191;

    /**
     * @var int LENGTH_ADDRESS
     */
    protected const LENGTH_ADDRESS = 255;

    /**
     * @var int LENGTH_PHONE
     */
    protected const LENGTH_PHONE = 20;

    /**
     * Decimal precision for money columns
     *
     * @var int MONEY_PRECISION
     */
    protected const MONEY_PRECISION = 15;

    /**
     * Decimal scale for money columns
     *
     * @var int MONEY_SCALE
     */
    protected const MONEY_SCALE = 2;
}
