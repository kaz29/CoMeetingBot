<?php
require 'NotifyBase.php';

class JenkinsNotify extends NotifyBase
{
    protected function requestToNotifyData()
    {
        $request = $this->loadRequest();
        if ($request === false) {
            return false;
        }

        if (isset($_GET['force']) !== true) {
            if ($request->build->phase !== 'FINISHED') {
                return false;
            }

            if (!isset($request->build->status)) {
                return false;
            }

            if ($request->build->status === 'SUCCESS') {
                return false;
            }
        }

        $data = array(
            'link' => $request->build->full_url,
            'name' => "Project:{$request->name} BUILD-{$request->build->phase}:{$request->build->status}",
        );

        $data['body'] = $data['name'].str_repeat(' ', 300).$data['link'];

        return $data;
    }
    
    private function loadRequest()
    {
        /*
        $response = new StdClass();
        $response->build = new StdClass();

        $response->build->phase = 'START';
        $response->build->status = 'FINISHED';
        $response->name = 'TEST';
        $response->build->full_url = 'http://examle.com/';

        return $response;
        */
        
        $requests = false;
        if (isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
            $requests = json_decode(urldecode($GLOBALS['HTTP_RAW_POST_DATA']));
            $result = json_last_error();
            if ( $result !== JSON_ERROR_NONE ) {
                return false;
            }
        }

        return $requests;
    }
}
