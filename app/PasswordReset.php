<?php

namespace App;
use DB;
use App\MyAppModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\funcsTrait;
use Illuminate\Validation\Rule;

class PasswordReset extends MyAppModel
{
    use funcsTrait;
    protected $table = 'password_resets';
    protected $primaryKey = 'id';
    public $timestamps = false;


    protected $fillable = [ 'email', 'token' ];


    protected $casts = [
    ];



}
