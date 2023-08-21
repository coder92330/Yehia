<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTourguide extends Model
{
    use HasFactory;

    protected $table = 'order_tourguide';

    protected $fillable = ['order_id', 'tourguide_id', 'status', 'agent_status'];

    protected $touches = ['order'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function tourguide()
    {
        return $this->belongsTo(Tourguide::class);
    }

    public function agentStatus($status)
    {
        return $this->agent_status === $status;
    }

    public function tourguideStatus($status)
    {
        return $this->status === $status;
    }

    public function approvedByBoth()
    {
        return $this->agentStatus('approved') && $this->tourguideStatus('approved');
    }

    public function rejectedByBoth()
    {
        return $this->agentStatus('rejected') && $this->tourguideStatus('rejected');
    }

    public function pendingByBoth()
    {
        return $this->agentStatus('pending') && $this->tourguideStatus('pending');
    }

    public function tourguideTakedAction()
    {
        return $this->status !== 'pending';
    }

    public function agentTakedAction()
    {
        return $this->agent_status !== 'pending';
    }
}
