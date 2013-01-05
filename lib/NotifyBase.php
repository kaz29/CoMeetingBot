<?php
require 'CoMeeting.php';

class NotifyBase
{
    public function __construct($meeting_id)
    {
        $this->meeting_id = $meeting_id;
    }

    public function notify()
    {
        $this->CoMeeting = new CoMeeting();
        $params = $this->requestToNotifyData();
        if ($params === false) {
            echo "ERROR";
            return;
        }

        $ret = $this->CoMeeting->post(
            $this->meeting_id,
            $params['body']
        );

    }
}