<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\applied_candidacy;

class partylist extends Model
{
    use SoftDeletes;
    protected $table = 'partylist';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = ['partylist_name','description','partylist_image','status'];

    public function applied_candidacies()
    {
        return $this->hasMany(applied_candidacy::class, 'partylist_id');
    }
}
    