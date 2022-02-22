<?php namespace App\library {

    use Auth;
    use DB;
    use Carbon\Carbon;
    use App\User;
    use App\ContactUs;
    use App\Settings;
    use App\CronNotification;
    use App\Http\Traits\funcsTrait;
    use App\Mail\SendgridMail;


    class AppCronTasks
    {
        use funcsTrait;

        public function notifyNewContactUs(bool $remove_cron_notification_rows = false)
        {
            $cronTasksReceiversArray = config('app.cronTasksReceivers');
//            echo '<pre>notifyNewContactUs $cronTasksReceiversArray::' . print_r($cronTasksReceiversArray, true) . '</pre>';


//            echo '<pre>$remove_cron_notification_rows::' . print_r($remove_cron_notification_rows, true) . '</pre>';
            $newContactUsList = ContactUs::getByAccepted(0)->get();
//        public function newContactUsNotification(ContactUs $contactUs, bool $user_was_registered, string $verification_token)

            if (count($newContactUsList)) { // send email if only there are new contact us
                $beautymailWrapper = new BeautymailWrapper();
                $beautymailWrapper->newContactUsNotification($newContactUsList, $cronTasksReceiversArray);

                if ($remove_cron_notification_rows) {
                    foreach ($newContactUsList as $nextNewContactUs) {
                        //     public function scopeGetByCronTypeAndCronObjectId($query, $cron_type= null, $cron_object_id= null)

                        $cronNotification = CronNotification::getByCronTypeAndCronObjectId(CronNotification::NEW_CONTACT_US, $nextNewContactUs->id)->first();
                        if ( ! empty($cronNotification)) { // found Cron Notification
//                            echo '<pre>$cronNotification->id::' . print_r($cronNotification->id, true) . '</pre>';
                            $cronNotification->delete();   // and remove it as we need to send CronNotification email only once
                        } // if ( !empty($cronNotification) ) { // found Cron Notification
                    }
                } // if ($remove_cron_notification_rows) {
            } // if ( count($newContactUsList) ) { // send email if only there are new contact us

        } // public function notifyNewContactUs(bool $remove_cron_notification_rows = false)


        public function notifyNewUsers(bool $remove_cron_notification_rows = false)
        {
            $cronTasksReceiversArray = config('app.cronTasksReceivers');
//            echo '<pre>notifyNewUser $cronTasksReceiversArray::' . print_r($cronTasksReceiversArray, true) . '</pre>';


//            echo '<pre>$remove_cron_notification_rows::' . print_r($remove_cron_notification_rows, true) . '</pre>';
            $newUsersList = User::getByStatus("N")->get();
            if (count($newUsersList)) { // send email if only there are new users

                foreach ($cronTasksReceiversArray as $nextCronTasksReceiver) { // all admins of the site who would receive email notifications

                    $next_cron_tasks_receiver_email = $nextCronTasksReceiver['email'];
                    $next_cron_tasks_receiver_name  = $nextCronTasksReceiver['name'];

                    \Mail::to($next_cron_tasks_receiver_email)->send(new SendgridMail('emails/cron/new_user_notification', $next_cron_tasks_receiver_email, '', $subject,
                        $additiveVars));
                }
//                $beautymailWrapper = new BeautymailWrapper();
//                $beautymailWrapper->newUsersNotification($newUsersList, $cronTasksReceiversArray);

                if ($remove_cron_notification_rows) {
                    foreach ($newUsersList as $nextNewUser) {
                        //     public function scopeGetByCronTypeAndCronObjectId($query, $cron_type= null, $cron_object_id= null)
                        $cronNotification = CronNotification::getByCronTypeAndCronObjectId(CronNotification::NEW_USER, $nextNewUser->id)->first();
                        if ( ! empty($cronNotification)) { // found Cron Notification
//                            echo '<pre>$cronNotification->id::' . print_r($cronNotification->id, true) . '</pre>';
                            $cronNotification->delete();   // and remove it as we need to send CronNotification email only once
                        } // if ( !empty($cronNotification) ) { // found Cron Notification
                    }
                } // if ($remove_cron_notification_rows) {*/
            } // if ( count($newUsersList) ) { // send email if only there are new users

        } // public function notifyNewUser(bool $remove_cron_notification_rows = false)

    }
}