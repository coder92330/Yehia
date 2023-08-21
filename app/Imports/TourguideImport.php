<?php

namespace App\Imports;

use App\Models\Setting;
use App\Models\Tourguide;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TourguideImport implements ToCollection, WithHeadingRow
{
    private string $password;

    public function __construct(string $password = 'password')
    {
        $this->password = $password;
    }

    /**
     * @param Collection $collection
     * @return Tourguide|void|null
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row){
            if (isset($row['email'])) {
                if (!Tourguide::where('email', $row['email'])->exists()) {
                    $tourguide = Tourguide::create([
                        'first_name'          => $row['first_name'] ?? null,
                        'last_name'           => $row['last_name'] ?? null,
                        'email'               => $row['email'],
                        'username'            => $row['username'] ?? null,
                        'password'            => Hash::make($this->password),
                        'city_id'             => $row['city_id'] ?? null,
                        "birthdate"           => $row['birthdate'] ?? null,
                        "age"                 => $row['age'] ?? null,
                        "education"           => $row['education'] ?? null,
                        "years_of_experience" => $row['years_of_experience'] ?? null,
                        "is_active"           => $row['is_active'] ?? true,
                        "is_online"           => $row['is_online'] ?? false,
                        "last_active"         => $row['last_active'] ?? null,
                        "facebook"            => $row['facebook'] ?? null,
                        "twitter"             => $row['twitter'] ?? null,
                        "instagram"           => $row['instagram'] ?? null,
                        "linkedin"            => $row['linkedin'] ?? null,
                        "gender"              => $row['gender'] ?? null,
                        "email_verified_at"   => $row['email_verified_at'] ?? now(),
                        "bio"                 => $this->setJson($row['bio_english'] ?? null, $row['bio_arabic'] ?? null),
                        "style_id"            => $row['style_id'] ?? null,
                    ]);
                    $tourguide->settings()->syncWithPivotValues(
                        Setting::whereNotIn("key", ["terms_and_conditions", "privacy_policy", "about_us", "contact_us", "faq"])->pluck("id")->toArray(),
                        ["value" => 1]);
                }
            }
        }
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
