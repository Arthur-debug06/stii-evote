<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\DocumentTraits;

class signatory_action extends Model
{
    use HasFactory, SoftDeletes, DocumentTraits;
    
    protected $primaryKey = 'id';
    protected $table = 'signatory_action';
    protected $fillable = ['action_name', 'status'];
    public $timestamps = true;

    /**
     * Configure morph relations for this model
     */
    protected array $morphRelations = [
        'signatories' => [
            'type' => 'hasMany',
            'related' => set_signatory::class,
            'foreign_key' => 'signatory_action_id',
        ],
        'actionLogs' => [
            'type' => 'morphMany',
            'related' => 'App\Models\ActionLog',
            'morph_name' => 'trackable',
        ],
        'notifications' => [
            'type' => 'morphMany',
            'related' => 'App\Models\Notification',
            'morph_name' => 'documentable',
        ],
    ];
}
