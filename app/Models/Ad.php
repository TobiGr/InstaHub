<?php

namespace App\Models;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = ['name', 'type', 'priority', 'url', 'img', 'query', 'budget', 'budget_remaining', 'clicks'];

    public static function getAd($photo_id = null)
    {
        $ads = null;
        $id = null;

        if ($photo_id) {
            $ads = self::where('type', '=', 'banner')->orderBy('priority', 'asc')->get();
        } else {
            $ads = self::where('type', '=', 'photo')->orderBy('priority', 'asc')->get();
        }

        foreach ($ads as $ad) {
            //prepare query
            $sql = $ad->query;
            $sql = str_replace('$user', Auth::id(), $sql);
            $sql = str_replace('$photo', $photo_id, $sql);

            //run query
            try {
                $r = DB::select($sql);
                $r = (array) $r;
            } catch (\Illuminate\Database\QueryException $ex) {
                $r = null;
                flash('<p> Ad <b>'.$ad->name.'</b> is broken: </p><p>'.$ex->getMessage().'</p>', 'danger')->important();
            }

            if ($r) { //check if someone wants to indicate with an empty result that this is false
                $r = (array) $r;
                if (count($r) > 1) {
                    //list of possible ids
                    for ($i = 0; $i < count($r); $i++) {
                        if ((current($r[$i]) == Auth::id() && ! $photo_id) ||
                            (current($r[$i]) == $photo_id && $photo_id)) {
                            $id = $ad->id;
                        }
                    }
                    if ($id) {
                        break;
                    } //leaves foreach
                } else {
                    //true or false
                    if (current($r[0])) {
                        $id = $ad->id;
                        break; //leaves foreach
                    }
                }
            }
        }

        return self::find($id);
    }

    public function clickOnAdd() {

    }

}
