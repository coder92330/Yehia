<?php

namespace App\Exports;

use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CompanyExport implements FromCollection, WithHeadings
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
		// Name is json field in database, i want to get name in english and arabic

        return Company::selectRaw(
            'companies.id, JSON_UNQUOTE(JSON_EXTRACT(companies.name, "$.en")) as name_en, JSON_UNQUOTE(JSON_EXTRACT(companies.name, "$.ar")) as name_ar,
            		  companies.email, JSON_UNQUOTE(JSON_EXTRACT(packages.name, "$.en")) as package_name_en, JSON_UNQUOTE(JSON_EXTRACT(packages.name, "$.ar")) as package_name_ar,
            		  JSON_UNQUOTE(JSON_EXTRACT(cities.name, "$.en")) as city_name_en, JSON_UNQUOTE(JSON_EXTRACT(cities.name, "$.ar")) as city_name_ar,
            		  companies.website, JSON_UNQUOTE(JSON_EXTRACT(companies.address, "$.en")) as address_en, JSON_UNQUOTE(JSON_EXTRACT(companies.address, "$.ar")) as address_ar,
            		  JSON_UNQUOTE(JSON_EXTRACT(companies.specialties, "$.en")) as specialties_en, JSON_UNQUOTE(JSON_EXTRACT(companies.specialties, "$.ar")) as specialties_ar,
            		  JSON_UNQUOTE(JSON_EXTRACT(companies.description, "$.en")) as description_en, JSON_UNQUOTE(JSON_EXTRACT(companies.description, "$.ar")) as description_ar,
            		  companies.facebook, companies.twitter, companies.instagram, companies.linkedin, companies.city_id, companies.package_id')
            ->leftJoin('packages', 'packages.id', '=', 'companies.package_id')
            ->leftJoin('cities', 'cities.id', '=', 'companies.city_id')
            ->when($this->ids, fn($query) => $query->whereIn('companies.id', $this->ids))
            ->when($this->from && $this->to, function ($query) {
                return $query->whereDate('companies.created_at', '>=', $this->from)
                    ->whereDate('companies.created_at', '<=', $this->to);
            })
            ->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Name (English)',
            'Name (Arabic)',
            'Email',
            'Package Name (English)',
            'Package Name (Arabic)',
            'City Name (English)',
            'City Name (Arabic)',
            'Website',
            'Address (English)',
            'Address (Arabic)',
            'Specialties (English)',
            'Specialties (Arabic)',
            'Description (English)',
            'Description (Arabic)',
            'Facebook',
            'Twitter',
            'Instagram',
            'Linkedin',
            'City ID',
            'Package ID',
        ];
    }
}
