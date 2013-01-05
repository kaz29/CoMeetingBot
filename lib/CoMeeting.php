<?php
// 大分やっつけ感がある感じ...orz
class CoMeeting {
    private $url = 'https://www.co-meeting.com';
    private $cookie = null;
    private $params = null;

    public function __construct($config='config.php')
    {
        include($config);

        $this->params = $params;
    }

    /**
     * co-meetingに問い合わせを送信する
     *
     * @param string $method (list_groups/list_meetings)
     * @return array
     * @author 
     **/
    public function query($method=null)
    {
        if (is_null($this->cookie)) {
            $result = $this->authenticate();
            if ($result !== true) {
                return false;
            }
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
//        curl_setopt($ch, CURLOPT_URL, "https://www.co-meeting.com/api/{$method}.json");
        curl_setopt($ch, CURLOPT_URL, "http://localhost/test.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, false) ;
        curl_setopt($ch, CURLOPT_POST, true) ;
        curl_setopt($ch, CURLOPT_COOKIE, $this->cookie) ;

        curl_setopt($ch, CURLOPT_POSTFIELDS, array());
        $this->response = curl_exec($ch);
        list($response_header, $response_body) = explode("\r\n\r\n", $this->response, 2);
        for(;;) {
          if (strncmp($response_body, 'HTTP/', 5) != 0)
            break ;
          list($response_header, $response_body) = explode("\r\n\r\n", $response_body, 2);
        }

        return json_decode($response_body);
    }

    /**
     * co-meetingにメッセージを書き込む
     *
     * @param string $meeting_id
     * @param string $message (list_groups/list_meetings)
     * @return array
     * @author 
     **/
    public function post($meeting_id, $message)
    {
        if (is_null($this->cookie)) {
            $result = $this->authenticate();
            if ($result !== true) {
                return false;
            }
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_URL, "https://www.co-meeting.com/m/meeting/{$meeting_id}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, false) ;
        curl_setopt($ch, CURLOPT_POST, true) ;
        curl_setopt($ch, CURLOPT_COOKIE, $this->cookie) ;

        $post = array(
            'message' => $message,
        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $this->response = curl_exec($ch);
        return $this->response;
    }

    private function authenticate()
    {
        $this->cookie = null;
        $post = array(
            'user[email]' => $this->params['email'],
            'user[password]' => $this->params['password'],
            'user[remember_me]' => 1,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_URL, $this->url.'/users/login');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, false) ;
        curl_setopt($ch, CURLOPT_POST, true) ;

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $this->response = curl_exec($ch);
        if ( $this->response === false ) {
            return false ;
        }

        $this->response_status  = curl_getinfo($ch);
        curl_close($ch);

        list($response_header, $response_body) = explode("\r\n\r\n", $this->response, 2);
        for(;;) {
          if (strncmp($response_body, 'HTTP/', 5) != 0)
            break ;
          list($response_header, $response_body) = explode("\r\n\r\n", $response_body, 2);
        }

        $headers = explode("\r\n", $response_header);
        foreach($headers as $header) {
            list($k, $v) = explode(' ', $header, 2);
            if (strncmp($k, "Set-Cookie:", 11) === 0 && strncmp($v, '__csid=', 7) === 0) {
                $this->cookie = $v;
                break ;
            }
        }

        return !is_null($this->cookie);
    }
}
