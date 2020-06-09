<?php

/**
 * 根据ID得到日志信息
 * @param int $id
 * @return array
 */
function getLogById($id){
    $sql="select id,ptime,pdesc from pg_log where id={$id}";
    return fetchOne($sql);
}
