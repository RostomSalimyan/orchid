<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Client extends Model
{
    use HasFactory, AsSource, Filterable;

    protected $table = 'clients';
    protected $guarded = false;

    protected $fillable = ['phone', 'name', 'last_name', 'status', 'email', 'birthday', 'service_id', 'assessment', 'invoice_id'];

    protected $allowedSorts = [
        'status'
    ];

    protected $allowedFilters = [
        'phone'
    ];

    public function setPhoneAttribute($phoneCandidate)
    {
        $this->attributes['phone'] = str_replace('+', '', Phonenumber::make($phoneCandidate, 'RU')->formatE164());
    }

}














