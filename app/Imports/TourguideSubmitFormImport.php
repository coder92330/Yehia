<?php

namespace App\Imports;

use App\Models\TourguideSubmitForm;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TourguideSubmitFormImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (TourguideSubmitForm::where('email', $row['email'])->doesntExist()){
            return new TourguideSubmitForm([
                'full_name'     => $row['full_name'],
                'email'         => $row['email'],
                'phone'         => $row['phone'],
                'address'       => $row['address'],
                'gender'        => $row['gender'],
                'date_of_birth' => $row['date_of_birth'],
                'languages'     => json_decode($row['language_ids']),
            ]);
        }
        return null;
    }
}
