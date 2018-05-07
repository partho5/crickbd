<?php
/**
 * Created by PhpStorm.
 * User: nrriaz
 * Date: 5/7/18
 * Time: 5:23 PM
 */

namespace App\Library;

use App\Innings;
use Illuminate\Support\Facades\DB;

class BasicInningsInfo
{
    public $inn_id = null;
    public $inn_data = [
        'runs' => null,
        'wickets' => null,
        'overs' => null
    ];

    public function __construct($id)
    {
        $this->inn_id = $id;
        $this->getInningsData();
    }

    public function getInningsData()
    {
        $this->inn_data['runs']=$this->getRuns($this->inn_id);
        $this->inn_data['overs']=$this->getOvers($this->inn_id);
        $this->inn_data['wickets']=$this->getWickets($this->inn_id);
    }

    public function getOvers($inn_id)
    {
        return DB::select('SELECT max(cast(ball_number as decimal(4,1))) AS overs FROM balls WHERE innings_id=?', [$inn_id])[0]->overs;
    }

    public function getRuns($inn_id)
    {
        return DB::select('SELECT sum(run) AS total_run FROM balls WHERE innings_id=?', [$inn_id])[0]->total_run;
    }

    public function getWickets($inn_id)
    {
        return DB::select('select count(*) as wickets from balls where innings_id=? and incident is not null', [$inn_id])[0]->wickets;
    }
}