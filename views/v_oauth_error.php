<?php defined('SYSPATH') or die('No direct script access.');
?><style type="text/css" media="screen">/*<![CDATA[*/
table{
    border:1px solid #ccc;margin:10px
}
tbody{
    border-top:1px solid #ccc;margin:5px 0
}
tbody th{
    vertical-align: middle
}
/*]]>*/</style><table><caption><h3>Service error codes description</h3></caption><thead>
    <tr><th>Request Stage</th><th>Error Code</th><th>Description</th><th>URI</th></tr>
    </thead><?php
    if(isset($req_code_errors))
    {
        echo '<tbody>';
        $count = count($req_code_errors);
        foreach($req_code_errors as $error_code => $error_info)
        {
            ?><tr><?php
                if($count) {
                    echo '<th rowspan="'.$count.'">'.__('Authorization Response').'</th>';
                    $count = FALSE;
                }
            ?><td><a href="/<?php echo Request::$current->uri(array('controller'=>'server','action'=>'error','id'=>$error_code));
            ?>"><?php echo $error_code; ?></a></td><td><?php echo $error_info['error_description']; ?></td><td>/<?php
                echo Request::$current->uri(array('controller'=>'server','action'=>'error','id'=>$error_code)); ?></td></tr><?php
        }
        echo '</tbody>';
    }

    if(isset($req_token_errors))
    {
        echo '<tbody>';
        $count = count($req_token_errors);
        foreach($req_token_errors as $error_code => $error_info)
        {
            ?><tr><?php
                if($count) {
                    echo '<th rowspan="'.$count.'">'.__('Access Token Response').'</th>';
                    $count = FALSE;
                }
            ?><td><a href="/<?php echo Request::$current->uri(array('controller'=>'server','action'=>'error','id'=>$error_code));
            ?>"><?php echo $error_code; ?></a></td><td><?php echo $error_info['error_description']; ?></td><td>/<?php
                echo Request::$current->uri(array('controller'=>'server','action'=>'error','id'=>$error_code)); ?></td></tr><?php
        }
        echo '</tbody>';
    }

    if(isset($access_res_errors))
    {
        echo '<tbody>';
        $count = count($access_res_errors);
        foreach($access_res_errors as $error_code => $error_info)
        {
            ?><tr><?php
                if($count) {
                    echo '<th rowspan="'.$count.'">'.__('Access Protected Resource').'</th>';
                    $count = FALSE;
                }
            ?><td><a href="/<?php echo Request::$current->uri(array('controller'=>'server','action'=>'error','id'=>$error_code));
            ?>"><?php echo $error_code; ?></a></td><td><?php echo $error_info['error_description']; ?></td><td>/<?php
                echo Request::$current->uri(array('controller'=>'server','action'=>'error','id'=>$error_code)); ?></td></tr><?php
        }
        echo '</tbody>';
    }
?></table>