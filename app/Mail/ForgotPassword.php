<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;
    protected $data = [];
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function build(){
        return $this->view('email.verifyEmail')
        ->with(['email' => $this->data['email'],'code'=>$this->data['code'],'link'=>$this->data['link']])
        ->from(env('MAIL_FROM_ADDRESS', 'hufflepufftifgol.a@gmail.com'), env('APP_NAME', 'Nganjuk Elok'));
    }
}