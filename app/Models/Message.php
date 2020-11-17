<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Message
 * @property integer id
 * @property integer from
 * @property integer to
 * @property string content
 * @property \DateTime created_at
 */

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['from', 'to', 'content'];

}
