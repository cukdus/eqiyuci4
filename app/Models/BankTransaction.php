<?php

namespace App\Models;

use CodeIgniter\Model;

class BankTransaction extends Model
{
    protected $table = 'bank_transactions';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        // Skema final integrasi parsingbca JSON
        'account_number', 'period', 'amount_formatted', 'info', 'type',
        'opening_balance_formatted', 'credit_total_formatted', 'debit_total_formatted', 'closing_balance_formatted'
    ];
}