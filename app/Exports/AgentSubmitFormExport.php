<?php

namespace App\Exports;

use App\Models\AgentSubmitForm;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AgentSubmitFormExport implements FromCollection, WithHeadings
{
    private $from;
    private $to;

    private $ids;

    public function __construct($from, $to, $ids)
    {
        $this->from = $from;
        $this->to = $to;
        $this->ids = $ids;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return AgentSubmitForm::when($this->ids, fn($query) => $query->whereIn('agent_submit_forms.id', $this->ids))
            ->when($this->from && $this->to, function ($query) {
                return $query->whereDate('agent_submit_forms.created_at', '>=', $this->from)
                    ->whereDate('agent_submit_forms.created_at', '<=', $this->to);
            })
            ->get()
            ->makeHidden(['updated_at']);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'Full Name',
            'Phone',
            'Email',
            'Address',
            'Website',
            'Created At',
        ];
    }
}
