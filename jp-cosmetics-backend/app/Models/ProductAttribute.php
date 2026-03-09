<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $table = 'product_attributes';

    protected $casts = [
        'unit_price' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute_images()
    {
        return $this->hasMany(ProductAttributeImage::class, 'attribute_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'product_attribute_id');
    }

    public function getAttributeDiscountAmount(): int
    {
        if (!$this->discount_type || !$this->discount_amount || !$this->unit_price) {
            return 0;
        }
    
        $amount = $this->discount_type === 'percentage'
            ? $this->unit_price * ($this->discount_amount / 100)
            : $this->discount_amount;
    
        return (int) round($amount, 0, PHP_ROUND_HALF_UP);
    }
    
    public function getDiscountPercentage(): int
    {
        if (!$this->discount_type || !$this->discount_amount || !$this->unit_price) {
            return 0;
        }
    
        if ($this->discount_type === 'percentage') {
            return (int) round($this->discount_amount, 0, PHP_ROUND_HALF_UP);
        }
    
        $percent = ($this->discount_amount / $this->unit_price) * 100;
    
        return (int) round($percent, 0, PHP_ROUND_HALF_UP);
    }
    
    public function getDiscountedPrice(): int
    {
        $price = $this->unit_price - $this->getAttributeDiscountAmount();
    
        return (int) round(max($price, 0), 0, PHP_ROUND_HALF_UP);
    }
    
}
