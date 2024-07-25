<?php

namespace App\Exports;

use App\Models\Artisan\Artisan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class ArtisansExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $banned = 'banned';
        $artisan=DB::table('artisans')->join('categories', 'categories.id', '=', 'artisans.category_id')->select('artisans.first_name','artisans.last_name','artisans.trade_name','categories.category_name','artisans.gst','artisans.country_code','artisans.phone_number','artisans.email','artisans.street1','artisans.street2','artisans.zip','artisans.city','artisans.state','artisans.country','artisans.account_name','artisans.account_number','artisans.bank_name','artisans.ifsc','artisans.commission','artisans.awards','artisans.approval_note','artisans.rejection_note', DB::raw('(case when artisans.banned = 1 then "banned" when artisans.status="rejected" then "rejected" when artisans.status="pending" then "pending" else "approved" end) as status'))->get();
        return $artisan;
    }
    public function headings(): array
    {
        return [
            'first_name',
            'last_name',
            'trade_name',
            'category_name',
            'gst',
            'country_code',
            'phone_number',
            'email',
            'street1',
            'street2',
            'zip',
            'city',
            'state',
            'country',
            'account_name',
            'account_number',
            'bank_name',
            'ifsc',
            'commission',
            'awards',
            'approval_note',
            'rejection_note',
            'status'
        ];
    }
}
