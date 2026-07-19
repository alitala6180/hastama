<?php

namespace App\Http\Controllers;


use App\Models\Attendance;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;



class AttendanceExportController extends Controller
{

    public function pdf(Request $request)
{
    abort_unless(
        $request->user()->can('reports.view'),
        403
    );


    $from = $request->from
        ?? now()->startOfMonth()->toDateString();


    $to = $request->to
        ?? now()->endOfMonth()->toDateString();



    $employeeId = $request->employee_id;



    $attendances = Attendance::with([
        'employee:id,first_name,last_name,employee_code'
    ])

    ->whereBetween(
        'work_date',
        [
            $from,
            $to
        ]
    )

    ->when(
        $employeeId,
        function($query, $employeeId){

            $query->where(
                'employee_id',
                $employeeId
            );

        }
    )

    ->orderBy('work_date')

    ->get();



    $summary = [

        'worked_minutes' => $attendances
            ->sum('worked_minutes'),


        'late_minutes' => $attendances
            ->sum('late_minutes'),


        'overtime_minutes' => $attendances
            ->sum('overtime_minutes'),

    ];



    $pdf = Pdf::loadView(
        'pdf.attendance-report',
        [

            'attendances' => $attendances,

            'summary' => $summary,

            'from' => $from,

            'to' => $to,

        ]
    );

    // Prefer mPDF if available (better RTL and Arabic shaping support)
    if (class_exists('\Mpdf\Mpdf')) {
        $html = view('pdf.attendance-report', [
            'attendances' => $attendances,
            'summary' => $summary,
            'from' => $from,
            'to' => $to,
        ])->render();

        // ensure temp and font dirs exist
        $mpdfTemp = storage_path('app/mpdf');
        if (!File::exists($mpdfTemp)) {
            File::makeDirectory($mpdfTemp, 0755, true);
        }

        $fontDirs = [public_path('fonts'), storage_path('fonts')];

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'tempDir' => $mpdfTemp,
            'fontDir' => array_merge((array) \Mpdf\Config\FontVariables::getDefaults()['fontDir'], $fontDirs),
            'fontdata' => array_merge((array) \Mpdf\Config\FontVariables::getDefaults()['fontdata'], [
                'vazir' => [
                    'R' => 'Vazir.ttf'
                ]
            ]),
            'default_font' => 'vazir',
        ]);

        $mpdf->WriteHTML($html);

        $pdfContent = $mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);

        $fileName = "attendance-report-{$from}-{$to}.pdf";

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    // Fallback to dompdf
    $pdf->setPaper('a4', 'landscape');
    $pdf->setOptions([
        'defaultFont' => 'vazir',
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true,
        'isFontSubsettingEnabled' => true,
    ]);

    return $pdf->download("attendance-report-{$from}-{$to}.pdf");
}

    public function export(Request $request): StreamedResponse
    {


        abort_unless(
            $request->user()->can('reports.view'),
            403
        );



        $from = $request->from
            ?? now()->startOfMonth()->toDateString();



        $to = $request->to
            ?? now()->endOfMonth()->toDateString();




        $employeeId = $request->employee_id;





        $fileName = "attendance-report-{$from}-{$to}.csv";





        return response()->streamDownload(function () use (
            $from,
            $to,
            $employeeId
        ) {



            $handle = fopen(
                'php://output',
                'w'
            );




            // UTF-8 برای اکسل فارسی
            fwrite(
                $handle,
                "\xEF\xBB\xBF"
            );




            fputcsv($handle,[

                'تاریخ',

                'کد پرسنلی',

                'نام',

                'نام خانوادگی',

                'ورود',

                'خروج',

                'کارکرد',

                'تاخیر',

                'اضافه کاری',

            ]);







            Attendance::with('employee')

                ->whereBetween(

                    'work_date',

                    [
                        $from,
                        $to
                    ]

                )


                ->when(
                    $employeeId,
                    function($query,$employeeId){

                        $query->where(
                            'employee_id',
                            $employeeId
                        );

                    }
                )


                ->orderBy(
                    'work_date'
                )


                ->chunk(
                    500,
                    function($rows) use ($handle){


                        foreach($rows as $attendance){



                            fputcsv($handle,[


                                $attendance->work_date
                                    ->format('Y-m-d'),


                                $attendance->employee->employee_code,


                                $attendance->employee->first_name,


                                $attendance->employee->last_name,


                                $attendance->check_in ?? '-',


                                $attendance->check_out ?? '-',


                                $attendance->worked_minutes,


                                $attendance->late_minutes,


                                $attendance->overtime_minutes,



                            ]);



                        }



                    }
                );





            fclose($handle);



        },$fileName);



    }



}