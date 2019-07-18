<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use Illuminate\Support\Carbon;

class Complaint extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Wildside\Userstamps\Userstamps;
    use Traits\EncryptableModel; 
    
    
    protected $guarded = ['id', 'creator', 'user', 'author', 'accused', 'type', 'procedure', 'status', 'tracings'];
    
    protected $with = ['status', 'type', 'author'];
    
    protected $encryptable = [
        'contact_phone', 
        'contact_available', 
        'description', 
        'accused_relation', 
        'facts', 
        'frequency', 
        'date_since',
        'date_until', 
        'time',
        'place', 
        'attitude', 
        'reaction', 
        'other_cases', 
        'witnesses', 
        'made_changes', 
        'affectation', 
        'affect_info', 
        'long_terms_considerations', 
        'psy_support', 
        'medical_support', 
        'boss_knowledge', 
        'solution', 
        'evidence', 
        'aditional_info'];

    protected $dates = ['created_at'];

    public function status() {
        return $this->belongsTo('App\ComplaintStatus', 'status_id');
    }
    
    public function type() {
        return $this->belongsTo('App\ComplaintType', 'type_id')->withDefault();
    }
    
    public function procedure() {
        return $this->hasOne('App\Procedure', 'complaint_id');
    }
    
    public function resolution() {
        return $this->belongsTo('App\Resolution', 'resolution_id')->withDefault();
    }
    
    public function survey() {
        return $this->hasOne('App\Survey', 'complaint_id');
    }
    
    public function tracings() {
        return $this->hasMany('App\Tracing', 'complaint_id');
    }
    
    public function author() {
        return $this->belongsTo('App\User', 'created_by')->withTrashed();
    }
    
    public function responsible() {
        return $this->belongsTo('App\User', 'assigned_to')->withDefault();
    }
    
    public function accused() {
        return $this->belongsTo('App\User', 'accused_id')->withDefault();
    }
    
    public function files() {
        return $this->hasMany('App\CaseFile', 'complaint_id');
    }
    
    public function getFolio() {
        return $this->folio . "/" . $this->created_at->year;
    }
    
    public function notif() {
        $to = array();
        switch($this->status_id){
            case 1: 
                $text = "Por este medio se le comunica que ha sido <b>RECIBIDA</b> una nueva "
                    . "DENUNCIA, a travéz del Sistema de Ombudsperson."
                    . "<p>Por lo cual se le invita a ingresar al <a href = '//intranet.strc.guanajuato.gob.mx'>Sistema</a> "
                    . "para revisar los detalles de la misma.</p>";
                $to = User::where('role_id', 1)->pluck('email');
                $subject = "Nueva denuncia recibida";
                break;
            case 2: 
                $text = "Por este medio se le comunica que ha sido <b>REVISADA</b> su DENUNCIA "
                    . "con folio <b>" . $this->getFolio() . "</b>, a travéz del Sistema de Ombudsperson."
                    . "<p>Por lo cual se le invita a ingresar al <a href = '//intranet.strc.guanajuato.gob.mx'>Sistema</a> "
                    . "para revisar el SEGUIMIENTO de la misma, así como la <b>CITA</b> programada para su "
                    . "entrevista.</p><p><u>Es importante al mismo tiempo, que complete los <b>DETALLES DE LA DENUNCIA</b>, "
                    . "ABRIENDO SU EXPEDIENTE y dirigiéndose a la <b>PESTAÑA de DETALLES</b> "
                    . "en el apartado correspondiente que verá al entrar.</u></p>";
                $to[] = $this->author->email;
                $subject = "Nuevo aviso sobre denuncia Ombudsperson";
                break;
            case 5: 
                $text = "Por este medio se le comunica que ha sido <b>CERRADA</b> su DENUNCIA "
                    . "con folio <b>" . $this->getFolio() . "</b>, a travéz del Sistema de Ombudsperson."
                    . "<p>Por lo cual se le invita a ingresar al <a href = '//intranet.strc.guanajuato.gob.mx'>Sistema</a> "
                    . "para revisar la <b>RESOLUCIÓN</b> de la misma.</p>";
                $to[] = $this->author->email;
                $subject = "Nuevo aviso sobre denuncia Ombudsperson";
                break;
        }
//        dd($to);
        foreach($to as $mail){
            insertMail($mail, $subject, $text);
            insertIntranet($mail, $subject);
        }
    }
}
