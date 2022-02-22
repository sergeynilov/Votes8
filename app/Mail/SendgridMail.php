<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sichikawa\LaravelSendgridDriver\SendGrid;
use App\Settings;
use App\Http\Traits\funcsTrait;

class SendgridMail extends Mailable
{
    use Queueable, SerializesModels;
    use SendGrid;
    use funcsTrait;

    private  $m_view_name;
    private  $m_to;
    private  $m_cc;
    private  $m_subject;
    private  $m_additiveVars;
    private  $m_attachFiles;

    public function __construct( $view_name, $to= [], $cc= '', $subject= '', $additiveVars= [], $attachFiles= [] )
    {
        $this->m_view_name= $view_name;
        $this->m_to= $to;
        $this->m_cc= $cc;
        $this->m_subject= $subject;
        $all_emails_copy      =   \Config::get('app.all_emails_copy');
//        echo '<pre>$all_emails_copy::'.print_r($all_emails_copy,true).'</pre>';

        if ( empty($this->m_cc) and !empty($all_emails_copy)) {
            $this->m_cc= $all_emails_copy;
        }

//        echo '<pre>$this->m_cc::'.print_r($this->m_cc,true).'</pre>';

        $additiveVars['site_home_url']         = \URL::to('/');
        $additiveVars['site_name']             = Settings::getValue('site_name');
        $additiveVars['noreply_email']         = Settings::getValue('noreply_email');
        $additiveVars['support_signature']     = Settings::getValue('support_signature');
        $additiveVars['medium_slogan_img_url'] = config('app.url').config('app.medium_slogan_img_url');
        $additiveVars['is_developer_comp']     = $this->isDeveloperComp();

        $this->m_additiveVars= $additiveVars;
        $this->m_attachFiles= $attachFiles;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build( )
    {
        $mailObject= $this
            ->view( $this->m_view_name)
            ->subject($this->m_subject)
            ->to([$this->m_to])
            ->cc([$this->m_cc])
            ->with( $this->m_additiveVars )
            ->sendgrid( $this->m_additiveVars );
        foreach( $this->m_attachFiles as $next_attach_file) {
            if ( file_exists($next_attach_file) ) {
                $mailObject->attach($next_attach_file);
            }
        }
        return $mailObject;
    }

}
