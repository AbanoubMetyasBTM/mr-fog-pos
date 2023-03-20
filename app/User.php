<?php

namespace App;

use App\models\ModelUtilities;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Illuminate\Support\Collection;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;


    protected $fillable = [
        'branch_id',
        'user_type',
        'user_role',
        'user_enc_id',
        'logo_img_obj', 'email', 'temp_email',
        'first_name', 'last_name', 'full_name',
        'password', 'password_changed_at',
        'remember_token', 'phone', 'phone_code',
        'verification_code', 'verification_code_expiration', 'password_reset_code', 'password_reset_expire_at',
        'is_active',
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $primaryKey = 'user_id';

    protected $dates = ["deleted_at"];

    static function get_users($attrs = [])
    {

        return self::getUsers($attrs);

    }

    static function getAdmins($attrs = [])
    {

        if (!isset($attrs["free_conds"])) {
            $attrs["free_conds"] = [];
        }
        $attrs["free_conds"][] = "users.user_type in ('dev','admin')";


        $res = User::select(DB::raw("
            users.*,
            users.created_at as 'user_created_at'
        "));

        return ModelUtilities::general_attrs($res, $attrs);

    }

    static function getBranchAdmins($attrs = [], $branch_id = null)
    {

        if (!isset($attrs["free_conds"])) {
            $attrs["free_conds"] = [];
        }
        $attrs["free_conds"][] = "users.user_type = 'branch_admin'";

        if ($branch_id != null) {
            $attrs["free_conds"][] = "users.branch_id = $branch_id";
        }

        $res = User::select(DB::raw("
            users.*,
            users.created_at as 'user_created_at'
        "));

        return ModelUtilities::general_attrs($res, $attrs);

    }

    static function getUsers($attrs = [])
    {

        $res = User::select(DB::raw("
            users.*,
            users.created_at as 'user_created_at'
        "));

        return ModelUtilities::general_attrs($res, $attrs);
    }

    static function getUserById(int $userId)
    {

        $attrs = [
            "free_conds" => ["users.user_id = {$userId}"],
            "return_obj" => "yes"
        ];

        return self::getUsers($attrs);

    }

    static function getUsersAtAdminPanel($userId = null)
    {

        $userId = Vsi($userId);

        $freeConds = [
            "users.user_type in ('user')"
        ];

        if ($userId != null) {
            $freeConds[] = "users.user_id = {$userId}";
        }

        return self::getUsers([
            "free_conds" => $freeConds,
            "order_by"   => ["users.user_id", "desc"],
            "paginate"   => 50
        ]);

    }

    static function getUsersByIds(array $userIds)
    {

        if (count($userIds) == 0) {
            return collect([]);
        }

        $attrs = [
            "free_conds" => [
                "users.user_id in (" . implode(",", $userIds) . ")"
            ]
        ];

        return self::get_users($attrs);

    }

    public static function getUserByEncId(string $userEndId)
    {
        $attrs               = [];
        $attrs['free_conds'] = ['users.user_enc_id="' . Vsi($userEndId) . '"'];
        $attrs['return_obj'] = "yes";

        $user = self::getUsers($attrs);

        if (!is_object($user)) {
            return null;
        }

        return $user;

    }

    public static function getUserByEmailAndType(string $email, string $user_type): ?object
    {
        return self::where("email", $email)->where("user_type", $user_type)->first();
    }


    public static function getUser(array $data): ?object
    {
        return self::where($data)->first();
    }


    public static function createUser(array $data): object
    {
        return self::create($data);
    }

    public static function updateUser(array $data, int $user_id): bool
    {
        return self::where('user_id', $user_id)->update($data);
    }

    public static function getUserProfile(int $user_id): ?object
    {
        $cond   = [];
        $cond[] = ["users.user_id", "=", $user_id];
        return self::get_users([
            'additional_and_wheres' => $cond,
            'return_obj'            => "yes"
        ]);
    }


    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            users.*
        "));

        if(isset($attrs["need_branch_join"])){
            $results = $results->leftJoin("branches","branches.branch_id","=","users.branch_id");
            $results = $results->addSelect(\DB::Raw("
                branch_name
            "));
        }

        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function checkIfBranchHasUsersWithType($branch_id, $user_type): bool
    {
        // type => employee, client
        $users =
            self::query()
                ->where('branch_id', '=', $branch_id)
                ->where('user_type', '=', $user_type)
                ->get();

        if (!count($users)) {
            return false;
        }
        return true;

    }

    public static function getAllUsersWithType($type)
    {
        return self::getData([
            "free_conds" => [
                "user_type = '{$type}'"
            ],
        ]);

    }


    public static function getAllUsersWithTypeOrSpecificBranch($type, array $attrs = []): Collection
    {

        if (!isset($attrs["free_conds"])){
            $attrs["free_conds"] = [];
        }


        $currentBranchId = \Session::get("current_branch_id");
        if (!empty($currentBranchId)){
            $attrs["free_conds"][] = "users.branch_id = $currentBranchId";
        }

        $attrs["free_conds"][] = "users.user_type = '{$type}'";


        $attrs[ "need_branch_join"] = true;

        return self::getData($attrs);

    }



    public static function getUsersTypeEmployeeByBranchId($branchId)
    {

        return self::getData([
            "free_conds" => [
                "user_type = 'employee'",
                "branch_id = '{$branchId}'"
            ],
        ]);

    }


}
