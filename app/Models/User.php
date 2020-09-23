<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = 'user';
    protected $fillable = ['user_name', 'first_name', 'viber_chat_id', 'status', 'user_birthday', 'card_name', 'city_id', 'email', 'social_networks', 'is_pregnant', 'child_birth', 'source', 'source_friend_name', 'club_expectation', 'phone_number', 'district_id', 'have_card'];

}