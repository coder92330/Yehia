<?php

namespace App\Imports;

use App\Models\AgentSubmitForm;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AgentSubmitFormImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (AgentSubmitForm::where('email', $row['email'])->doesntExist()){
            return new AgentSubmitForm([
                'full_name' => $row['full_name'],
                'email'     => $row['email'],
                'phone'     => $row['phone'],
                'address'   => $row['address'],
                'website'   => $row['website'],
            ]);
        }
    }
}
