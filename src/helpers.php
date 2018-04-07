<?php

/**
 * ID转Hash
 * @param int $id
 * @return string
 */
function id2hash($id = 0)
{
    return (new \Hashids\Hashids())->encode($id);
}

/**
 * Hash转ID
 * @param string $hash
 * @return mixed
 */
function hash2id($hash = '')
{
    $result = (new \Hashids\Hashids())->decode($hash);
    return !empty($result) ? $result[0] : null;
}

/**
 * 返回Json响应
 *
 * @param $status
 * @param string $info
 * @param array $data
 * @return \Slim\Http\Response
 */
function jsonRes($status, $info = 'OK', $data = [])
{
    $res = compact('status', 'info');
    if ($data) {
        $res['data'] = $data;
    }
    return (new \Slim\Http\Response())->withJson($res, 200, JSON_UNESCAPED_UNICODE);
}