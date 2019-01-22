<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorImportSchedule extends Model
{
    protected $table = 'vendor_import_schedules';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['vendor_id', 'user_id', 'reoccur_by', 'days', 'date', 'month', 'is_active'];
    
    
    function createOrUpdateSchedule($id, $userId, $inputArray){
        $dataArray['reoccur_by'] = $inputArray['reoccur_by'];
        $dataArray['vendor_id'] = $id;
        $dataArray['user_id'] = $userId;
        $dataArray['is_active'] = 1;
        if($dataArray['reoccur_by'] == 'daily'){
            $dataArray['days'] = '';
            $dataArray['date'] = '';
            $dataArray['month'] = '';
        }
        elseif ($dataArray['reoccur_by'] == 'weekly'){
            $dataArray['days'] = implode(",",$inputArray['days']);
            $dataArray['date'] = '';
            $dataArray['month'] = '';
        }
        elseif ($dataArray['reoccur_by'] == 'monthly'){
            $dataArray['days'] = '';
            $dataArray['date'] = $inputArray['date'];
            $dataArray['month'] = '';
        }
        else{
            $dateMonth = explode('/',$inputArray['date_month']);
            $dataArray['days'] = '';
            $dataArray['date'] = $dateMonth[0];
            $dataArray['month'] =$dateMonth[1];
        }
        
        $save = $this->updateOrCreate(['vendor_id'=>$id],$dataArray);
        return $save;
    }
    
}
