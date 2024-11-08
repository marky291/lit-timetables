<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lecturer;
use App\Services\SemesterPeriodDateService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class TimetableController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param Course $course
     * @return Application|Factory|View|Response
     */
    public function course(Course $course): Factory|View|Response|Application
    {
        return $this->render($course);
    }

    /**
     * Display the specified resource.
     *
     * @param Lecturer $lecturer
     * @return Factory|View|Response|Application
     */
    public function lecturer(Lecturer $lecturer): Factory|View|Response|Application
    {
        return $this->render($lecturer);
    }

    /**
     * Retrieve all the schedules belonging to a course
     *
     * @param Lecturer|Course $model
     * @return Application|Factory|View
     */
    private function render(Lecturer|Course $model): Application|Factory|View
    {
        return view('timetable', [
            'model' => $model,
            'semester' => app(SemesterPeriodDateService::class),
        ]);
    }
}
