<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentMatch extends Model
{
    protected $table = 'payment_matches';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'registrasi_id',
        'bank_transaction_id',
        'type',
        'amount_formatted',
        'amount_numeric',
        'period',
        'matched_at',
        'notes',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}