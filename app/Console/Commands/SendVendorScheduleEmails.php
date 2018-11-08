<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Library\VendorProductsImportHelper;

//Models
use App\Models\VendorImportSchedule;
use App\Models\Vendor;


class SendVendorScheduleEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:sendvendorschedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send vendor schedule email with products list';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $currentTime = Carbon::now();
        
        //Schedule for selective
        $schedules = VendorImportSchedule::where('vendor_id', '!=', 0)->get();
        $vendorIds = [];
        foreach ($schedules as $schedule){
            $vendorIds[] = $schedule->vendor_id;
            $vendor = Vendor::find($schedule->vendor_id);
            //If vendor email does not exist.
            $vendorEmail = $vendor->email ? $vendor->email:$vendor->email_2;
            if($vendorEmail != '') {
                //If email schedule weekly
                if ($schedule->reoccur_by == 'weekly') {
                    $days = explode(',', $schedule->days);
                    foreach ($days as $day) {
                        //Check if schedule day is equal to current day.
                        if ($day == lcfirst($currentTime->format('l'))) {

                            VendorProductsImportHelper::exportExcel($schedule->vendor_id, $vendorEmail);
                        }
                    }
                }
                //If email schedule monthly
                else {
                    if ($schedule->date == $currentTime->format('d')) {
                        VendorProductsImportHelper::exportExcel($schedule->vendor_id, $vendorEmail);
                    }
                }
            }

        }

        //Schedule for all
        $vendors = Vendor::whereNotIn('id', $vendorIds)->get();//get those vendors who don't have schedule
        $schedule = VendorImportSchedule::where('vendor_id', 0)->first();
        foreach ($vendors as $vendor){
            $vendorEmail = $vendor->email ? $vendor->email:$vendor->email_2;
            //If vendor emails are not empty
            if($vendorEmail != '') {
                //If email schedule weekly
                if ($schedule->reoccur_by == 'weekly') {
                    $days = explode(',', $schedule->days);
                    foreach ($days as $day) {
                        //Check if schedule day is equal to current day.
                        if ($day == lcfirst($currentTime->format('l'))) {
                            VendorProductsImportHelper::exportExcel($vendor->id, $vendorEmail);
                        }
                    }
                }
                //If email schedule monthly
                else {
                    if ($schedule->date == $currentTime->format('d')) {
                        VendorProductsImportHelper::exportExcel($vendor->id, $vendorEmail);
                    }
                }
            }
        }

    }
}
