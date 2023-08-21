<?php

namespace App\Imports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CompanyImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return Company|null
     */
    public function model(array $row): ?Company
    {
        if (!Company::where('email', $row['email'])->exists()) {
            return new Company([
                'country_id'   => optional($row)['country_id'] ?? null,
                'city_id'      => optional($row)['city_id'] ?? null,
                'package_id'   => optional($row)['package_id'] ?? null,
                'name'         => $this->setJson(optional($row)['name_english'] ?? null, optional($row)['name_arabic'] ?? null),
                'email'        => optional($row)['email'] ?? null,
                'website'      => optional($row)['website'] ?? null,
                'address'      => $this->setJson(optional($row)['address_english'] ?? null, optional($row)['address_arabic'] ?? null),
                'specialties'  => $this->setJson(optional($row)['specialties_english'] ?? null, optional($row)['specialties_arabic'] ?? null),
                'description'  => $this->setJson(optional($row)['description_english'] ?? null, optional($row)['description_arabic'] ?? null),
                "facebook"     => optional($row)['facebook'] ?? null,
                "twitter"      => optional($row)['twitter'] ?? null,
                "instagram"    => optional($row)['instagram'] ?? null,
                "linkedin"     => optional($row)['linkedin'] ?? null,
            ]);
        }
        return null;
    }

    private function setJson($en_value = null, $ar_value = null)
    {
        $data = [];
        if ($en_value) {
            $data['en'] = str_replace('"', '', $en_value);
        }
        if ($ar_value) {
            $data['ar'] = str_replace('"', '', $ar_value);
        }
        return $data;
    }
}
