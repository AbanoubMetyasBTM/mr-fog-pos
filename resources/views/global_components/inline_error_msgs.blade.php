<?php

    $msg = \Session::get("msg");
    if($msg == ""){
        if (count($errors->all()) > 0)
        {
            $dump = "<div class='alert alert-danger' style='text-align: center;'>";
            foreach ($errors->all() as $key => $error)
            {
                $dump .= $error." <br>";
            }
            $dump   .= "</div>";
            $msg    = $dump;
        }
    }

?>
{!! $msg !!}