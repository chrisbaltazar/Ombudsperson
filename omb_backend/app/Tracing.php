<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;

use Illuminate\Support\Carbon;

class Tracing extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Wildside\Userstamps\Userstamps;
    
    protected $guarded = ['id', 'date', 'time'];
    
//    protected $dates = ['meeting_date'];


    public function complaint() {
        return $this->belongsTo('App\Complaint', 'complaint_id');
    }
    
    public function notif() {
        if($this->type == "ENTREVISTA"){
            Carbon::setLocale('es');
            $text = "Por este medio se le comunica que ha sido <b>AGENDADA o ACTUALIZADA</b> su CITA sobre "
                . "su DENUNCIA con folio <b>" . $this->complaint->getFolio() . "</b>, con fecha <b>" . Carbon::parse($this->meeting_date)->toDayDateTimeString() . "</b> "
                . "a travéz del Sistema de Ombudsperson."
                . "<p>Por lo cual se le invita a ingresar al <a href = '//intranet.strc.guanajuato.gob.mx'>Sistema</a> "
                . "para revisar el SEGUIMIENTO de la misma</p>";
            $mail = $this->complaint->author->email;
            $subject = "Nueva cita programada";
            insertMail($mail, $subject, $text);
        }
    }
}
