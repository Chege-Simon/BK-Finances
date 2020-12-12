<?php

namespace App\Models;
use App\Models\Organisation;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $table = 'accounts';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_account_number',
        'organisation_id',
        'user_id',
    ];
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function organisation(){
        return $this->belongsTo('App\Models\Organisation');
    }

    public function transactions(){
        return $this->hasMany('App\Models\Transaction');
    }
}
