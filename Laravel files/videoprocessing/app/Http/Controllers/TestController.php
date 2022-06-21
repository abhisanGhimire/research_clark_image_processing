<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class TestController extends Controller
{
    public function index(Request $request)
    {

        $starttime = microtime(true);

        set_time_limit(36000);

        if ($request->multiple != null) {

            $result            = explode(",", $request->multiple);
            $id_collection     = [];
            $failed_collection = [];
            $idk_why_fail=[];
            foreach ($result as $id) {
                $process = new Process(['python', 'C:\Users\Invictus\PycharmProjects\videoprocessing\main.py', trim($id)]);
                $process->setTimeout(36000);
                $process->run();

                if (!$process->isSuccessful()) {
                    array_push($idk_why_fail, trim($id));
                    continue;
                }
                $output = $process->getOutput();
                $result = preg_split('/\r\n|\r|\n/', $output);
                if ($result[1] == "Failed") {
                    array_push($failed_collection, trim($id));} else {
                    array_push($id_collection, trim($id));
                }
            }
            $success_count = 0;
            $failure_count = 0;
            $idk_why_fail_count = 0;
            $success_id    = "Success : ";
            foreach ($id_collection as $id) {
                $success_id = $success_id . $id . " , ";
                $success_count++;
            }
            $success_id = $success_id . "Failed : ";
            foreach ($failed_collection as $id) {
                $success_id = $success_id . $id . ",";
                $failure_count++;
            }
            $success_id = $success_id . "id_why_Failed : ";
            foreach ($idk_why_fail as $id) {
                $success_id = $success_id . $id . " , ";
                $idk_why_fail_count++;
            }
            $success_id = $success_id . " SC : " . $success_count . ", FC : " . $failure_count.", IDF : " . $idk_why_fail_count;
              /* do stuff here */
        $endtime  = microtime(true);
        $timediff = $endtime - $starttime;
        $success_id=$success_id." Time : ".round($timediff,1)."s";
            return back()->with('success', $success_id);

        } else {
            $id      = $request->single;
            $process = new Process(['python', 'C:\Users\Invictus\PycharmProjects\videoprocessing\main.py', $id]);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            return back()->with('success', "Success");
        }
    }
}
