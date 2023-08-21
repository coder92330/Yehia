<?php

namespace App\Exports;

use App\Models\Tourguide;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TourguideExport implements FromCollection, WithHeadings
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

    public function collection()
    {
        return DB::table('tourguides')->selectRaw('tourguides.id, tourguides.first_name, tourguides.last_name,
                JSON_UNQUOTE(JSON_EXTRACT(cities.name, "$.en")) as city_name_en, JSON_UNQUOTE(JSON_EXTRACT(cities.name, "$.ar")) as city_name_ar,
                tourguides.email, tourguides.username, tourguides.gender, tourguides.birthdate, tourguides.age, tourguides.education,
                tourguides.years_of_experience, tourguides.facebook, tourguides.twitter, tourguides.instagram, tourguides.linkedin, tourguides.email_verified_at,
                JSON_UNQUOTE(JSON_EXTRACT(tourguides.bio, "$.en")) as bio_en, JSON_UNQUOTE(JSON_EXTRACT(tourguides.bio, "$.ar")) as bio_ar, tourguides.style_id')
            ->leftJoin('cities', 'cities.id', '=', 'tourguides.city_id')
            ->when($this->ids, fn($query) => $query->whereIn('tourguides.id', $this->ids))
            ->when($this->from && $this->to, function ($query) {
                return $query->whereDate('tourguides.created_at', '>=', $this->from)
                    ->whereDate('tourguides.created_at', '<=', $this->to);
            })
            ->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'First Name',
            'Last Name',
            'City Name (English)',
            'City Name (Arabic)',
            'Email',
            'Username',
            'Gender',
            'Birthdate',
            'Age',
            'Education',
            'Years of Experience',
            'Facebook',
            'Twitter',
            'Instagram',
            'Linkedin',
            'Email Verified At',
            'Bio (English)',
            'Bio (Arabic)',
            'Style ID',
        ];
    }
}
