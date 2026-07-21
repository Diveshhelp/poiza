<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ESolution\DBEncryption\Traits\EncryptedAttribute;


class SmtpSetting extends Model
{
    use HasFactory;
    use EncryptedAttribute;

    protected $fillable = [
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'team_id',
        'uuid',
        'mail_signature'
    ];

    protected $encryptable = [
        'mail_host',
        'mail_username',
        'mail_password',
        'mail_from_address',
        'mail_from_name',
    ];
}