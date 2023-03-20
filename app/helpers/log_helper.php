<?php


function createLog(\Illuminate\Http\Request $request, $data)
{

    $action_type = '';

    if(isset($data["action_type"])){
        $action_type = $data["action_type"];
    }
    elseif(isset($data["item_id"])){
        $action_type = "add";

        if ($data["item_id"] != null) {
            $action_type = 'update';
        }
    }


    $data['module'] = explode("\\", $data['module']);
    $data['module'] = $data['module'][count($data['module'])-1];
    $data['module'] = str_lreplace("_m","", $data['module']);

    return \App\models\employee_action_log_m::createLog([
        'user_id'         => $data['user_id'],
        'module'          => strtolower($data['module']),
        'action_url'      => strtolower($data['action_url']),
        'action_type'     => $action_type,
        'old_obj'         => json_encode($data['old_obj'] ?? ""),
        'request_headers' => json_encode($request->headers),
        'request_body'    => json_encode($request->except("_token")),
        'logged_at'       => date("Y-m-d H:i:s"),
    ]);

}
