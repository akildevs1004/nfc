<?php

namespace App\Http\Controllers\Customers\Reports;

use App\Exports\AlarmEventsExport;
use App\Exports\DeviceArmedExport;
use App\Exports\DeviceArmedReportExcelMapping;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Customers\Alarm\AlarmNotificationController;
use App\Http\Controllers\Customers\Alarm\DeviceArmedLogsController;
use App\Http\Controllers\Customers\CustomerAlarmEventsController;
use App\Models\AlarmEvents;
use App\Models\AlarmLogs;
use App\Models\AttendanceLog;
use App\Models\Company;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;

class AlarmReportsController extends Controller
{
    public function alarmEventsExportCsv(Request $request)
    {
        $model =   (new CustomerAlarmEventsController())->filter($request);
        $model->orderBy("alarm_start_datetime", "asc");
        $reports = $model->get();

        $file_name =  'Alarm Events from ' . $request->date_from . ' to ' . $request->date_to . ' .xlsx';

        return Excel::download((new AlarmEventsExport($reports)), $file_name);
    }
    public function alarmEventsDownloadPdf(Request $request)
    {
        $file_name =  'Alarm Events from ' . $request->date_from . ' to ' . $request->date_to . ' .pdf';

        $model =   (new CustomerAlarmEventsController())->filter($request);
        $model->orderBy("alarm_start_datetime", "asc");
        $reports = $model->get();
        $company = Company::whereId($request->company_id)->with('contact:id,company_id,number')->first();


        $pdf = Pdf::loadView('alarm_reports/alarm_events_list',  ['reports' => $reports, 'company' => $company, "request" => $request])->setPaper('A4', 'potrait');
        return $pdf->download($file_name);
    }
    public function alarmEventsPrintPdf(Request $request)
    {


        $model =   (new CustomerAlarmEventsController())->filter($request);
        $model->orderBy("alarm_start_datetime", "asc");
        $reports = $model->get();
        $company = Company::whereId($request->company_id)->with('contact:id,company_id,number')->first();


        $pdf = Pdf::loadView('alarm_reports/alarm_events_list',  ['reports' => $reports, 'company' => $company, "request" => $request])->setPaper('A4', 'potrait');
        return $pdf->stream('report.pdf');
    }
    //----------------------------------------DEVICE ARMED REPORTS 
    public function deviceArmedLogsPrintPdf(Request $request)
    {
        $model =   (new DeviceArmedLogsController())->filter($request);
        $model->orderBy("armed_datetime", "asc");
        $reports = $model->get();
        $company = Company::whereId($request->company_id)->with('contact:id,company_id,number')->first();


        $pdf = Pdf::loadView('alarm_reports/device_armed_list',  ['reports' => $reports, 'company' => $company, "request" => $request])->setPaper('A4', 'potrait');
        return $pdf->stream('report.pdf');
    }
    public function deviceArmedLogsDownloadPdf(Request $request)
    {
        $model =   (new DeviceArmedLogsController())->filter($request);
        $model->orderBy("armed_datetime", "asc");
        $reports = $model->get();
        $company = Company::whereId($request->company_id)->with('contact:id,company_id,number')->first();
        $file_name =  'Device Armed Reports from ' . $request->date_from . ' to ' . $request->date_to . ' .pdf';

        $pdf = Pdf::loadView('alarm_reports/device_armed_list',  ['reports' => $reports, 'company' => $company, "request" => $request])->setPaper('A4', 'potrait');
        return $pdf->download($file_name);
    }
    public function deviceArmedLogsExportExcel(Request $request)
    {
        $model =   (new DeviceArmedLogsController())->filter($request);
        $model->orderBy("armed_datetime", "asc");
        $reports = $model->get();

        $file_name =  'Device Armed Reports from ' . $request->date_from . ' to ' . $request->date_to . ' .xlsx';

        return Excel::download((new DeviceArmedExport($reports)), $file_name);
    }
    public function alarmEventsNotesDownload(Request $request)
    {

        if (!$request->filled('alarm_id')) return [];
        $alarm =   AlarmEvents::with([
            "device.customer.primary_contact",
            "device.customer.secondary_contact",
            "device.company.user",
            "notes.contact",
            "category",
            "device.customer.buildingtype",
            "zoneData",
            "security.user",
            "pinverifiedby"

        ])->where("id", $request->alarm_id)->first();

        $icons = (new AlarmNotificationController())->getGoogleMapIcons();


        $pdf = Pdf::loadView('alarm_reports/alarm_event_notes_track', compact('alarm', 'icons'))->setPaper('A4', 'potrait');
        return $pdf->download($request->alarm_id . "_event_track_notes.pdf");
    }
    public function alarmEventsNotesPrintPdf(Request $request)
    {

        if (!$request->filled('alarm_id')) return [];
        $alarm =   AlarmEvents::with([
            "device.customer.primary_contact",
            "device.customer.secondary_contact",
            "device.company.user",
            "notes.contact",
            "category",
            "device.customer.buildingtype",
            "zoneData",
            "security.user",
            "pinverifiedby"

        ])->where("id", $request->alarm_id)->first();

        $icons = (new AlarmNotificationController())->getGoogleMapIcons();


        $pdf = Pdf::loadView('alarm_reports/alarm_event_notes_track', compact('alarm', 'icons'))->setPaper('A4', 'potrait');
        return $pdf->stream($request->alarm_id . "_event_track_notes.pdf");
    }
    public function deviceArmedReportsDownloadExcel(Request $request)
    {

        $reports = $this->pdfArmedProcess($request);

        $file_name =  $request->date_from . ' to ' . $request->date_to . ' Armed Report.xlsx';

        return Excel::download((new DeviceArmedReportExcelMapping($reports)), $file_name);
    }
    public function deviceArmedReportsDownload(Request $request)
    {

        $reports = $this->pdfArmedProcess($request);

        $company = Company::whereId($request->company_id)->with('contact:id,company_id,number')->first();

        $pdf = Pdf::loadView("alarm_reports/armed_reports", compact('company', 'reports',  'request'))->setPaper('A4', 'potrait');

        return $pdf->download($request->date_from . ' to ' . $request->date_to . ' Armed Report.pdf');
    }
    public function deviceArmedReportsPrintPdf(Request $request)
    {

        $reports = $this->pdfArmedProcess($request);

        $company = Company::whereId($request->company_id)->with('contact:id,company_id,number')->first();

        $pdf = Pdf::loadView("alarm_reports/armed_reports", compact('company', 'reports',  'request'))->setPaper('A4', 'potrait');

        return $pdf->stream($request->date_from . ' to ' . $request->date_to . ' Armed Report.pdf');
    }

    function pdfArmedProcess($request)
    {
        $reports1 = (new DeviceArmedLogsController())->reportProcess($request);


        $reports = [];

        foreach ($reports1 as $report) {

            if (empty($report['customers'])) {
                $reports[] = [
                    "date" => $report['date'],

                    "customer_id" => "---",
                    "customer" => "---",
                    "city" => "---",
                    "armed" => "---",
                    "sos" => "---",
                    "armedHours" => "---",
                    "disarmHours" => "---",
                    "events_count" => 0,

                ];
            } else {
                foreach ($report['customers'] as $customer) {

                    $armedTotalHrs = $this->getArmedTotalDuration($customer['armed']);
                    $disarmTotalHrs = $this->getDisarmTotalDuration($customer['armed'], $report['date']);
                    $reports[] = [
                        "date" => $report['date'],
                        "armedHours" => $armedTotalHrs,
                        "disarmHours" => $disarmTotalHrs,

                        ...$customer
                    ];
                }
            }
        }

        return  $reports;
    }
    function getDisarmTotalDuration($array, $date)
    {
        $startOfDay = new DateTime($date);
        $startOfDay->setTime(0, 0, 0); // Set to 12:00 AM
        $endOfDay = clone $startOfDay;
        $endOfDay->setTime(23, 59, 59); // Set to 11:59 PM

        $totalDurationInSeconds = 0;

        foreach ($array as $item) {
            if ($item && isset($item['armed_datetime'], $item['disarm_datetime'])) {
                $armedTime = new DateTime($item['armed_datetime']);
                $disarmedTime = new DateTime($item['disarm_datetime']);

                $durationInSeconds = $disarmedTime->getTimestamp() - $armedTime->getTimestamp();

                if ($durationInSeconds > 0) {
                    $totalDurationInSeconds += $durationInSeconds;
                }
            }
        }

        if (!is_numeric($totalDurationInSeconds)) {
            return "---";
        }

        $totalDurationInSeconds = 24 * 60 * 60 - $totalDurationInSeconds;

        $totalHours = floor($totalDurationInSeconds / 3600);
        $totalMinutes = floor(($totalDurationInSeconds % 3600) / 60);

        $totalHours = str_pad($totalHours, 2, "0", STR_PAD_LEFT);
        $totalMinutes = str_pad($totalMinutes, 2, "0", STR_PAD_LEFT);

        return "$totalHours:$totalMinutes";
    }

    function getArmedTotalDuration($array)
    {
        $totalDurationInSeconds = 0;

        foreach ($array as $item) {
            if ($item && isset($item['armed_datetime'], $item['disarm_datetime'])) {
                $armedTime = new DateTime($item['armed_datetime']);
                $disarmedTime = new DateTime($item['disarm_datetime']);

                $duration = $disarmedTime->getTimestamp() - $armedTime->getTimestamp();
                $totalDurationInSeconds += $duration;
            }
        }

        if (!is_numeric($totalDurationInSeconds)) {
            return "00:00";
        }

        $totalHours = floor($totalDurationInSeconds / 3600);
        $totalMinutes = floor(($totalDurationInSeconds % 3600) / 60);

        $totalHours = str_pad($totalHours, 2, "0", STR_PAD_LEFT);
        $totalMinutes = str_pad($totalMinutes, 2, "0", STR_PAD_LEFT);

        return "$totalHours:$totalMinutes";
    }

    //----------------------------------------------------------------

    public function sample_pdf_pagenumbers()
    {
        $reports =   AttendanceLog::where("UserID", "1001")->get();
        $pdf =  App::make('dompdf.wrapper');

        //$pdf->getDomPDF()->set_option("enable_php", true);//do not delete this line 
        $pdf->loadView('alarm_reports/sample_with_page_numbers', compact('reports'))->setPaper('A4', 'potrait');
        return $pdf->stream('invoice.pdf');
    }
}
