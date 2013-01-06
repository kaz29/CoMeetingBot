<?php
require 'NotifyBase.php';

class GithubNotify extends NotifyBase
{
    protected function requestToNotifyData()
    {
        $request = $this->loadRequest();

        $data = array();

        list(,,$branch) = explode('/', $request->ref, 3);
        $branch_name = "{$request->repository->name}/{$branch}";

        $data['link'] = $request->compare;
        $data['name'] = "{$request->pusher->name} pushed new stuff to {$branch_name}";

        if (count($request->commits) > 0 ) {
            $data['message'] = '';
            foreach($request->commits as $key => $commit) {
                if ($key >= 10) {
                    $data['message'] .= "...\n";
                    break ;
                }
                $data['message'] .= "[{$commit->author->username}] {$commit->message} - {$commit->url}\n";
            }
        }

        $data['body'] = $data['name'].str_repeat(' ', 300).$data['message'].str_repeat(' ', 30).$data['link'];

        return $data;
    }

    protected function loadRequest()
    {
        if (isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
            list(,$payload) = explode('=', $GLOBALS['HTTP_RAW_POST_DATA']);
            $requests = json_decode(urldecode($payload));
            $result = json_last_error();
            if ($result !== JSON_ERROR_NONE) {
                return false;
            }
        }

        return $requests;
    }
}