<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MinisterPublicRegister;

class PrintRegisterController extends Controller
{
    public function print(Request $request)
    {
        $request->validate([
            'statistics_number' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'category' => 'required|string',
        ]);

        $titles = [
            'presidency' => 'سجل رئاسة النيابة العامة و السلطة القضائية',
            'secret' => 'السجل المرسلات السرية',
            'justice_minister' => 'سجل مرسلات وزير العدل و الوكيل العام للملك',
            'circulars' => 'سجل الدوريات و المناشير',
        ];

        $title = $titles[$request->category] ?? 'إستخراج السجل';

        $registers = MinisterPublicRegister::with(['incoming', 'correspondences'])
            ->where('category', $request->category)
            ->whereDate('registration_date', '>=', $request->start_date)
            ->whereDate('registration_date', '<=', $request->end_date)
            ->orderBy('registration_year', 'asc')
            ->orderBy('sequential_number', 'asc')
            ->get();

        $view = view('print-register', [
            'title' => $title,
            'registers' => $registers,
            'statistics_number' => $request->statistics_number,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'format' => $request->format,
        ]);

        if ($request->format === 'excel') {
            return response($view)
                ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="register_' . $request->statistics_number . '.xls"');
        }

        return $view;
    }
}
