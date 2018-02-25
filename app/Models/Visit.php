<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Utils\Functions;

class Visit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page', 'ip', 'city'
    ];

    /**
     * 记录访问数据
     *
     * @param object $request
     * @param string $page
     */
    static public function record($request, $page = '', $title = '')
    {
        $city = Functions::getCity($request->ip());
        $visit = new Visit;
        if ($request->user()) {
            $visit->user_id = $request->user()->id;
        }
        $visit->path = $request->path();
        $visit->page = $page;
        $visit->title = $title;
        $visit->ip = $request->ip();
        $visit->city = $city['region'] . ' ' . $city['city'];
        $visit->client = $request->userAgent();
        $visit->save();
    }
}
