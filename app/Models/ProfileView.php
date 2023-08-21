<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileView extends Model
{
    use HasFactory;

    protected $fillable = [
        'viewer_id',
        'viewer_type',
        'viewable_id',
        'viewable_type',
        'ip_address',
        'user_agent',
        'referer',
        'device',
        'viewed_at',
    ];

    public function viewer()
    {
        return $this->morphTo();
    }

    public function viewable()
    {
        return $this->morphTo();
    }

    // Viewd Monthly Scope
    public function scopeMonthly($query)
    {
        return $query->where('viewed_at', '>=', now()->subMonth());
    }

    // Viewd Weekly Scope
    public function scopeWeekly($query)
    {
        return $query->where('viewed_at', '>=', now()->subWeek());
    }

    // Viewd Daily Scope
    public function scopeDaily($query)
    {
        return $query->where('viewed_at', '>=', now()->subDay());
    }

    // scopeViewedByScope
    public function scopeViewedByScope($query, $scope)
    {
        return $query->where('viewer_type', $scope);
    }

    // scopeViewedByScope
    public function scopeViewedIdScope($query, $scope, $id)
    {
        return $query->where(['viewer_type' => $scope, 'viewer_id' => $id]);
    }
}
