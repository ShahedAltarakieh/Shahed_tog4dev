<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeletedPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cart_id',
        'status',
        'amount',
        'referrer_id',
        'collection_team_id',
        'contract_id',
        'temp_id',
        'payment_type',
        'acquirer_message',
        'subscription_id',
        'acquirer_rrn',
        'resp_code',
        'resp_message',
        'signature',
        'token',
        'tran_ref',
        'lang',
        'send_email',
        'odoo_column_to_payments',
        'response',
        'country',
        'cliq_number',
        'name_on_card',
        'bank_issuer',
        'not_send_email',
        'created_at',
        'updated_at',
        'odoo_id',
        'source',
        'need_sync',
    ];
}
