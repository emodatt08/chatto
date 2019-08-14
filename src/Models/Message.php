<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Message extends Model
{
    public $table = "messages";
    public $primaryKey = "id";
    protected $fillable = ['text', 'sender'];

    
}
