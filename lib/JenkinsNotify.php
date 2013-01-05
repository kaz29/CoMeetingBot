<?php
require 'NotifyBase.php';

class JenkinsNotify extends NotifyBase
{
    protected function requestToNotifyData()
    {
        $request = $this->loadRequest();
        if ($request->build->phase !== 'FINISHED') {
            return false;
        }

        if (!isset($request->build->status)) {
            return false;
        } 

        if ($request->build->status === 'SUCCESS') {
            return false;
        } 

        $data = array(
            'link' => $request->build->full_url,
            'name' => "{$request->name} build {$request->build->status}",
        );

        $data['body'] = $data['name'].str_repeat(' ', 300).$data['link'];

        return $data;
    }
    
    private function loadRequest()
    {
        if( isset($GLOBALS['HTTP_RAW_POST_DATA']) ) {
            $requests = json_decode(urldecode($GLOBALS['HTTP_RAW_POST_DATA']));
            $result = json_last_error();
            if ( $result !== JSON_ERROR_NONE ) {
                return false;
            }
        }

        return $requests;
    }
}