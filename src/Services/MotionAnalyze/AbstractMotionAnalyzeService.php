<?php

namespace App\Services\MotionAnalyze;

use App\Services\AbstractService;

class AbstractMotionAnalyzeService extends AbstractService
{
    const MOTION_ANALYZE_URL = "http://localhost:5000/process-data";
}