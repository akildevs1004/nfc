<?php

namespace App\Console;

use App\Http\Controllers\AlramEventsController;
use App\Http\Controllers\Customers\Api\ApiAlarmDeviceTemperatureLogsController;
use App\Http\Controllers\Customers\CustomersController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\SDKController;
use App\Models\AccessControlTimeSlot;
use App\Models\Company;
use App\Models\DeviceActivesettings;
use App\Models\PayrollSetting;
use App\Models\ReportNotification;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // $schedule
        //     ->command('task:alarm_device_sensor_logs_csv')
        //     ->everyMinute()
        //     ->appendOutputTo(storage_path("kernal_logs/" . date("d-M-Y") . "-alarm-device-sensor-logs-csv.log")); // 

        // /*------------------------ */
        $monthYear = date("M-Y");


        $schedule
            ->command("task:files-delete-old-log-files")
            ->dailyAt('23:30')
            //->withoutOverlapping()
            ->appendOutputTo(storage_path("kernal_logs/$monthYear-delete-old-logs.log"))
            ->runInBackground()
        ;;

        // /*------------------------ */
        // $schedule->call(function () {
        //     (new ApiAlarmDeviceTemperatureLogsController)->createAlarmEventsJsonFile();
        // })->everyMinute();
        // /*------------------------ */



        // /*------------------------ */
        // $schedule->call(function () {
        //     (new AlramEventsController)->verifyOfflineDevices();
        // })->everyMinute();


        // /*------------------------ */
        // $schedule->call(function () {
        //     return (new CustomersController)->verifyArmedDeviceWithShopTime();
        // })->everyMinute()

        //     ->appendOutputTo(storage_path("kernal_logs/" . date("d-M-Y") . "-notification-armed-with-shop-time.log"));

        // /*------------------------ */
        // $schedule->call(function () {
        //     return (new CustomersController)->clearDeviceNotification();
        // })->dailyAt('00:00')

        //     ->appendOutputTo(storage_path("kernal_logs/" . date("d-M-Y") . "-notification-armed-with-shop-time.log"));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
