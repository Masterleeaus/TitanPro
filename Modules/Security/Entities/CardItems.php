<?php
namespace Modules\Security\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Modules\Security\Entities\TrAccessCard;

class CardItems extends BaseModel
{
    use HasCompany;

    protected $table = 'tr_access_card_items';
    protected $guarded = ['id'];

    public function card()
    {
        return $this->belongsTo(TrAccessCard::class, 'card_id');
    }
}

